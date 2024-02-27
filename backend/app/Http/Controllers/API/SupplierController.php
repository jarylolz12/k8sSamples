<?php

namespace App\Http\Controllers\API;

use App\Buyer;
use App\CustomerBuyer;
use App\Events\SendInvitationVendorEvent;
use App\Http\Controllers\Controller;
use App\InvitationVendor;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Supplier;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Rules\IsArrayRule;
use App\Customer;
use App\Shipment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Http\Resources\Standard as StandardResource;

/**
 *
 * @authenticated
 *
 * @group Supplier
 *
 * APIs to manage the supplier resource
 *
 */
class SupplierController extends Controller
{

    const PER_PAGE = 35;

    /**
     * Get Shipment
     *
     * @queryParam shipment_id int required
     *
     * @response status=200 {
     *      "data": []
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByShipmentId(Request $request)
    {
        $get_suppliers = [];
        $user = Auth::user();
        $customers = $user->customersApi->pluck('id');

        $customer_id = ($user->default_customer_id !== null) ? $user->default_customer_id : $customers[0];

        if ($request->has('shipment_id')) {

            $shipment = Shipment::where('id', $request->input('shipment_id'))
                ->where('customer_id', $customer_id)
                ->first();

            if (isset($shipment->id)) {

                $get_suppliers = $shipment->suppliers;
                if (isset($get_suppliers[0])) {
                    foreach ($get_suppliers as $key => $get_supplier) {
                        $get_suppliers[$key]->name = $get_supplier->company_name;
                        unset($get_suppliers[$key]->company_name);
                        unset($get_suppliers[$key]->pivot);
                    }
                }
            }
        }

        return response()->json(['data' => $get_suppliers]);
    }




    /**
     * Display a listing of the supplier
     *
     * @authenticated
     *
     * @queryParam per_page int Size per page. Example: 50
     * @queryParam page int Page to view. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\Customer
     * @apiResourceModel App\Customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return Response
     */
    public function indexV2(Request $request)
    {
        //design for efficiency and fast performance in fetching suppliers
        //intended to replace the old suppliers index api once changes in FE is completed - Kenneth
        $user = Auth::user();
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();

        if (count($customers) > 0) {
            $customer = ( $get_authenticated_user->default_customer_id!==null ) ? $get_authenticated_user->default_customer_id : $customers[0];
        }

        $suppliers = Supplier::whereHas('customers', function($q) use ($customer) {
            $q->where('customers.id', $customer);
        });

        $check_customer = Customer::find($customer);

        if ($request->has('qry')) {
            $qry = $request->input('qry');
            $suppliers = $suppliers->where(function($q) use ($qry) {
                $q->where('company_name', 'LIKE', '%'.$qry.'%');
                $q->orWhere('address', 'LIKE', '%'.$qry.'%');
                $q->orWhere('phone', 'LIKE','%'.$qry.'%');
                $q->orWhereJsonContains('emails', $qry);
            });
        }

        $per_page = $request->has('per_page') ? intval($request->input('per_page')) : SupplierController::PER_PAGE;
        
        if ( $request->has('no_pagination')) {
            $suppliers = $suppliers->get();
        } else {
            $suppliers = $suppliers->paginate($per_page);
        }
        
        if ( count ($suppliers) > 0 ) {
            foreach ( $suppliers as $key => $supplier ) {
                $companyKey = NULL;
                if($supplier->connected_customer){
                    $companyKeyData = Customer::where('id', $supplier->connected_customer)->first('company_key');
                    $companyKey = $companyKeyData ? $companyKeyData->company_key : NULL;
                }
                $invitationStatus = InvitationVendor::select('status')->where('vendor_id', $supplier->id)->first();
                if (empty($invitationStatus)) {
                    $status = 'not_invited';
                } else if ($invitationStatus->status == 0) {
                    $status = 'invited';
                } else if ($invitationStatus->status == 1) {
                    $status = 'connected';
                }
                $suppliers[$key]->supplier_customer_company_name = $check_customer->company_name;
                $suppliers[$key]->emails = json_decode($supplier->emails);
                $suppliers[$key]->invitation_status = $status;
                $suppliers[$key]->company_key =  $companyKey;
            }
        }

        return response()->json($suppliers);

    }


    /**
     * Display a listing of the supplier
     *
     * @authenticated
     *
     * @queryParam per_page int Size per page. Example: 50
     * @queryParam page int Page to view. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\Customer
     * @apiResourceModel App\Customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return Response
     */

    public function index(Request $request)
    {

        $user = Auth::user();
//        $user = Auth::loginUsingId(240);
        $suppliers = [];

        if (isset($user->customersApi)) {
            //$suppliers = $user->customersApi[0]->suppliers;

            if (count($user->customersApi) > 0) {

                $customers = $user->customersApi->pluck('id');
                $customers = ($user->default_customer_id !== null) ? [$user->default_customer_id] : [$customers[0]];
                $customers = Customer::whereIn('id', $customers)->get();

                foreach ($customers as $customer) {
                    if (isset($customer->suppliers) && count($customer->suppliers) > 0) {
                        $customerSuppliers = $customer->suppliers;

                        foreach ($customerSuppliers as $key => $supplier) {
                            $invitationStatus = InvitationVendor::select('status')->where('vendor_id', $supplier->id)->first();
                            if (empty($invitationStatus)) {
                                $status = 'not_invited';
                            } else if ($invitationStatus->status == 0) {
                                $status = 'invited';
                            } else if ($invitationStatus->status == 1) {
                                $status = 'connected';
                            }
                            if (isset($supplier->emails) && $supplier->emails !== null && $supplier->emails !== '') {
                                $customerSuppliers[$key]->emails = json_decode($supplier->emails);
                            }
                            $customerSuppliers[$key]->supplier_customer_company_name = $customer->company_name;
                            $customerSuppliers[$key]->invitation_status = $status;

                            $pushIt = true;
                            if (count($suppliers) > 0) {

                                foreach ($suppliers as $keySecond => $value) {
                                    if ($value->id === $supplier->id) {
                                        $pushIt = false;
                                    }
                                }
                            }

                            if ($pushIt)
                                array_push($suppliers, $customerSuppliers[$key]);
                        }
                    }
                }

            }
        }


        if (is_null($request->per_page)) {
            return StandardResource::collection((new Collection($suppliers))->paginate(35));
            //return StandardResource::collection((new Collection($suppliers)));
        }

        if (is_numeric($request->per_page)) {
            $per_page = intval($request->per_page);
            return StandardResource::collection((new Collection($suppliers))->paginate($per_page));
        }

        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @authenticated
     *
     * @bodyParam custom_customers_id int[]
     * @bodyParam emails string[]
     * @response 200 scenario="Supplier created successfully" {
     *    "message": "Supplier created successfully"
     * }
     *
     * @response status=422 scenario="Validation error" {
     *    "message": "The given data was invalid.",
     *    "errors": {
     *        "company_name": [
     *            "The company_name field is required."
     *        ],
     *        "address": [
     *            "The address field is required."
     *        ],
     *        "phone": [
     *            "The phone field is required."
     *        ],
     *        "custom_customers_id": [
     *            "The custom_customers_id field is required."
     *        ],
     *        "emails": [
     *            "The emails field is required."
     *        ]
     *    }
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return Response
     */
    public function create(Request $request)
    {
        //

        $input = $request->all();

        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();

        try {
            $customers = ($get_authenticated_user->default_customer_id !== null) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        } catch (Exception $exception) {
            return response([
                'message' => 'No results found.'
            ], 404);
        }

        $validator = Validator::make($input, [
            'company_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'custom_customers_id' => ['required', new IsArrayRule],
            'emails' => new IsArrayRule
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors());

        $input['custom_customers'] = $input['custom_customers_id'];
        unset($input['custom_customers_id']);

        if (isset($input['emails']))
            $input['emails'] = json_encode($input['emails']);


        //query builder
        $id = DB::table('suppliers')
            ->insertGetId([
                'company_name' => $input['company_name'],
                'address' => $input['address'],
                'phone' => $input['phone'],
                'emails' => $input['emails']
            ]);

        $model = Supplier::find($id);

        $buildCustomerSuppliers = [];

        //build customer supplier relationship
        foreach ($customers as $key => $customer) {
            //foreach ($input['custom_customers'] as $key => $custom_customer) {
            array_push($buildCustomerSuppliers, [
                'supplier_id' => $model->id,
                'customer_id' => $customer
                //'customer_id' => $custom_customer
            ]);
        }

        if (count($buildCustomerSuppliers) > 0) {

            //attach customers
            DB::table('customer_supplier')
                ->insert($buildCustomerSuppliers);
        }

        //$model = Supplier::create($input);
        if($request->company_key){

            $companyIdRecord = Customer::where('company_key', $request->company_key)->first();

            $supplierId = $id;
            $customerId = $customers[0];
            $selectedCustomerId = $request->company_key;

            $customer = Customer::find($customerId);

            $buyer = new Buyer();
            $buyer->company_name = $customer->company_name;
            $buyer->address = $customer->address;
            $buyer->phone = $customer->phone;
            $buyer->emails = json_encode($customer->emails);
            $buyer->admin_user_id = null;
            $buyer->connected_customer = $customerId;
            $buyer->connection_generated = 1;
            if ($buyer->save()) {
                Supplier::where('id', $supplierId)->update(['connected_customer' => $companyIdRecord->id]);

                CustomerBuyer::updateOrCreate(
                    [
                        'customer_id' => $companyIdRecord->id,
                        'buyer_id' => $buyer->id
                    ],
                    [
                        'customer_id' => $companyIdRecord->id,
                        'buyer_id' => $buyer->id
                    ]
                );
            }
        }

        return $this->sendResponse(new StandardResource(StandardResource::make($model)), "Supplier created successfully.");

    }

    private function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    private function sendResponse($result, $message)
    {

        $response = [
            'success' => true,
            'data' => isset($result->resource) ? $result->resource : $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @bodyParam campany_name string required company name of the supplier. Example: Shifl
     * @bodyParam address string required company address of the supplier. Example: USA
     * @bodyParam phone string required company phone of the supplier. Example: 855-399-9945
     * @bodyParam email string required company email of the supplier. Example: Shifl@shifl.com
     * @apiResource App\Http\Resources\ScribeResources\SupplierResource
     * @apiResourceModel App\Supplier
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //campany_name
        //address
        //phone
        //admin_user_id
        //email
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @urlParam id int require Supplier ID
     *
     * @apiResource App\Http\Resources\ScribeResources\SupplierResource
     * @apiResourceModel App\Supplier
     *
     * @response 200 {
     *    "message": "Supplier fetched successfully."
     * }
     *
     * @response status=404{
     *    "message": "Supplier not found."
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $model = Supplier::find($id);
        if (is_null($model)) return $this->sendError("Supplier not found.");

        return $this->sendResponse(new StandardResource(StandardResource::make($model)), "Supplier fetched successfully.");
        //return new StandardResource(Supplier::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     *
     * @urlParam id int required The supplier ID.
     * @bodyParam campany_name string required company name of the supplier. Example: Shifl
     * @bodyParam address string required company address of the supplier. Example: USA
     * @bodyParam phone string required company phone of the supplier. Example: 855-399-9945
     *
     * @apiResource App\Http\Resources\ScribeResources\SupplierResource
     * @apiResourceModel App\Supplier
     *
     * @response 200 {
     *    "message": "Supplier updated successfully."
     * }
     * @response status=422 scenario="Validation error" {
     *    "message": "The given data was invalid.",
     *    "errors": {
     *        "company_name": [
     *            "The company_name field is required."
     *        ],
     *         "address": [
     *            "The address field is required."
     *        ],
     *         "phone": [
     *            "The phone field is required."
     *        ]
     *    }
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
        $input = $request->all();

        $validator = Validator::make($input, [
            'company_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) return $this->sendError('Validation Error.', $validator->errors());

        $model = Supplier::find($id);

        $model->company_name = $input['company_name'];
        $model->address = $input['address'];
        $model->phone = $input['phone'];

        if (isset($input['emails']))
            $model->emails = json_encode($input['emails']);

        //if (isset($input['custom_customers']))
        //  $model->custom_customers = $input['custom_customers'];

        $model->save();

        return $this->sendResponse(new StandardResource(StandardResource::make($model)), "Supplier updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @urlParam id int required The supplier ID.
     *
     * @response 200 {
     *   "message": "Supplier deleted successfully."
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $finalResponse = ['status' => 'ok'];
        $errorMessage = 'This Vendor cannot be deleted. It is associated with either a PO or Shipment.';
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept'     => 'application/json',
            'Authorization'  => 'Bearer ' . $token
        ])->baseUrl(getenv('PO_URL'))
        ->get(sprintf('/api/po/supplier/%d',$id));

        $response = json_decode(json_encode($response->json()));
        $status = 200;

        if ( isset($response->status) && $response->status === 'ok') {

            $checkSupplier = Supplier::find($id);
            if ( isset($checkSupplier->id) && isset($checkSupplier->shipments) && $checkSupplier->shipments!==null && $checkSupplier->shipments!=='' && count($checkSupplier->shipments) > 0) {
                $finalResponse['status']= 'not ok';
                $finalResponse['shipment'] = $checkSupplier->shipments;
                $finalResponse['error'] = $errorMessage;
            } else {
                Supplier::find($id)->delete();
            }

        } else {
            $finalResponse['status'] = 'not ok';
            $finalResponse['error'] = $errorMessage;
            $status = 400;
        }
        return response()->json(
            $finalResponse, $status
        );

        //return $this->sendResponse([], "Supplier deleted successfully.");
    }

    /**
     *
     *
     * Vendor
     *
     *
     * @authenticated
     *
     * @bodyParam vendorId int
     * @bodyParam defaultCustomerId int
     * @bodyParam emailAddress string
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */
    public function invite(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'vendorId' => 'required',
                'defaultCustomerId' => 'required',
                'emailAddress' => ['required', new IsArrayRule],
            ]);

            if ($validator->fails()) return response()->json($validator->errors(), 404);

            $emailAddress = $input['emailAddress'];
            $defaultCustomerId = $input['defaultCustomerId'];
            $vendorId = $input['vendorId'];

            $vendor = Supplier::select('company_name')->where('id', $vendorId)->first();
            $customer = Customer::select('company_name')->where('id', $defaultCustomerId)->first();

            if (isset($emailAddress))
                $emailAddressEncode = json_encode($emailAddress);

            $invitationVendor = new InvitationVendor();
            $invitationVendor->default_customer_id = $defaultCustomerId;
            $invitationVendor->vendor_id = $vendorId;
            $invitationVendor->email = $emailAddressEncode;
            $invitationVendor->status = 0;

            if ($invitationVendor->save()) {
                foreach ($emailAddress as $email) {
                    event(new SendInvitationVendorEvent($email, $vendor, $customer));
                }

                return response()->json(
                    [
                        'message' => 'Supplier successfully invited!'
                    ]
                );
            } else {
                return response()->json(
                    [
                        'message' => 'Something error! Please try again!'
                    ], 400
                );
            }
        } catch (Exception $exception) {
            return response()->json(
                [
                    'message' => $exception->getMessage()
                ], 400
            );
        }
    }
}
