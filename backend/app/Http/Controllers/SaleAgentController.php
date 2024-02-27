<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleAgentController extends Controller
{


    
    public function check(Request $request)
    {
        
        $response = ['status' => 'not ok'];

        if ($request->user()->has('roles')) {
            $checkCurrentRoles = \DB::table('model_has_roles')
                                    ->select('model_type')
                                    ->where('model_id', $request->user()->id)
                                    ->get();
            
            if (count($checkCurrentRoles)>0) {
                $checkCurrentRoles = $checkCurrentRoles->pluck('model_type')
                                                       ->toArray();

                if (!in_array('App\SaleAgent', $checkCurrentRoles)) {
                    if (in_array('App\Admin', $checkCurrentRoles)) {
                        $response['status'] = 'ok';
                    }
                }
            }
        }

        return response()->json($response);
    }

    public function remove(Request $request)
    {
        /*
        $response = ['status' => 'ok'];
        if($request->has('user_id') && $request->has('customer_id')) {

            if($request->input('customer_id')!=null) {
                DB::table('customers_has_managers')
                 ->where('customer_id',$request->input('customer_id'))
                 ->where('user_id',$request->input('user_id'))
                 ->delete();
            }

        }
        return response()->json($response);*/
    }

    public function add(Request $request)
    {
        /*
        $response = ['status' => 'ok'];
        if($request->has('user_id') && $request->has('customer_id')) {

            if($request->input('customer_id')!=null) {
                $checkRecord= DB::table('customers_has_managers')
                 ->where('customer_id', $request->input('customer_id'))
                 ->where('user_id', $request->input('user_id'))
                 ->get();

                if(count($checkRecord)==0) {
                    if($request->input('customer_id')!=null) {
                        DB::table('customers_has_managers')->insert([
                        'user_id' => $request->input('user_id'),
                        'customer_id' => $request->input('customer_id')
                        ]);
                    }
                }
            }
        }
        return response()->json($response);*/
    }

    public function findSaleAgentById(Request $request)
    {
        
        $response = ['status' => 'not ok','result' => null];
        $getRelatedSaleAgent = DB::table('users')
                                       ->select('name', 'email', 'id')
                                       ->where('id', $request->input('id'))
                                       ->first();

        if (isset($getRelatedSaleAgent->id)) {
            $response['result'] = $getRelatedSaleAgent;
            $response['status'] = 'ok';
        }

        return response()->json($response);
    }

    //
    public function index(Request $request)
    {

        $response = ['results' =>[],'user_id' => $request->user()->id];
        
        $checkRoleId= \Spatie\Permission\Models\Role::where('name', 'Sales Representative')->first()->id;
        $getSaleAgents = DB::table('model_has_roles')
                                ->select(['users.name AS text','users.id AS id'])
                                ->join('users', 'users.id', '=', 'model_has_roles.model_id')
                                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                                ->where('roles.id', $checkRoleId)
                                ->where('model_has_roles.model_type', 'App\SaleAgent')
                                ->distinct('model_has_roles.id')
                                ->get();

        $getRelatedSaleAgent = DB::table('customers')
                                       ->select('sale_persons')
                                       ->where('id', $request->input('customer_id'))
                                       ->first();

        $getRelatedSaleAgent = (isset($getRelatedSaleAgent->sale_persons)) ? intval($getRelatedSaleAgent->sale_persons) : null;
        /*
        $getRelatedAccountManagers = [];

        if(isset($getRelatedAccountManagerIds->managers)) {

            $getRelatedAccountManagersArray = explode(',', $getRelatedAccountManagerIds->managers);



            if(count($getRelatedAccountManagersArray)>0) {
                foreach ($getRelatedAccountManagersArray as $key => $value) {
                    array_push($getRelatedAccountManagers, ['id' => $value]);
                }

            }
        }*/

        /*

        $getRelatedAccountManagers = DB::table('customers_has_managers')
                                    ->select(['users.id AS id'])
                                    ->join('users', 'users.id', '=', 'customers_has_managers.user_id')
                                    ->join('customers','customers.id', '=', 'customers_has_managers.customer_id')
                                    ->where('customers.id',$request->input('customer_id'))
                                    ->get()
                                    ->pluck('id')
                                    ->toArray();*/

        $response['results'] = $getSaleAgents;
        //$response['customer_account_manager_ids'] = $getRelatedAccountManagers;
        $response['related_sale_agent_id'] = $getRelatedSaleAgent;
        return response()->json($response);
    }
}
