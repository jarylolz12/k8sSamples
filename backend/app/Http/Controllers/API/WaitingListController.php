<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\WaitingList; 
/** 
 *  
 * @group Waiting List
 * 
 * APIs to manage the waiting list
 *  
 */ 
class WaitingListController extends Controller
{  
    /**
     * Register user for waiting list
     *  
     * @bodyParam company_name string required The company name. Example: ertyuio1
     * @bodyParam business_type string required The business type should be 'Shipper','Carrier','Cargo Terminal','Forwarder/Customs','Trucker/Broker','Warehouse','Other'. Example: Shipper
     * @bodyParam approximate_annual_shipments int required The approximate annual shipments. Example: 45
     * @bodyParam phone_number string required The phone number. Example: 123456789
     * @bodyParam email string required Email. Example: daxzdxbss@gmail.com
     * @bodyParam contact_person string required Contact Person. Example: cfsetewrdj
     *
     * @response 201 {
     *    "success": "Created",
     *    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNDc5OTg4MjFkMTZlZjUxOTRjOWZhYWFkMzFlZDY0NzljMzliZWMzMjg1NjU2YWViNzdkMmM3ZGMwOGNhNGFiOTZiOTVkZmEwNTE3OWE0ZTciLCJpYXQiOjE1OTAzOTEzNDMsIm5iZiI6MTU5MDM5MTM0MywiZXhwIjoxNjIxOTI3MzQzLCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.onsWJtrF9UT2XEbSsYQbVLvr-TZKGbqIoj4w5W-sEqbrcGep-mRuHJDY1-tY7E_-KxSV3yTrOAtyWFIv51atVFs5m6abF-QWNUpGYMlfEhjQbN6S5QOLXD7deiatSGH0dIXX6v7j7IdUeLgJ5t_6z7oD2s0bAuzfhrHCM9wf7Plyqv7p4-E6ROJ5Atv6IzFFA8dA6eEZWqF_SwOXJMEqyo3Gas7AzNoUSVear8d2sToLZFUv9lPXubp3_5kNN65Ri7bVQXJh0GqCBNN2ySWlO1ODaiIoNPSMOYpBLUaERRTh2AVzDLMvEcKQ5HQFLSqA1wFzABlOweF-7O1mvzdYLSmx8yCvlrIZxnBE2-c69IGmSJKowoczc2lNp96hNept6K-h94xQomC_bd8RajojBP928x-NLPhCH2bg98He0np_xBkm6M91z6M1ZnReZ7s45ViPOTovR6nW0QuqmH6LdF6JEeLc026DKLDX7Ap7fGjq-jFH-tbB_jp0wGGoJfTBLQllftTz9f86oqTXCf85_4fnMgTxB2qX_LBfJw4s6oa1Oex-EpBEprM4C0Awtlo9IkNNRBKLhxa7wPwMHFR_y9w7ZgEbq2pd-qDb4WMcDFfR5mTpCcYYrhufHSa0gnDDDAOanVSaFf58V5T_kKnb72_md7JFf87rhOoSjML_1Ks",
     *    "user": {
     *        "id": 15,
     *        "company_name": "ertyuio",
     *        "business_type": "demo@demo.com",
     *        "approximate_annual_shipments": "45",
     *        "phone_number": "123456789",
     *        "email": "daxzdxb@gmail.com",
     *        "contact_person": "cfsetewrdj",
     *        "created_at": "2022-01-18 14:01:39",
     *        "updated_at": "2022-01-18 14:01:39",
     *    }
     * }
     *
     * @response status=400 scenario="Validation error" {
     *    "errors": {
     *        "email": [
     *            "The email field is required.",
     *            "Email must be unique",
     *            "Please insert a valid email address."
     *        ],
     *        "business_type": [
     *            "The business type field is required.",
     *            "The selected business type is invalid.",
     *        ],
     *        "approximate_annual_shipments": [
     *            "The approximate annual shipments field is required."
     *        ],
     *        "phone_number": [
     *            "The phone number field is required."
     *        ],
     *        "company_name": [
     *            "The company name field is required."
     *        ],
     *        "contact_person": [
     *            "The contact person field is required."
     *        ]
     *    }
     * }
     * 
     */
    public function join(Request $request){

        $validator = Validator::make($request->all(), [
                          'company_name' => 'required',
                          'business_type' => 'required|in:Shipper,Carrier,Cargo Terminal,Forwarder/Customs,Trucker/Broker,Warehouse,Other',
                          'approximate_annual_shipments' => 'required|integer',
                          'phone_number' => 'required',
                          'email' => 'required|email|unique:waiting_lists',
                          'contact_person' => 'required' 
                      ]);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $waiting_list = WaitingList::create($validator->validated());
        return response()->json("Created",201);
    }
}
