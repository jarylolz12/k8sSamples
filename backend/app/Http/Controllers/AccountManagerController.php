<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountManagerController extends Controller
{


    public function getAllOld() {

        $response = ['status' => 'not ok', 'results' => []];

        $checkRoleId= \Spatie\Permission\Models\Role::where('name', 'Account Manager')->first()->id;
        $managers = DB::table('model_has_roles')
                                ->select(['users.name AS text','users.id AS id'])
                                ->join('users', 'users.id', '=', 'model_has_roles.model_id')
                                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                                ->where('roles.id', $checkRoleId)
                                ->where('model_has_roles.model_type', 'App\AccountManager')
                                ->distinct('model_has_roles.id')
                                ->get();


        $response['results'] = $managers;
        return response()->json($response);


    }
    public function getAll() {

        $response = ['status' => 'not ok', 'results' => []];

        $checkRoleId= \Spatie\Permission\Models\Role::where('name', 'Account Manager')->first()->id;
        $managers = DB::table('model_has_roles')
                                ->select(['users.name AS text','users.id AS id'])
                                ->join('users', 'users.id', '=', 'model_has_roles.model_id')
                                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                                ->where('roles.id', $checkRoleId)
                                ->where('model_has_roles.model_type', 'App\AccountManager')
                                ->distinct('model_has_roles.id')
                                ->get();

        $officeManagers = \DB::table('shifl_offices_managers')
                             ->select('manager_id')
                             ->get();

        $finalManagers = [];

        if (count($managers) > 0) {
            foreach ($managers as $key => $manager) {
                $remove = false;

                foreach($officeManagers as $keySecond => $officeManager) {
                    if ($manager->id==$officeManager->manager_id) {
                        $remove = true;
                    }
                }

                if (!$remove) {
                    array_push($finalManagers, $manager);
                }
            }
        }

        $response['results'] = $finalManagers;

        return response()->json($response);

    }


    public function whereIn(Request $request) {
        $response = ['status' => 'ok','results' => []];
        $managers = [];

        if ($request->has('ids')) {
            $ids = json_decode($request->input('ids'));
            $managers = \App\AccountManager::select('id', 'name')
                                 ->whereIn('id', $ids)
                                 ->get();
        }

        $response['results'] = $managers;
        return response()->json($response);
    }

    public function search(Request $request) {

        $response = ['status' => 'not ok', 'results' => ''];
        if ($request->has('query')) {
            
            $query = trim($request->input('query'));

            $checkRoleId = \Spatie\Permission\Models\Role::where('name', 'Account Manager')->first();

            $managers = DB::table('model_has_roles')
                                ->select(['users.name AS name','users.id AS id'])
                                ->join('users', 'users.id', '=', 'model_has_roles.model_id')
                                ->where('users.name','LIKE','%'.$query.'%')
                                ->where('model_has_roles.model_type', 'App\AccountManager')
                                ->get();

            $results = [];

            if (count($managers)>0) {
                $response['status'] = 'ok';
                foreach ($managers as $manager) {
                    array_push($results, [
                        'label' => $manager->name,
                        'value' => $manager->id
                    ]);
                }

                $response['results'] = $results;
            }
        }
        
        return response()->json($response);

    }

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

                if (!in_array('App\AccountManager', $checkCurrentRoles)) {
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
        $response = ['status' => 'ok'];
        if ($request->has('user_id') && $request->has('customer_id')) {
            if ($request->input('customer_id')!=null) {
                DB::table('customers_has_managers')
                 ->where('customer_id', $request->input('customer_id'))
                 ->where('user_id', $request->input('user_id'))
                 ->delete();
            }
        }
        return response()->json($response);
    }

    public function add(Request $request)
    {
        
        $response = ['status' => 'ok'];
        if ($request->has('user_id') && $request->has('customer_id')) {
            if ($request->input('customer_id')!=null) {
                $checkRecord= DB::table('customers_has_managers')
                 ->where('customer_id', $request->input('customer_id'))
                 ->where('user_id', $request->input('user_id'))
                 ->get();

                if (count($checkRecord)==0) {
                    if ($request->input('customer_id')!=null) {
                        DB::table('customers_has_managers')->insert([
                        'user_id' => $request->input('user_id'),
                        'customer_id' => $request->input('customer_id')
                        ]);
                    }
                }
            }
        }
        return response()->json($response);
    }

    public function findAccountManagerById(Request $request)
    {
        
        $response = ['status' => 'not ok','result' => null];
        $getRelatedAccountManager = DB::table('users')
                                       ->select('name', 'email', 'id')
                                       ->where('id', $request->input('id'))
                                       ->first();

        if (isset($getRelatedAccountManager->id)) {
            $response['result'] = $getRelatedAccountManager;
            $response['status'] = 'ok';
        }
        

        return response()->json($response);
    }

    //
    public function index(Request $request)
    {

        $response = ['results' =>[],'user_id' => $request->user()->id];
        
        $checkRoleId= \Spatie\Permission\Models\Role::where('name', 'Account Manager')->first()->id;
        
        $getAccountManagers = DB::table('model_has_roles')
                                ->select(['users.name AS text','users.id AS id'])
                                ->join('users', 'users.id', '=', 'model_has_roles.model_id')
                                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                                ->where('roles.id', $checkRoleId)
                                ->where('model_has_roles.model_type', 'App\AccountManager')
                                ->distinct('model_has_roles.id')
                                ->get();

        $getRelatedAccountManager = DB::table('customers')
                                       ->select('managers')
                                       ->where('id', $request->input('customer_id'))
                                       ->first();

        $getRelatedAccountManager = (isset($getRelatedAccountManager->managers)) ? intval($getRelatedAccountManager->managers) : null;
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

        $response['results'] = $getAccountManagers;
        //$response['customer_account_manager_ids'] = $getRelatedAccountManagers;
        $response['related_account_manager_id'] = $getRelatedAccountManager;
        return response()->json($response);
    }
}
