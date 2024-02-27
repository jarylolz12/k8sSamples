<?php

namespace App\Http\Controllers\API;

use App\EmailReportSchedule;
use App\User;
use App\CustomerAdmin;
use App\Custom\SendExcelReportMail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
/**
 *
 * @authenticated
 *
 * @group Email Report Schedule
 *
 * APIs to manage the email report schedule resource
 *
*/
class EmailReportScheduleController extends Controller
{
    /**
     *
     * Display the specified resource.
     *
     * 
     * @urlParam email_report_schedule_id int required email report schedule ID
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\EmailReportSchedule
     * @apiResourceAdditional success=true message="Data fetched successfully"
     *
     * @response status=404 scenario="Something went wrong" {
     *   "message": "Something went wrong"
     * }
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     */
    public function getEmailReportSchedule($id)
    {

       try {
            $statusCode = 200;
            $users = (new EmailReportSchedule)->getEmailReportWithUsersByUserId($id)
                ->first();
           
            if(!$users) {
                return response()->json(['message' => 'No Data Found', 'success' => false, 'data' => []], $statusCode);
            }
            
            // $recipients = (is_array(json_decode($users->report_recipients))) ? json_decode($users->report_recipients) : [];
            $recipients = json_decode(json_encode($users->report_recipients)) ?? [];
          
            $arRecipients = [];
            foreach($recipients as $key => $value){
                array_push($arRecipients, $value->report_recipients);
            }

            $users->report_recipients = $arRecipients;

            $response = [
                'success' => true,
                'data' => isset($users) ? $users : [],
                'message' => isset($users) ? 'Data retrieved successfully' : 'No Data Found',
            ];

       } catch (\Exception $e) {
          Log::error($e->getMessage());

          $response = [
              'success' => false,
              'message' => 'Something went wrong',
          ];

       }
       return response()->json($response, $statusCode);
    }

    /**
     *
     * Remove the specified resource from storage.
     *
     *
     * @urlParam email_report_schedule_id int required email report schedule ID
     *
     * @response 200 {
     *    "status": "ok",
     *    "message": "Email Report Schedule Deleted Successfully"
     * }
     *
     * @response 200 {
     *   "status": "failed",
     *   "message": "Email Report Schedule doesn't exists"
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *   "message": "Unauthenticated."
     * }
     *
     */
    public function deleteEmailReportSchedule($id)
    {
        // $emailSchedule = EmailReportSchedule::where('customer_admin_id', '=', $id)->delete();
        $emailSchedule = EmailReportSchedule::find($id)->delete();
        if($emailSchedule){
            return response()->json([
                'status' => 'ok',
                'message' => "Email Report Schedule Deleted Successfully"
            ], 200);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => "Email Report Schedule doesn't exists"
            ], 200);
        }
    }


     /**
     *
     * Show the form for creating a new email report schedule.
     *
     * @bodyParam customer_admin_id int required customer admin ID. Example: 1
     * @bodyParam frequency string required frequency. Example: WEEKLYON
     * @bodyParam day tinyint required day. Example: 1
     * @bodyParam time time required time. Example: 08:00:00
     * @bodyParam active tinyint required active. Example: 1
     * @bodyParam month_day date required month_day.
     * @bodyParam report_type string required report_type.
     *
     * @response 200 {
     *      "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNDc5OTg4MjFkMTZlZjUxOTRjOWZhYWFkMzFlZDY0NzljMzliZWMzMjg1NjU2YWViNzdkMmM3ZGMwOGNhNGFiOTZiOTVkZmEwNTE3OWE0ZTciLCJpYXQiOjE1OTAzOTEzNDMsIm5iZiI6MTU5MDM5MTM0MywiZXhwIjoxNjIxOTI3MzQzLCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.onsWJtrF9UT2XEbSsYQbVLvr-TZKGbqIoj4w5W-sEqbrcGep-mRuHJDY1-tY7E_-KxSV3yTrOAtyWFIv51atVFs5m6abF-QWNUpGYMlfEhjQbN6S5QOLXD7deiatSGH0dIXX6v7j7IdUeLgJ5t_6z7oD2s0bAuzfhrHCM9wf7Plyqv7p4-E6ROJ5Atv6IzFFA8dA6eEZWqF_SwOXJMEqyo3Gas7AzNoUSVear8d2sToLZFUv9lPXubp3_5kNN65Ri7bVQXJh0GqCBNN2ySWlO1ODaiIoNPSMOYpBLUaERRTh2AVzDLMvEcKQ5HQFLSqA1wFzABlOweF-7O1mvzdYLSmx8yCvlrIZxnBE2-c69IGmSJKowoczc2lNp96hNept6K-h94xQomC_bd8RajojBP928x-NLPhCH2bg98He0np_xBkm6M91z6M1ZnReZ7s45ViPOTovR6nW0QuqmH6LdF6JEeLc026DKLDX7Ap7fGjq-jFH-tbB_jp0wGGoJfTBLQllftTz9f86oqTXCf85_4fnMgTxB2qX_LBfJw4s6oa1Oex-EpBEprM4C0Awtlo9IkNNRBKLhxa7wPwMHFR_y9w7ZgEbq2pd-qDb4WMcDFfR5mTpCcYYrhufHSa0gnDDDAOanVSaFf58V5T_kKnb72_md7JFf87rhOoSjML_1Ks",
     *      "id": 1,
     *      "customer_admin_id": 44,
     *      "frequency": "WEEKLYON",
     *      "day": 1,
     *      "time": "08:00:00",
     *      "active": 1,
     *      "created_at": "2021-02-03T03:41:06.000000Z",
     *      "updated_at": "2021-02-05T00:24:53.000000Z"
     * }
     *
     * @response status=422 scenario="Validation error" {
     *    "message": "The given data was invalid.",
     *    "errors": {
     *        "customer_admin_id": [
     *            "The customer_admin_id is required."
     *        ],
    *         "active": [
     *            "The active is required."
     *        ]
     *    }
     * }
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     * @return \Illuminate\Http\Response
     *
     */




    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_admin_id' => 'required',
            'active' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }

        try {
            $recipients = $this->reportRecipient($request->report_recipients);

            $emailScheduleData = $request->only('customer_admin_id', 'frequency', 'day', 'time', 'active', 'month_day', 'report_type');
            $emailSchedule = EmailReportSchedule::create($emailScheduleData);
    
            $user = User::find($request->customer_admin_id);
            if($user){
                $user->report_recipients  = isset($request->report_recipients) ? $recipients : $user->report_recipients;
                $user->update();
            }
            $response = [
                'status' => $emailSchedule ? true : false,
                'data' =>  $emailSchedule ? $emailSchedule : [],
                'message' => $emailSchedule ? 'Successfully created email report' : 'Failed to create email report'
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }

        return response()->json($response);

    }

    /**
     *
     * Update the specified resource in storage.
     *
     *
     * @bodyParam id int required The email report schedule ID
     * @bodyParam customer_admin_id int required The customer admin ID
     *
     * @bodyParam frequency string required frequency. Example: WEEKLYON
     * @bodyParam day tinyint required day. Example: 1
     * @bodyParam time time required time. Example: 08:00:00
     * @bodyParam active tinyint required active. Example: 1
     * @bodyParam month_day date required month_day. Example: Null
     * @bodyParam report_type string required report_type.
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\EmailReportSchedule
     * @apiResourceAdditional status=ok message="Updated Successfully."
     *
     * @response status=422 scenario="Validation error" {
     *
     *    "message": [
     *          "Email schedule: problem in updating Email Schedule",
     *          "User: Problem in updating Report Recipients"
     *    ],
     *    "errors": {
     *        "customer_admin_id": [
     *            "The customer admin id is required."
     *        ],
     *        "id": [
     *            "The id is required."
     *        ]
     *    },
     *    "status": "failed"
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @param report_recipient type array || string
     * [{"report_recipients": "email@email.com"}, {"report_recipients": "email@email.com"}]
     * {"report_recipients": "email@email.com"}
     *
     */
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        
       $response['status'] = 'failed';
 
        if($validator->fails()){
             return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
        try {

            $recipients = $this->reportRecipient($request->report_recipients);
            $emailScheduleData = $request->only('frequency', 'day', 'time', 'active', 'month_day', 'report_type');
            $emailSchedule = $emailSchedule = EmailReportSchedule::find($request->id);
            if($emailSchedule){
                $emailSchedule->update($emailScheduleData);
                $user = User::find($emailSchedule->customer_admin_id);
                if($user){
                    $user->report_recipients  = isset($request->report_recipients) ? $recipients : $user->report_recipients;
                    $user->update();
                }
                $response = [
                    'message' => 'Updated Successfully',
                    'status' => true
                ];
            }else{
                $response['message'] = "Data not found";
            }
           

        } catch (\Exception $e) {
        Log::error($e->getMessage());

            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }

       return response()->json($response);
    }

    /**
     *
     * Get All Customer Shipment Info
     *
     *
     * @urlParam email_report_schedule_id int required The email report schedule ID
     * @bodyParam id int required The customer_admin_id in email_report_schedules table
     *
     * @response status=200 {
     *      "message": ""
     * }
     *
     * @response status=422 scenario="Validation error" {
     *    "message": ["File does not exist","Failed in retrieving data"],
     *    "status": "failed",
     *    "errors": {
     *        "id": [
     *            "The id is required."
     *        ]
     *    }
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function download($id)
    {

        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required']
        );

        if($validator->fails()){
            $response['errors'] = $validator->errors();
            return response()->json([ 'status' => 'failed', 'message' => $response['errors'] ]);
        }
        
        $emailSchedule = EmailReportSchedule::with('CustomerAdmin')
            ->where('customer_admin_id', $id)
            ->where('active', true)
            ->whereReportType('BYREFERENCE')
            ->orWhere('customer_admin_id', $id)
            ->whereNull('report_type')->first();

        if($emailSchedule){
            $type = isset($emailSchedule->report_type) ? $emailSchedule->report_type : 'BYREFERENCE'; 
            $fileName = (new SendExcelReportMail($emailSchedule->CustomerAdmin, $type))->store();
        }else{
            $emailSchedule = CustomerAdmin::where('id', $id)->first();
            $fileName = (new SendExcelReportMail($emailSchedule, 'BYREFERENCE'))->store();
        }
        if($emailSchedule){
            $file = '/public/reports/excel/' . $fileName;
             //check if file exists
            if( !Storage::exists($file) ){
                return response()->json([ 'status' => 'failed', 'message' => 'File does not exist' ]);
            }
            
            $file = Storage::disk('public')->get('reports/excel/'.$fileName);
    
            return (new Response($file, 200))
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheets');

        }else{
            return response()->json(['status' => 'failed', 'message' => 'Failed in retrieving data']);
        }

    }


    public function reportRecipient($recipients)
    {
       $type = gettype($recipients);
       if($type === "array") {

            $recipients = $recipients ?? [];
            $arRecipients = [];
            foreach($recipients as $key => $val){
                $ar['report_recipients'] = $val;
                $arRecipients[] = $ar;
            }
            $recipients = json_encode($arRecipients, true);

       }elseif($type === "string") {
            $recipients = json_encode($recipients, true);
            $recipients = json_decode($recipients, true, JSON_UNESCAPED_SLASHES);
            $recipients = array($recipients);
       }
       return $recipients;
    }
    /**
     * Display dropdown value
     *
     *
     *
     * @response 200 {
     *      "data": {
     *          "frequency": {
     *          "DAILYAT": "Daily",
     *          "WEEKLYON": "Weekly",
     *          "MONTHLYON": "Monthly",
     *          "YEARLYON": "Yearly"
     *      },
     *      "days_of_the_week": {
     *          "1": "Monday",
     *          "2": "Tuesday",
     *          "3": "Wednesday",
     *          "4": "Thursday",
     *          "5": "Friday",
     *          "6": "Saturday",
     *          "7": "Sunday"
     *      },
     *      "report_by_option": {
     *          "BYREFERENCE": "Shifl Reference Number",
     *          "BYCONTAINER": "Container Number"
     *      },
     *      "days_in_month": {
     *          "1": "Day 1",
     *          "2": "Day 2",
     *          "3": "Day 3",
     *          "4": "Day 4",
     *          "5": "Day 5",
     *          "6": "Day 6",
     *          "7": "Day 7",
     *          "8": "Day 8",
     *          "9": "Day 9",
     *          "10": "Day 10",
     *          "11": "Day 11",
     *          "12": "Day 12",
     *          "13": "Day 13",
     *          "14": "Day 14",
     *          "15": "Day 15",
     *          "16": "Day 16",
     *          "17": "Day 17",
     *          "18": "Day 18",
     *          "19": "Day 19",
     *          "20": "Day 20",
     *          "21": "Day 21",
     *          "22": "Day 22",
     *          "23": "Day 23",
     *          "24": "Day 24",
     *          "25": "Day 25",
     *          "26": "Day 26",
     *          "27": "Day 27",
     *          "28": "Day 28",
     *          "29": "Day 29",
     *          "30": "Day 30",
     *          "31": "Day 31"
     *       }
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
    */


    public function getDropdownValue()
    {
        $data = [
            'frequency' => \App\EmailReportSchedule::$frequency,
            'days_of_the_week' => \App\EmailReportSchedule::$days_of_the_week,
            'report_by_option' => \App\EmailReportSchedule::$report_by_option_customer_view,
            'days_in_month' => (new EmailReportSchedule)->getDaysInMonths(),
        ];

        return response()->json(['data' => $data]);

    }

    /**
     * version 2
     *
     *
     */
    public function getEmailReportSchedule_v2($id)
    {
        try {
            $statusCode = 200;

            $users = (new EmailReportSchedule)->getEmailReportWithUsersByUserId($id)->get();

            if(!$users) {
                return response()->json(['message' => 'No Data Found', 'success' => false, 'data' => []], $statusCode);
            }

            foreach($users as $user){
                $arRecipients = [];
                $recipients = (is_array(json_decode($user->report_recipients))) ? json_decode($user->report_recipients) : [];
                foreach($recipients as $key => $value){
                    array_push($arRecipients, $value->report_recipients);
                }
                $user->report_recipients = $arRecipients;
            }
            
            $response = [
                'success' => count($users) > 0 ? true : false,
                'data' => count($users) > 0 ? $users : [],
                'message' => count($users) > 0 ? 'Data retrieved successfully' : 'No Data Found',
            ];

       } catch (\Exception $e) {
          Log::error($e->getMessage());

          $response = [
              'success' => false,
              'message' => 'Something went wrong',
          ];

       }
       return response()->json($response, $statusCode);
    }

    /**
     * Download V2
     *
     *
     * @urlParam user_id int required
     * @urlParam id int required
     *
     * @response status=200 {
     *      "status": "ok"
     * }
     *
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     *
     * @param $user_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function download_v2($user_id, $id)
    {

        $emailSchedule = EmailReportSchedule::with('CustomerAdmin')
            ->where('id', $id)
            ->where('customer_admin_id', $user_id)
            ->where('active', true)->first();

        if($emailSchedule){
            $type = isset($emailSchedule->report_type) ? $emailSchedule->report_type : 'BYREFERENCE'; 
            $fileName = (new SendExcelReportMail($emailSchedule->CustomerAdmin, $type))->store();
        }else{
            $emailSchedule = CustomerAdmin::where('id', $user_id)->first();
            $fileName = (new SendExcelReportMail($emailSchedule, 'BYREFERENCE'))->store();
        }
        if($emailSchedule){
            $file = '/public/reports/excel/' . $fileName;
             //check if file exists
            if( !Storage::exists($file) ){
                return response()->json([ 'status' => 'failed', 'message' => 'File does not exist' ]);
            }
            return Storage::download($file);
        }else{
            return response()->json(['status' => 'failed', 'message' => 'Failed in retrieving data']);
        }

    }

}
