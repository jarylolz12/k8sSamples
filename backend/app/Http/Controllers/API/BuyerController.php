<?php

namespace App\Http\Controllers\API;

use App\Customer;
use App\CustomerSupplier;
use App\Events\SendInvitationBuyerEvent;
use App\Http\Controllers\Controller;
use App\InvitationBuyer;
use App\Rules\IsArrayRule;
use App\Buyer;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Standard as StandardResource;

/**
 *
 * @authenticated
 *
 * @group Buyer
 *
 * Class BuyerController
 * @package App\Http\Controllers\API
 *
 */
class BuyerController extends Controller
{
    const PER_PAGE = 35;

    /**
     * Get Buyers V2
     *
     * @response status=200 {
     *      "current_page": 1,
     *      "data": [
     *          {
     *              "id": 4,
     *              "company_name": "Yoki shoes",
     *              "address": "Bacayo",
     *              "phone": "09265321825",
     *              "emails": [
     *                  "mike@gmail.com",
     *                  "suarezandrew@gmail"
     *              ],
     *              "admin_user_id": null,
     *              "created_at": "2022-08-26T14:39:51.000000Z",
     *              "updated_at": "2022-08-26T15:07:03.000000Z",
     *              "connected_customer": 0,
     *              "connection_generated": null,
     *              "supplier_customer_company_name": "SOUND AROUND",
     *              "invitation_status": "not_invited",
     *              "company_key": null
     *          }
     *      ],
     *      "first_page_url": "api/v2/buyers?page=1",
     *      "from": 1,
     *      "last_page": 1,
     *      "last_page_url": "api/v2/buyers?page=1",
     *      "next_page_url": null,
     *      "path": "api/v2/buyers",
     *      "per_page": 35,
     *      "prev_page_url": null,
     *      "to": 1,
     *      "total": 1
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return JsonResponse
    */

    public function indexV2(Request $request) {

        //design for efficiency and fast performance in fetching buyers
        //intended to replace the old buyers index api once changes in FE is completed -kenneth

        $user = Auth::user();
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();

        if (count($customers) > 0) {
            $customer = ( $get_authenticated_user->default_customer_id!==null ) ? $get_authenticated_user->default_customer_id : $customers[0];
        }

        $buyers = Buyer::whereHas('customers', function($q) use ($customer) {
            $q->where('customers.id', $customer);
        });

        $check_customer = Customer::find($customer);

        if ($request->has('qry')) {
            $qry = $request->input('qry');
            $buyers = $buyers->where(function($q) use ($qry) {
                $q->where('company_name', 'LIKE', '%'.$qry.'%');
                $q->orWhere('address', 'LIKE', '%'.$qry.'%');
                $q->orWhere('phone', 'LIKE','%'.$qry.'%');
                $q->orWhereJsonContains('emails', $qry);
            });
        }

        $per_page = $request->has('per_page') ? intval($request->input('per_page')) : BuyerController::PER_PAGE;
        
        if ( $request->has('no_pagination')) {
            $buyers = $buyers->get();
        } else {
            $buyers = $buyers->paginate($per_page);
        }

        

        if ( count ($buyers) > 0 ) {
            foreach ($buyers as $key => $buyer) {
                $companyKey = NULL;
                if($buyer->connected_customer){
                    $companyKeyData = Customer::where('id', $buyer->connected_customer)->first('company_key');
                    $companyKey = $companyKeyData ? $companyKeyData->company_key : NULL;
                }
                $invitationStatus = InvitationBuyer::select('status')->where('buyer_id', $buyer->id)->first();
                if ( empty($invitationStatus) ) {
                    $status = 'not_invited';
                } else if ($invitationStatus->status == 0) {
                    $status = 'invited';
                } else if ($invitationStatus->status == 1) {
                    $status = 'connected';
                }
                if (isset($buyer->emails) && $buyer->emails !== null && $buyer->emails !== '') {
                    $buyers[$key]->emails = json_decode($buyer->emails);
                }
                $buyers[$key]->supplier_customer_company_name = $check_customer->company_name;
                $buyers[$key]->invitation_status = $status;
                $buyers[$key]->company_key =  $companyKey;
            }
        }

        return response()->json($buyers);

    }


    /**
     * Get Buyers
     *
     * @response status=200 {
     *      "data": [
     *          {
     *              "id": 4,
     *              "company_name": "Yoki shoes",
     *              "address": "Bacayo",
     *              "phone": "09265321825",
     *              "emails": [
     *                  "mike@gmail.com",
     *                  "suarezandrew@gmail"
     *              ],
     *              "admin_user_id": null,
     *              "created_at": "2022-08-26T14:39:51.000000Z",
     *              "updated_at": "2022-08-26T15:07:03.000000Z",
     *              "connected_customer": 0,
     *              "connection_generated": null,
     *              "supplier_customer_company_name": "SOUND AROUND",
     *              "invitation_status": "not_invited",
     *              "pivot": {
     *                  "customer_id": 25,
     *                  "buyer_id": 4
     *              }
     *          }
     *      ],
     *      "links": {
     *          "first": "api/buyers?page=1",
     *          "last": "api/buyers?page=1",
     *          "prev": null,
     *          "next": null
     *      },
     *      "meta": {
     *          "current_page": 1,
     *          "from": 1,
     *          "last_page": 1,
     *          "path": "api/buyers",
     *          "per_page": 35,
     *          "to": 1,
     *          "total": 1
     *      }
    `*  }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $buyers = [];

            if (isset($user->customersApi)) {
                if (count($user->customersApi) > 0) {
                    $customers = $user->customersApi->pluck('id');
                    $customers = ($user->default_customer_id !== null) ? [$user->default_customer_id] : [$customers[0]];
                    $customers = Customer::whereIn('id', $customers)->get();

                    foreach ($customers as $customer) {
                        if (isset($customer->buyer) && count($customer->buyer) > 0) {
                            $customerBuyers = $customer->buyer;

                            foreach ($customerBuyers as $key => $buyer) {
                                $invitationStatus = InvitationBuyer::select('status')->where('buyer_id', $buyer->id)->first();
                                if (empty($invitationStatus)) {
                                    $status = 'not_invited';
                                } else if ($invitationStatus->status == 0) {
                                    $status = 'invited';
                                } else if ($invitationStatus->status == 1) {
                                    $status = 'connected';
                                }
                                if (isset($buyer->emails) && $buyer->emails !== null && $buyer->emails !== '') {
                                    $customerBuyers[$key]->emails = json_decode($buyer->emails);
                                }
                                $customerBuyers[$key]->supplier_customer_company_name = $customer->company_name;
                                $customerBuyers[$key]->invitation_status = $status;

                                $pushIt = true;

                                if (count($buyers) > 0) {
                                    foreach ($buyers as $keySecond => $value) {
                                        if ($value->id === $buyer->id) {
                                            $pushIt = false;
                                        }
                                    }
                                }

                                if ($pushIt) {
                                    array_push($buyers, $customerBuyers[$key]);
                                }
                            }
                        }
                    }

                    $perPage = !empty($request->per_page) ? $request->per_page : BuyerController::PER_PAGE;

                    return StandardResource::collection((new Collection($buyers))->paginate($perPage));

                }
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Something went wrong",
            ], 500);
        }
    }

    /**
     * Create buyer
     *
     * @bodyParam company_name string required
     * @bodyParam address string required
     * @bodyParam phone string required
     * @bodyParam custom_customers_id array required
     * @bodyParam emails array
     *
     * @response status=200 {
     *      "data": {
     *          "id": 4,
     *          "company_name": "Yoki shoes",
     *          "address": "Bacayo",
     *          "phone": "09265321825",
     *          "emails": "[\"mike@gmail.com\",\"suarezandrew@gmail\"]",
     *          "admin_user_id": null,
     *          "created_at": "2022-08-26T14:39:51.000000Z",
     *          "updated_at": "2022-08-26T15:07:03.000000Z",
     *          "connected_customer": 0,
     *          "connection_generated": null
     *      }
     *  }
     *
     * @response status=404{
     *      "company_name": [
     *          "The company name field is required."
     *      ],
     *      "address": [
     *          "The address field is required."
     *      ],
     *      "phone": [
     *          "The phone field is required."
     *      ],
     *      "custom_customers_id": [
     *          "The custom customers id field is required.",
     *          "This field must be a valid array and has an array length of at least 1."
     *      ]
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'company_name' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'custom_customers_id' => ['required', new IsArrayRule],
                'emails' => new IsArrayRule
            ]);

            if ($validator->fails()) return response()->json($validator->errors(), 404);

            $user = Auth::user();
            $customers = $user->customersApi->pluck('id');
            $get_authenticated_user = $user;

            $customers = ($get_authenticated_user->default_customer_id !== null) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];

            $input['custom_customers'] = $input['custom_customers_id'];
            unset($input['custom_customers_id']);

            if (isset($input['emails']))
                $input['emails'] = json_encode($input['emails']);

            $id = DB::table('buyer')
                ->insertGetId([
                    'company_name' => $input['company_name'],
                    'address' => $input['address'],
                    'phone' => $input['phone'],
                    'emails' => $input['emails'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

            $model = Buyer::find($id);

            $buildCustomerBuyers = [];

            //build customer buyers relationship
            foreach ($customers as $key => $customer) {
                array_push($buildCustomerBuyers, [
                    'buyer_id' => $model->id,
                    'customer_id' => $customer
                ]);
            }

            if (count($buildCustomerBuyers) > 0) {

                //attach customers
                DB::table('customer_buyer')
                    ->insert($buildCustomerBuyers);
            }


            if ($request->company_key) {
                $companyIdRecord = Customer::where('company_key', $request->company_key)->first();
                $buyerId = $id;
                $customerId = $customers[0];
                $selectedCustomerId = $request->company_key;

                $customer = Customer::find($customerId);

                $supplier = new Supplier();
                $supplier->company_name = $customer->company_name;
                $supplier->address = $customer->address;
                $supplier->phone = $customer->phone;
                $supplier->emails = json_encode($customer->emails);
                $supplier->admin_user_id = null;
                $supplier->connected_customer = $customerId;
                $supplier->connection_generated = 1;
                if ($supplier->save()) {
                    Buyer::where('id', $buyerId)
                        ->update(['connected_customer' => $companyIdRecord->id]);

                    CustomerSupplier::updateOrCreate(
                        [
                            'customer_id' => $companyIdRecord->id,
                            'supplier_id' => $supplier->id
                        ],
                        [
                            'customer_id' => $companyIdRecord->id,
                            'supplier_id' => $supplier->id
                        ]
                    );
                }
            }

            return response()->json([
                'data' => $model,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Something went wrong",
            ], 500);
        }
    }

    /**
     * Update Buyer
     *
     *
     * @bodyParam company_name string required
     * @bodyParam address string required
     * @bodyParam phone string required
     *
     * @response status=200 {
     *      "data": {
     *          "id": 4,
     *          "company_name": "Yoki shoes",
     *          "address": "Iligan City",
     *          "phone": "09265321825",
     *          "emails": "[\"mike@gmail.com\",\"suarezandrew@gmail\"]",
     *          "admin_user_id": null,
     *          "created_at": "2022-08-26T14:39:51.000000Z",
     *          "updated_at": "2022-08-26T14:39:51.000000Z",
     *          "connected_customer": 0,
     *          "connection_generated": null
     *      }
     * }
     *
     * @response status=404{
     *      "company_name": [
     *          "The company name field is required."
     *      ],
     *      "address": [
     *          "The address field is required."
     *      ],
     *      "phone": [
     *          "The phone field is required."
     *      ]
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'company_name' => 'required',
                'address' => 'required',
                'phone' => 'required',
            ]);

            if ($validator->fails()) return response()->json($validator->errors(), 404);

            $model = Buyer::find($id);

            $model->company_name = $input['company_name'];
            $model->address = $input['address'];
            $model->phone = $input['phone'];
            $model->updated_at = Carbon::now();

            if (isset($input['emails']))
                $model->emails = json_encode($input['emails']);

            $model->save();

            return response()->json([
                'data' => $model,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Something went wrong",
            ], 500);
        }
    }

    /**
     * Delete Buyer
     *
     * @response status=200 {
     *      "data": true
     *  }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $model = Buyer::find($id)->delete();
        return response()->json([
            'data' => $model,
        ]);
    }

    /**
     * Invite Buyer
     *
     * @bodyParam company_name string required
     * @bodyParam buyerId int required
     * @bodyParam defaultCustomerId int required
     * @bodyParam emailAddress array required
     *
     *
     * @response status=200 {
     *      "message": "Buyer successfully invited!"
     *  }
     *
     * @response status=404 {
     *      "buyerId": [
     *          "The buyer id field is required."
     *      ],
     *      "defaultCustomerId": [
     *          "The default customer id field is required."
     *      ],
     *      "emailAddress": [
     *          "The email address field is required.",
     *          "This field must be a valid array and has an array length of at least 1."
     *      ]
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function invite(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'buyerId' => 'required',
                'defaultCustomerId' => 'required',
                'emailAddress' => ['required', new IsArrayRule],
            ]);

            if ($validator->fails()) return response()->json($validator->errors(), 404);

            $emailAddress = $input['emailAddress'];
            $defaultCustomerId = $input['defaultCustomerId'];
            $buyerId = $input['buyerId'];

            $buyer = Buyer::select('company_name')->where('id', $buyerId)->first();
            $customer = Customer::select('company_name')->where('id', $defaultCustomerId)->first();

            if (isset($emailAddress))
                $emailAddressEncode = json_encode($emailAddress);

            $invitationBuyer = new InvitationBuyer();
            $invitationBuyer->default_customer_id = $defaultCustomerId;
            $invitationBuyer->buyer_id = $buyerId;
            $invitationBuyer->email = $emailAddressEncode;
            $invitationBuyer->status = 0;

            if ($invitationBuyer->save()) {
                foreach ($emailAddress as $email) {
                    event(new SendInvitationBuyerEvent($email, $buyer, $customer));
                }

                return response()->json(
                    [
                        'message' => 'Buyer successfully invited!'
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

    public function contactsDropdown(Request $request)
    {
       try {
        $user = Auth::user();
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();

        if (count($customers) > 0) {
            $customer = ( $get_authenticated_user->default_customer_id!==null ) ? $get_authenticated_user->default_customer_id : $customers[0];
        }

        $buyers = Buyer::whereHas('customers', function($q) use ($customer) {
            $q->where('customers.id', $customer);
        })->get();
        $buyers->each(function ($buyer)
        {
           $buyer->contact_type = 'buyer';
        });
       
        $vendors = Supplier::whereHas('customers', function($q) use ($customer) {
            $q->where('customers.id', $customer);
        })->get();
        $vendors->each(function ($vendor)
        {
           $vendor->contact_type = 'vendor';
        });

        $results = $vendors->merge($buyers);

        return response()->json([
            'results' => $results
        ]);
    } catch (Exception $exception) {
        return response()->json(
            [
                'message' => $exception->getMessage()
            ], 500
        );
    }
}

}
