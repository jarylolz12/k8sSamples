<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Auth;
use App\Customer;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * @authenticated
 *
 * @group Customer
 *
 * APIs to manage the customer resource
 *
 */
class CustomerController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     *
     * @queryParam customers int Size per page. Default to 5. Example: 5
     * @queryParam page int Page to view. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\ScribeResources\UserResource
     * @apiResourceModel App\User paginate=5 with=customersApi
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return Response
     */
    public function index()
    {
        $customers = [];
        //Auth::loginUsingId(3);
        $customers = array_merge($customers, Auth::user()->customersApi->toArray());

        return StandardResource::collection((new Collection($customers))->paginate(5));

        // return StandardResource::collection(new Collection(Auth::user()->customersApi->toArray()))->paginate(5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @urlParam id int required Customer ID
     * @apiResource App\Http\Resources\Customer
     * @apiResourceModel App\Customer
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
        try {
            $customers = Auth::user()->customersApi;

            foreach ($customers as $customer) {
                if ($customer->id == $id) {
                    return new StandardResource($customer);
                }
            }
            return abort(404);
        } catch (\Exception $exception) {
            return response([
                'message' => 'No results found.'
            ], 404);
        }
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
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function updateProfile($id, Request $req)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'company_name' => 'string:required',
                'phone' => 'string|required',
                'emails' => 'array|required',
                'address' => 'string|required',
                'country' => 'string|required',
                'state' => 'string|required',
                'city' => 'string|required',
                'zipcode' => 'numeric|required'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first(),
                    'status' => 400],
                    400);
            }
            $customer = Customer::find($id);
            if(!$customer)
            {
                return response()->json(['error'=> 'ID Not Found'],404);
            }


            if($req->has('company_name'))
            {
                $customer->company_name=$req->company_name;
            }
            if($req->has('phone'))
            {
                $customer->phone = $req->phone;
            }
            if($req->has('emails'))
            {
                $customer->emails = $req->emails;
            }
            if($req->has('address'))
            {
                $customer->address = $req->address;
            }
            if($req->has('country'))
            {
                $customer->country = $req->country;
            }
            if($req->has('state'))
            {
                $customer->state = $req->state;
            }
            if($req->has('city'))
            {
                $customer->city = $req->city;
            }
            if($req->has('zipcode'))
            {
                $customer->zipcode = $req->zipcode;
            }

            $saved = $customer->save();

            if($saved) {
                return response()->json(["message"=>"Company information has been updated.",'data' => $customer]);
            }
            else {
                return response()->json(["message"=>"Failed to update a Company information"]);
            }

        } catch ( \Exception $e ) {
            return response()->json(['error'=>$e->getMessage(),
                'message' => 'Internal Server Error, Please try again later.',
                'status' => 500
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Check Company Key
     *
     * Get By Company Key
     *
     * @urlParam company_key string required. Example: YOK60520
     *
     *
     * @response status=200 {
     *      "results": "success"
     *  }
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param $companyKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCompanyKey($companyKey)
    {
        try {
            $get_authenticated_user = Auth::user();
            $customersIds = $get_authenticated_user->customersApi->pluck('id');

            $default_customer_id = ($get_authenticated_user->default_customer_id !== null) ? [$get_authenticated_user->default_customer_id] : [$customersIds[0]];

            $results = 'This key does not exist..!';
            $customers = Customer::where('company_key', $companyKey)->where('id', '!=', $default_customer_id[0])->exists();
            if ($customers) {
                $results = 'success';
            }
            return response()->json(["results" => $results]);
        } catch (\Exception $e) {
            Log::debug("Unable to get customer by company key : " . $e->getMessage());
            return response()->json(["results" => "No results found"]);
        }

    }

    /**
     * Get Company Keys
     *
     *
     * @response status=200 {
     *      "results": [
     *          "YOK60520",
     *          "STAQCF5S",
     *          "GRAHNO1S",
     *          ...,
     *      ]
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanyKeys()
    {
        try {
            $customers = collect(Customer::all());
            $company_keys = $customers->pluck('company_key');
            return response()->json(["results" => $company_keys->all()]);
        } catch (\Exception $e) {
            Log::debug("Unable to get customer by company key : "
                . $e->getMessage());
            return response()->json(["results" => "No results found"]);
        }

    }

    /**
     * Get By Company Key
     *
     * @urlParam company_key string required. Example: YOK60520
     *
     * @response status=200 {
     *      "results": {
     *          "id": 1,
     *          "company_name": "Yoki shoes",
     *          "address": null,
     *          "phone": null,
     *          "admin_user_id": null,
     *          "created_at": "2020-01-07T22:54:10.000000Z",
     *          "updated_at": "2022-08-26T14:10:54.000000Z",
     *          "managers": "119",
     *          "sale_persons": "0",
     *          "emails": [
     *              {
     *                  "email": "elie@yokigroup.com"
     *              },
     *              {
     *                  "email": "devi@yokigroup.com"
     *              },
     *              {
     *                  "email": "samantha@cachb.com"
     *              }
     *          ],
     *          "requirements": "NO Trucking NO Customs               [!!!!!!!!!!!!!!!!!!!!!!]       \n---------------------------\nAN: Need telex, notify:  Telex Pending (Sundy&Lydia) 1651359290@qq.com, lydia001205@vip.163.com\nDO: don't even send it out - Mark DO confirmed. \nRandom: @Ryan, Chassis Usage?  (Ryan) ryan.nguyen.lax@unique-logistics.com,\n                 If trucking:  WAREHOUSE: #'s to confirm before; Javier Salazar Tel: (714) (323) 742-3870 Dulce Olivarez Tel:(626) 608-5490 Ext 2413",
     *          "credit_term_freight": 0,
     *          "credit_term_duty": 0,
     *          "ein": null,
     *          "booking_email_recipients": "[]",
     *          "loi": "customers/81179f359120099142e62e20adbd0f48.pdf",
     *          "offices_managers": "[{\"office_id\": 1, \"manager_id\": 119}, {\"office_id\": 2, \"manager_id\": 41}, {\"office_id\": 3, \"manager_id\": 120}, {\"office_id\": 4, \"manager_id\": 150}, {\"office_id\": 5, \"manager_id\": 40}]",
     *          "qbo_customer": "{\"customer\":{\"Id\":\"25\",\"DisplayName\":\"Yoki Shoes\",\"FullyQualifiedName\":\"Yoki Shoes\",\"Balance\":\"64980.00\",\"PrimaryEmailAddr\":{\"Id\":null,\"Address\":\"devi@yokigroup.com\",\"Default\":null,\"Tag\":null},\"GivenName\":null,\"FamilyName\":null,\"BillAddr\":null},\"company\":\"shifl Inc\",\"realm_id\":123146157027444}",
     *          "poa": null,
     *          "default_duty_layout": 0,
     *          "created_by": null,
     *          "last_updated_by": null,
     *          "last_updated": null,
     *          "handling_freight": null,
     *          "handling_trucking": null,
     *          "handling_customs": null,
     *          "default_requirements_section": null,
     *          "confirmed_default_requirements": 0,
     *          "default_requirements": null,
     *          "customer_default_requirements": null,
     *          "company_key": "YOK60520"
     *      }
     *  }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCompanyKey($companyKey)
    {
        try {
            $customer = Customer::where('company_key', $companyKey)->first();
            return response()->json(["results" => $customer]);
        } catch (\Exception $e) {
            Log::debug("Unable to get customer by company key : "
                . $e->getMessage());
            return response()->json(["results" => "No results found"]);
        }
    }
}
