<?php

namespace App\Http\Controllers\API\Custom;

use App\Customer;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Standard as StandardResource;
/**
 *
 * @group Custom Customer
 *
 * APIs to manage the custom customer resources
 *
 */

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Customer
     * @apiResourceModel App\Customer
     * @apiResourceAdditional result=success message=""
     *
     * @response status=400 {
     *    "message": "Unauthenticated",
     * }
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(["results" => Customer::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @urlParam id int required Customer ID
     *
     * @apiResource App\Http\Resources\Customer
     * @apiResourceModel App\Customer with=customerAdminsApi
     * @apiResourceAdditional result=success message=""
     *
     * @response status=400 {
     *    "message": "Unauthenticated",
     * }
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::find($id);
        try{
            $customerAdminsApi = $customer->customerAdminsApi->toArray();
        }catch (\Exception $exception){
            return response([
                'message'=>"No results found."
            ], 404);
        }

        $customerAdmins = [];

        if(count($customerAdminsApi) > 0){
            $customerAdmins = collect($customerAdminsApi)->map(function($customerAdmin, $value){
                return $customerAdmin["id"];
            });
        }

        $customer->customer_admins = $customerAdmins;

        return response()->json(["results" => $customer]);
    }


    /**
     * Display customer's supplier
     *
     * @authenticated
     *
     * @urlParam id int required Customer Supplier ID.
     *
     * @apiResource App\Http\Resources\Customer
     * @apiResourceModel App\Customer with=suppliers
     * @apiResourceAdditional result=success message=""
     *
     * @response status=400 {
     *    "message": "Unauthenticated",
     * }
     *
     */
    public function getCustomerSuppliers(Customer $id)
    {
        try {
            return response()->json(["results" => $id->suppliers]);
        }catch (\Exception $exception){
            return response([
                'message'=>'No query results'
            ], 404);
        }
    }

    /**
     * Display customer's buyers
     *
     * @authenticated
     *
     * @urlParam id int required Customer Supplier ID.
     *
     * @apiResource App\Http\Resources\Customer
     * @apiResourceModel App\Customer with=suppliers
     * @apiResourceAdditional result=success message=""
     *
     * @response status=400 {
     *    "message": "Unauthenticated",
     * }
     *
     */
    public function getCustomerBuyers(Customer $id)
    {
        try {
            return response()->json(["results" => $id->buyer]);
        }catch (\Exception $exception){
            return response([
                'message'=>'No query results'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getUserName($id)
    {
        try {
            $user = User::where('id', $id)->first();
            return response()->json(["results" => $user]);
        } catch (\Exception $exception) {
            return response([
                'message' => 'No query results'
            ], 404);
        }
    }
}






