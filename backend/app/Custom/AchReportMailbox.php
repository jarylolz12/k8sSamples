<?php

namespace App\Custom;

use BeyondCode\Mailbox\InboundEmail;

use App\Imports\StatementsImport;
use App\Imports\StatementMonthlyImport;
use App\Statements;
use App\StatementMonthly;
use Maatwebsite\Excel\Facades\Excel;

class AchReportMailbox
{
    public function __invoke(InboundEmail $email)
    {
        // Handle the incoming email


        \Log::info('received!');
        \Log::info('from: '. $email->from());
        \Log::info('to: '. $email->headerValue("To"));
        \Log::info('forwarded to:'. $email->headerValue("X-Forwarded-To"));
        \Log::info('subject: '.strtolower($email->subject()));
        \Log::info(env('ACH_REPORT_EMAIL'));

        // Handle the incoming
        if($email->headerValue("X-Forwarded-To") == env('ACH_REPORT_EMAIL') ||
                $email->headerValue("To") == env('ACH_REPORT_EMAIL')
            ) {

            if(str_contains(strtolower($email->subject()), "daily statement")) {

                \Log::info('process daily statement');
                $this->processDailyStatement($email);

            } else if(str_contains(strtolower($email->subject()), "monthly statement")) {

                \Log::info('process monthly statement');
                $this->processMonthlyStatement($email);

            } else {
                \Log::error('something went wrong!');
            }

        } else {
            \Log::error('not process!');
        }

    }

    function processDailyStatement(InboundEmail $email)
    {
        $string = $email->text();
        $prefix = 'Statement Date:';
        $index = strpos($string, $prefix) + strlen($prefix);
        $remain = substr($string, $index);
        $arr_result = explode(" ", trim(str_replace(array("\r\n","\r")," ",$remain)));
        $statement_date = trim($arr_result[0]) == '' ?  date("Y-m-d") : trim($arr_result[0]);

        foreach ($email->attachments() as $attachment) {
            $filename = $attachment->getFilename();

            $parts = explode(".",$filename);
            $extension = $parts[count($parts)-1];

            if($extension == 'xls') {

                $attachment->saveContent(storage_path($filename));

                $file = storage_path($filename);

                // upload report
                $data = Excel::toArray(new StatementsImport, storage_path($filename));

                unlink($file);

                $res = $data[0];

                $data = [];
                $rowNum = 0;
                $i = 0;

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
                        'date' => $statement_date,
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

                    $attachment->saveContent($soa_path.'/'.$filename);
                    \Log::info('---- file save ----');
                    \Log::info(storage_path($soa_path.'/'.$filename));

                    $res = [];
                    $created_at = now();
                    foreach($result['data'] as $k => $v)
                    {
                        Statements::updateOrCreate([
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


                    }

                } else {

                    unlink($file);

                    \Log::error('Invalid Report. '.$email->subject());
                }

            }

        }
    }

    function processMonthlyStatement(InboundEmail $email)
    {
        $string = $email->text();
        $prefix = 'Statement Date:';
        $index = strpos($string, $prefix) + strlen($prefix);
        $remain = substr($string, $index);
        $arr_result = explode(" ", trim(str_replace(array("\r\n","\r")," ",$remain)));
        $statement_date = trim($arr_result[0]);;

        foreach ($email->attachments() as $attachment) {
            $filename = $attachment->getFilename();

            $parts = explode(".",$filename);
            $extension = $parts[count($parts)-1];

            if($extension == 'xls') {

                $attachment->saveContent(storage_path($filename));

                $file = storage_path($filename);

                // upload report
                $data = Excel::toArray(new StatementMonthlyImport, $file);

                unlink($file);

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

                    $attachment->saveContent($soa_path.'/'.$filename);
                    \Log::info('---- file save ----');
                    \Log::info(storage_path($soa_path.'/'.$filename));

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

                } else {

                    unlink($file);

                    \Log::error('Invalid Report. '.$email->subject());
                }
            }

        }
    }
}
