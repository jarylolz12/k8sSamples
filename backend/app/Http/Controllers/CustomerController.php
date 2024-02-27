<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use GuzzleHttp\Client;
class CustomerController extends Controller
{
    

    public function getCustomerByShipment(Request $request) {

        $response = ['status' => 'not ok', 'customer' => null];
        $customer = '';

        if ( $request->has('shipment_id') ) {

            $shipment = \App\Shipment::find($request->shipment_id);

            if ( isset($shipment->id) ) {
                $customer = Customer::find($shipment->customer->id);
                $sales_representative = null;
                /*
                if ( isset($customer->id) && ($customer->sale_persons!=null && $customer->sale_persons!='')){
                    $sales_representative = User::find(intval($customer->sale_persons));
                    $customer->sales_representative = $sales_representative;
                }*/
                $customer->sales_representative = $customer->salesRepresentative;


                $response['status'] = 'ok';
                $response['customer'] = $customer;    
            }
        }

        return response()->json($response);
    }



    public function attachCustomer( Request $request )
    {
        $response = ['status' => 'not ok'];
            
        if ($request->has('customer_id') && $request->has('customer_admin_id')) {
            $customer_admin = User::find($request->input('customer_admin_id'));

            if ( isset($customer_admin->id)) {

                $admins = \DB::table('customer_admins')
                ->where('user_id', $customer_admin->id)
                ->where('customer_id', $request->input('customer_id'))
                ->get();

                if ( count($admins) === 0) {
                    \DB::table('customer_admins')
                    ->insert([
                        'user_id' => $customer_admin->id,
                        'customer_id' => $request->input('customer_id')
                    ]);

                    $response['status'] = 'ok';
                }
            }   
        }

        return response()->json($response);
    }

    public function detachCustomer( Request $request )
    {
    
        $response = ['status' => 'not ok'];
            
        if ($request->has('customer_id') && $request->has('customer_admin_id')) {

            $customer_admin = User::find($request->input('customer_admin_id'));

            if ( isset($customer_admin->id)) {
                \DB::table('customer_admins')
                ->where('user_id', $customer_admin->id)
                ->where('customer_id', $request->input('customer_id'))
                ->delete();
                
                if ( $customer_admin->default_customer_id === $request->input('customer_id') && $customer_admin->customersApi!==null && count($customer_admin->customersApi) > 0) {
                   // $customer_admin->default_customer_id = $customer_admin->customersApi[0]->id;
                   // $customer_admin->save();
                    return $this->sendNotification($customer_admin);
                }
            }
            
        }

        return response()->json($response);

    }


    private function sendNotification( $user ) {
        
        if (isset($user->notification_token)) {

            $client = new Client([
                'base_uri' => 'https://shipol-test.herokuapp.com',
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept'     => 'application/json'
                ]
            ]);

            $client->post('/notify.php', [
                'form_params' => [
                    'secret_key' => 'thisSecr3t15123',
                    'token' => $user->notification_token,
                ]
            ]);

            return response()->json([
                'status' =>  'ok'
            ]);

        } else {
            return response()->json([
                'status' => 'not ok'
            ]);
        }
        
    }

    public function searchForCustomerAdmin(Request $request)
    {
        $response = ['status' => 'ok', 'results' => []];

        if ( $request->has('qry') && $request->has('customer_admin_id')) {

            $get_user = User::find($request->input('customer_admin_id'));
            $customerIds = $get_user->customersApi;
            $customers = Customer::with('salesRepresentative')
                        ->where(function ($query) use ($request){
                            if ( $request->has('qry') && $request->input('qry')!=='' && $request->input('qry')!==null ) {
                                $query->where(function ($qq) use ($request) {
                                    $querySplits = explode(' ',$request->input('qry'));
                                    if (count($querySplits) > 0) {
                                        foreach($querySplits as $querySplit) {
                                            $qq->orWhere('company_name', 'LIKE', '%'.strtolower($querySplit).'%');
                                            $qq->orWhere('address', 'LIKE', '%'.strtolower($querySplit).'%');
                                            $qq->orWhere('phone', 'LIKE', '%'.strtolower($querySplit).'%');
                                        }
                                    }
                                });
                            }
                        });

            if (count($customerIds) > 0) {
                $customerIds = $customerIds->pluck('id');
                $customers = $customers->whereNotIn('id',$customerIds);  
            }
            $customers = $customers->paginate(100);
            $response['results'] = $customers;
        }
        return response()->json($response);

    }

    public function getCustomersByCustomerAdmin(Request $request)
    {
        $response = ['status' => 'ok', 'results' => []];
        if ( $request->has('customer_admin_id') ) {
            $customers = Customer::with('salesRepresentative')
                    ->whereHas('customerAdmins', function($query) use ($request) {
                        $customer_admin_id = abs($request->input('customer_admin_id'));
                        $query->where('user_id', $customer_admin_id);
                    })
                    ->where(function ($query) use ($request){
                        if ( $request->has('qry') && $request->input('qry')!=='' && $request->input('qry')!==null ) {
                            $query->where(function ($qq) use ($request) {
                                $querySplits = explode(' ',$request->input('qry'));
                                if (count($querySplits) > 0) {
                                    foreach($querySplits as $querySplit) {
                                        $qq->orWhere('company_name', 'LIKE', '%'.strtolower($querySplit).'%');
                                        $qq->orWhere('address', 'LIKE', '%'.strtolower($querySplit).'%');
                                        $qq->orWhere('phone', 'LIKE', '%'.strtolower($querySplit).'%');
                                    }
                                }
                            });
                        }
                    })
                    ->paginate(10);
            $response['results'] = $customers;
        }
        return response()->json($response);
    }
    //
    public function getCustomers(Request $request)
    {
        $response = ['status' => 'ok', 'results' => []];
        $customers = Customer::all();
        if (count($customers) > 0) {
            $response['results'] = $customers;
        }
        return response()->json($response);
    }

    public function getCustomer(Customer $customer_id)
    {
        $suppliers = $customer_id->suppliers;
        $customer_id["suppliers"] = $suppliers;

        return response()->json($customer_id);
    }
}
