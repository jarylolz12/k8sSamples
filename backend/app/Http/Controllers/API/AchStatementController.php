<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\StatementMonthly;
use App\Statements;
use App\CustomerAdmin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Rules\IsArrayRule;

use App\Http\Resources\Standard as StandardResource;
/**
 *  
 * @group Statement
 * 
 * APIs to manage the Statement resource
 * 
 */
class AchStatementController extends Controller
{
    public function __construct()
    {
        \DB::statement("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE'");
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

    private function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    /**
     * Display the list resource.
     * 
     * @authenticated   
     * 
     * @apiResource App\Http\Resources\ScribeResources\StatementsResource
     * @apiResourceModel App\Statements
     *  
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // $user_id = auth()->user()->id;

        // $customer_id = \DB::table('customer_admins')->where('user_id', $user_id)->first()->customer_id;
        $customer_id = auth()->user()->default_customer_id;
        //$customer_id = Auth::loginUsingId(2);
        if (is_null($customer_id)) return $this->sendError("Statement not found.");

        $model = Statements::leftJoin('statement_monthly', 'daily_statement_no', '=','statements.statement_no')
                ->leftJoin('shipments', 'shifl_ref','=','reference_no')
                ->leftJoin('customers', 'customers.id','=','shipments.customer_id')
                ->where('shipments.customer_id', $customer_id)
                ->select('statement_monthly.statement_no',\DB::raw('concat(substring(monthname(statement_monthly.statement_date),1,3)," ",year(statement_monthly.statement_date)) as month_year'),'statement_monthly.statement_no','statement_monthly.statement_date','statement_monthly.pres_date as process_date','statement_monthly.daily_statement_no',\DB::raw('concat("$ ",FORMAT(sum(statements.amount),2)) as amount'),
                    \DB::raw('group_concat(distinct(statements.statement_no)) as statement_nos')
                    )
                ->groupBy('statement_monthly.statement_no')
                ->orderBy(\DB::raw('ifnull(statement_monthly.statement_date, now())'),"DESC")
                ->get();

        if (is_null($model)) return $this->sendError("Statement not found.");

        foreach($model as $k => $m) {
            if($model[$k]['daily_statement_no']) {
                $dailyStatement = Statements::where('statement_no',$model[$k]['daily_statement_no'])
                                            ->select('statement_date','statement_no',\DB::raw('concat("$ ",FORMAT(sum(amount),2)) as amount'))
                                            ->groupBy('statement_no')
                                            ->get();

                    foreach($dailyStatement as $i => $stmt) {
                        $stmt['details'] = $this->getDailyStatementDetails($model[$k]['daily_statement_no']);
                    }

                $m['daily_statements'] = $dailyStatement;
            } else {
                $m['daily_statements'] = $this->getDailyStatementDetails($model[$k]['statement_nos']);
            }
        }

        return $this->sendResponse(new StandardResource(StandardResource::make($model)), "Statement fetched successfully.");
    }

    public function getDailyStatementDetails($statement_no)
    {
        return Statements::with('shipment')->whereIn('statement_no', explode(",",$statement_no))
                        ->select('entry_no','reference_no',\DB::raw('concat("$ ",FORMAT(amount,2)) as amount'))
                        ->get();
    }

}
