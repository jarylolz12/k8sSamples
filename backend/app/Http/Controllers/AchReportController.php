<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StatementsImport;
use App\Imports\StatementMonthlyImport;
use App\Statements;
use App\StatementMonthly;
use App\Exports\StatementsMonthly;
use BeyondCode\Mailbox\InboundEmail;

class AchReportController extends Controller
{
    public function __construct()
    {
        \DB::statement("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE'");
    }

    public function viewStatements()
    {
        return StatementMonthly::join('statements', 'daily_statement_no', '=','statements.statement_no')
                                ->join('shipments', 'shifl_ref','=','reference_no')
                                ->join('customers', 'customers.id','=','shipments.customer_id')
                                ->groupBy('customer_id')
                                ->select('customers.id','company_name')
                                ->get();
    }

    public function viewCustomerStatements($customer_id)
    {

        return StatementMonthly::join('statements', 'daily_statement_no', '=','statements.statement_no')
                                ->join('shipments', 'shifl_ref','=','reference_no')
                                ->join('customers', 'customers.id','=','shipments.customer_id')
                                ->where('shipments.customer_id', $customer_id)
                                ->groupBy('statement_monthly.statement_no')
                                ->select('statement_monthly.id','company_name','statement_monthly.statement_no','statement_monthly.daily_statement_no','statement_monthly.statement_date','statement_monthly.pres_date',\DB::raw('sum(statement_monthly.amount) as amount'))
                                ->get();
    }

    public function viewDailyStatements($statement_no)
    {

        return Statements::join('shipments', 'shifl_ref','=','reference_no')
                        ->join('customers', 'customers.id','=','shipments.customer_id')
                        ->where('statements.statement_no', $statement_no)
                        ->select('statements.id','company_name','statements.statement_no','statements.statement_date','.statements.entry_no','statements.reference_no','statements.amount')
                        ->groupBy('statements.id')
                        ->orderByDesc('statements.statement_date')
                        ->get();
    }

    public function postViewPreliminaryStatement(Request $request)
    {
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                $fileName = $request->file->getClientOriginalName();

                $request->file->move(public_path('storage'), $fileName);

                $file = public_path('storage').'/'.$fileName;


                // process csv file

                $data = Excel::toArray(new StatementsImport, $file);

                $res = $data[0];

                $data = [];
                $rowNum = 0;
                $i = 0;
                $stmt_date = date_format(now(), "Y-m-d");

                while(isset($res[$rowNum][0]) ? trim($res[$rowNum][0]) : "" !== 'Entry Number') {

                    if(isset($res[$rowNum][0]) && $res[$rowNum][0] == 'Entry Number') {
                        $rowNum += 2;
                        break;
                    }

                    if(str_contains($res[$rowNum][0], "Statement Number")) {
                        $soa = $res[$rowNum][1];
                    }

                    if(str_contains($res[$rowNum][0], "Importer of Record")) {
                        $importer_of_record = $res[$rowNum][1];
                    }

                    if(str_contains($res[$rowNum][0], "Process Dist/Port")) {
                        $process_port = $res[$rowNum][1];
                    }

                    $rowNum++;

                }

                while(isset($res[$rowNum][0]) ? $res[$rowNum][0] : '' !== 'Totals:') {
                    if(isset($res[$rowNum][0]) && $res[$rowNum][0] == 'Totals:') {
                        break;
                    }

                        $data[$i]['entry_number'] = $res[$rowNum][0];
                        $data[$i]['reference_number'] = $res[$rowNum][1];
                        $data[$i]['total_amount'] = str_replace(",","",$res[$rowNum][11]);
                        $i++;
                        $rowNum++;

                }

                if(isset($soa) && isset($importer_of_record) && isset($process_port)) {
                    $result = [
                        'date' => $stmt_date,
                        'soa' => $soa,
                        'importer_of_record' => $importer_of_record,
                        'process_port' => $process_port,
                        'total_amount_due' => str_replace(",","",$res[$rowNum][11]),
                        'data' => $data,
                    ];

                    $soa_path = public_path('storage'.'/DailyStatements/'.$soa);

                    if(!\File::exists($soa_path)) {
                        // path does not exist
                        \File::makeDirectory($soa_path, $mode = 0777, true, true);
                    }

                    // store into separate location
                    \File::copy($file, $soa_path.'/'.$fileName);

                    unlink($file);

                    $this->saveStatements($result);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Report successfully save.',
                    ]);

                } else {

                    unlink($file);

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid report.',
                    ]);
                }

            }
        }

    }

    function saveStatements($result)
    {
        $res = [];
        $created_at = now();
        foreach($result['data'] as $k => $v) {
            $statement = Statements::updateOrCreate([
                'entry_no' => $v['entry_number'],
                'reference_no' => $v['reference_number']
            ],
                [
                    'statement_date' => $result['date'],
                    'statement_no' => $result['soa'],
                    'importer_of_record' => $result['importer_of_record'],
                    'process_port' => $result['process_port'],
                    'amount' => str_replace(",","",$v['total_amount']),
                ]
            );

            // \Log::info('---------');
            // \Log::info('model clean: '.$statement->isClean());
            // \Log::info('model dirty: '.$statement->isDirty());
            // \Log::info('model was changed: '.$statement->wasChanged());
            // \Log::info('clean : '.$statement->isClean('updated_at'));
            // \Log::info('dirty : '.$statement->isDirty('updated_at'));
            // \Log::info('was changed : '.$statement->wasChanged('updated_at'));

        }
    }

    public function viewStatementNumber($statement_no)
    {
        return Statements::with('shipment.customer')->where('statement_no', $statement_no)->get();
    }

    public function viewMonthly()
    {
        return Statements::with('shipment.customer')->select('id', 'statement_no','statement_date','importer_of_record','entry_no','reference_no',\DB::raw('sum(amount) as total_amount'), \DB::raw("CONCAT_WS('-',MONTH(statement_date),YEAR(statement_date)) as monthyear"))
                        ->groupBy('monthyear')
                        ->get();
    }

    public function generateReport($customer_id,$statement_no)
    {
        return Excel::download(new StatementsMonthly($customer_id,$monthyear), 'statement-monthly.xlsx');
    }


    // Monthly Periodic

    public function postStatementMonthly(Request $request)
    {
        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                $fileName = $request->file->getClientOriginalName();
                // $fileName = date('YmdHis') . '.' . $request->file->extension();
                $request->file->move(public_path('storage'), $fileName);

                $file = public_path('storage').'/'.$fileName;


                // process csv file

                $data = Excel::toArray(new StatementMonthlyImport, $file);

                $res = $data[0];

                $data = [];
                $rowStart = 0;
                $rowNum = 0;
                $i = 0;

                while(isset($res[$rowNum][0]) ? $res[$rowNum][0] : "" !== 'Periodic Daily Statement') {

                    if(isset($res[$rowNum][0]) && $res[$rowNum][0] == 'Periodic Daily Statement') {
                        $rowNum++;
                        break;
                    }

                    if(str_contains($res[$rowNum][0], "Statement Number")) {
                        $soa = $res[$rowNum][1];
                    }

                    if(str_contains($res[$rowNum][0], "Statement For")) {
                        $customer_name = $res[$rowNum][1];
                    }

                    if(str_contains($res[$rowNum][0], "Process Dist/Port")) {
                        $district_port = $res[$rowNum][1];
                    }

                    $rowNum++;
                }

                while(isset($res[$rowNum][0]) ? $res[$rowNum][0] : "" !== 'Totals:') {

                    if(isset($res[$rowNum][0]) && $res[$rowNum][0] == 'Totals:') {
                        break;
                    }

                    $stmt_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($res[$rowNum][1]);
                    $pres_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($res[$rowNum][2]);

                    $data[$i]['pds'] = $res[$rowNum][0];
                    $data[$i]['stmt_date'] = date_format($stmt_date, "Y-m-d");
                    $data[$i]['pres_date'] = date_format($pres_date, "Y-m-d");
                    $data[$i]['total_amount'] = str_replace(",","",$res[$rowNum][8]);

                    $i++;
                    $rowNum++;
                }


                if(isset($soa) && isset($customer_name) && isset($district_port)) {
                    $result = [
                        'soa' => $soa,
                        'customer_name' => $customer_name,
                        'district_port' => $district_port,
                        'total_amount' => str_replace(",","",$res[$rowStart][8]),
                        'data' => $data,
                    ];

                    $soa_path = public_path('storage'.'/MonthlyStatements/'.$soa);

                    if(!\File::exists($soa_path)) {
                        // path does not exist
                        \File::makeDirectory($soa_path, $mode = 0777, true, true);
                    }

                    // store into separate location
                    \File::copy($file, $soa_path.'/'.$fileName);

                    unlink($file);

                    $this->saveStatementMonthly($result);
                    // return $result;

                } else {

                    unlink($file);

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid report.',
                    ]);
                }
            }
        }
    }

    public function saveStatementMonthly($result)
    {
        $res = [];
        $created_at = now();

        foreach($result['data'] as $k => $v)
        {
            StatementMonthly::updateOrCreate([
                'daily_statement_no' => $v['pds'],
            ],
                [
                    'statement_no' => $result['soa'],
                    'customer_name' => $result['customer_name'],
                    'process_port' => $result['district_port'],
                    'statement_date' => $v['stmt_date'],
                    'pres_date' => $v['pres_date'],
                    'daily_statement_no' => $v['pds'],
                    'amount' => str_replace(",","",$v['total_amount']),
                ]
            );

        }
    }

    public function viewMonthlyStatements($customer_id)
    {
        return StatementMonthly::join('statements', 'daily_statement_no', '=','statements.statement_no')
                        ->join('shipments', 'shifl_ref','=','reference_no')
                        ->join('customers', 'customers.id','=','shipments.customer_id')
                        ->where('shipments.customer_id', $customer_id)
                        ->select('statement_monthly.id','company_name','statement_monthly.statement_no','statement_monthly.statement_date','.statement_monthly.daily_statement_no',\DB::raw('concat("$ ",FORMAT(sum(statements.amount),2)) as amount'))
                        ->groupBy('statement_monthly.id')
                        ->orderByDesc('statement_monthly.statement_date')
                        ->get();
    }

    public function viewMonthlyStatementsByStatementNo($statement_no)
    {
        return StatementMonthly::join('statements', 'daily_statement_no', '=','statements.statement_no')
                        ->join('shipments', 'shifl_ref','=','reference_no')
                        ->join('customers', 'customers.id','=','shipments.customer_id')
                        ->where('statement_monthly.statement_no', $statement_no)
                        ->select('statement_monthly.id','company_name','statement_monthly.statement_no','statement_monthly.statement_date','.statement_monthly.daily_statement_no',\DB::raw('concat("$ ",FORMAT(statement_monthly.amount,2)) as amount'))
                        ->groupBy('statement_monthly.id')
                        ->orderByDesc('statement_monthly.statement_date')
                        ->get();
    }

    public function viewStatementsDaily()
    {
        return Statements::join('shipments', 'shifl_ref','=','reference_no')
                                ->join('customers', 'customers.id','=','shipments.customer_id')
                                ->groupBy('customer_id')
                                ->select('customers.id','company_name')
                                ->get();
    }

    public function viewStatementsDailyByCustomer($customer_id)
    {
        return Statements::join('shipments', 'shifl_ref','=','reference_no')
                                ->join('customers', 'customers.id','=','shipments.customer_id')
                                ->where('customers.id',$customer_id)
                                ->groupBy('statements.statement_no',)
                                ->select('statements.statement_date','company_name','statements.statement_no','statements.entry_no','statements.reference_no',\DB::raw('sum(statements.amount) as amount'))
                                ->orderByDesc('statements.statement_date')
                                ->get();
    }

}
