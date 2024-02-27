<?php

namespace App\Http\Controllers\API;

use App\EmailReportSchedule;
use App\Terminal49Shipment;
use App\CustomerAdmin;
use App\User;
use App\Invoice;
use App\Shipment;
use App\Customer;
use Carbon\Carbon;
use App\Custom\SendExcelReportMail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class ShipmentCustomizeReport extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'customer_admin_id' => 'required',
                'active' => 'required'
            ]);
    
            if($validator->fails()){
                return response()->json(['status' => 400, 'error' => $validator->errors(), 'updated_on' => new \DateTime()], 400);

            }

            $checkEmailReports = EmailReportSchedule::where('customer_admin_id', $request->customer_admin_id)->where('active', 1);
            if($request->has('frequency')) $checkEmailReports->where('frequency', $request->frequency);
            if($request->has('report_type')) $checkEmailReports->where('report_type', $request->report_type);
            if($request->has('time')) $checkEmailReports->where('time', $request->time);
            if($request->has('day')) $checkEmailReports->where('day', $request->day);
            $existingEmailReports  = $checkEmailReports->get();

           if(count($existingEmailReports) > 0){
                return response()->json(['status' => 400, 'error' => 'Email report details already exists',  'updated_on' => new \DateTime()], 400);
           }
           
          
            $emailScheduleData = $request->only('customer_admin_id', 'frequency', 'day', 'time', 'active', 'month_day', 'report_type', 'report', 'selected_customer');
            
            if($request->has('report_columns')) {
                $report_columns = json_encode($request->report_columns);
                $emailScheduleData['report_columns'] =  $report_columns;
            }    

            if($request->has('report_recipients')) {
                $recipients = $this->reportRecipient($request->report_recipients);
                $emailScheduleData['report_recipients'] =  $recipients;
            }  

            $emailSchedule = EmailReportSchedule::create($emailScheduleData);
          
            
            return response()->json([
                'status' => $emailSchedule ? 200 : 400,
                'updated_on' => new \DateTime(),
                'data' =>  $emailSchedule ? $this->getEmailReportScheduleById($emailSchedule->id) : [],
                'message' => $emailSchedule ? 'Successfully created email report' : 'Failed to create email report'
            ], 200);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
  
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        } 
       
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

    
    public function show($id)
    {
        try {

            $emailSchedules = $this->getEmailReportScheduleByCustomerAdminId($id) ?? [];
            
            return response()->json([
                'status' => $emailSchedules ? 200 : 400,
                'data' => $emailSchedules ?? []
            ], $emailSchedules ? 200 : 400);


        } catch (\Exception $e) {
            \Log::error($e->getMessage());
  
           return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ],500);
        }
    }

    
    public function edit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'customer_admin_id' => 'required',
                'active' => 'required'
            ]);
    
            if($validator->fails()){
                return response()->json(['status' => 400, 'error' => $validator->errors(),  'updated_on' => new \DateTime()], 400);
            }


            $checkEmailReports = EmailReportSchedule::where('customer_admin_id', $request->customer_admin_id)->where('id', '<>', $request->id)->where('active', 1);
            if($request->has('frequency')) $checkEmailReports->where('frequency', $request->frequency);
            if($request->has('report_type')) $checkEmailReports->where('report_type', $request->report_type);
            if($request->has('time')) $checkEmailReports->where('time', $request->time);
            if($request->has('day')) $checkEmailReports->where('day', $request->day);
            $existingEmailReports  = $checkEmailReports->get();

            if(count($existingEmailReports) > 0){
                 return response()->json(['status' => 400, 'error' => 'Email report details already exists', 'updated_on' => new \DateTime(), 'title' => 'Edit Report'], 400);
           }


            $emailScheduleData = $request->only('customer_admin_id', 'frequency', 'day', 'time', 'active', 'month_day', 'report_type', 'report', 'selected_customer');
            
            if($request->has('report_columns')) {
                $report_columns = json_encode($request->report_columns);
                $emailScheduleData['report_columns'] =  $report_columns;
            }    
      
            if($request->has('report_recipients')) {
                $emailScheduleData['report_recipients'] =  $this->reportRecipient($request->report_recipients);
            }

            $emailSchedule = EmailReportSchedule::find($request->id);
            if($emailSchedule){
                $emailSchedule->update($emailScheduleData);
            }
                      
            return response()->json([
                'status' => $emailSchedule ? 200 : 400,
                'updated_on' => new \DateTime(),
                'data' =>  $emailSchedule ? $this->getEmailReportScheduleById($request->id) : [],
                'message' => $emailSchedule ? 'Successfully edited email report' : 'Failed to edit email report'
            ], $emailSchedule ? 200 : 400);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
  
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Something went wrong',
            ],500);
        } 
    }

    public function updateStatus($id, Request $request)
    {
        $statusCode = 200;
        try {
            $validator = Validator::make($request->all(), [
                'active' => 'required',
            ]);

            if($validator->fails()){
                $statusCode = 400;
                return response()->json(['status' => $statusCode, 'error' => $validator->errors(), 'updated_on' => new \DateTime()], $statusCode);
            }

            $emailSchedule = EmailReportSchedule::where('id', $id)->first();

            if($emailSchedule){
                $emailSchedule->active = $request->active;
                $emailSchedule->update();
                $aData = $this->getEmailReportScheduleById($id);
            }else{
                $statusCode = 400;
            }

            return response()->json([
                'message' => $emailSchedule ? 'Email Report Schedule status has been updated' : 'No email schedule found',
                'data' => $aData ?? []
            ], $statusCode);


        } catch ( \Exception $e ) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Internal Server Error',
                'status' => 500
            ], 500);

        }
        
    }

    public function deleteEmailReportSchedule($id)
    {
        $emailSchedule = EmailReportSchedule::find($id);
        if($emailSchedule){
            $emailSchedule->delete();
            return response()->json([
                'status' => 'ok',
                'message' => "Email Report Schedule Deleted Successfully"
            ], 200);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => "Email Report Schedule doesn't exists"
            ], 400);
        }
    }

    /**
     * @id = EmailReport Schedule
     * 
     */
    public function sendReport(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if($validator->fails()){
                return response()->json(['status' => 400, 'error' => $validator->errors(), 'updated_on' => new \DateTime()], 400);
            }

            $emailSchedule = EmailReportSchedule::with('CustomerAdmin')
                ->where('id', $request->id)->first();

            $fileName = '';
            if($emailSchedule){
                if($request->has('report_recipients')) {
                    $recipients = $this->reportRecipient($request->report_recipients);
                    $emailSchedule->report_recipients =  $recipients;
                    // $emailSchedule->update();
                }    
                $fileName = (new SendExcelReportMail($emailSchedule->CustomerAdmin, $emailSchedule->report_type, $emailSchedule))->handle(); 
            }
            
            return response()->json([
                'status' => $fileName ? 200 : 400,
                'message' => $fileName ? 'Shipment Report Mail Sent' : 'Error while sending report'
            ], $fileName ? 200 : 400);

            // getShipmentReportData($userId, $type)

        } catch ( \Exception $e ) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Internal Server Error',
                'status' => 500
            ], 500);

        }
        
    }

    public function download($id)
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => 'required']
        );

        if($validator->fails()){
            return response()->json([ 'status' => 400, 'message' => $validator->errors(), 'updated_on' => new \DateTime() ], 400);
        }
        
        $emailSchedule = EmailReportSchedule::with('CustomerAdmin')
            ->where('id', $id)->first();
        
        $fileName = '';
        if($emailSchedule){
            $type = isset($emailSchedule->report_type) ? $emailSchedule->report_type : 'BYREFERENCE'; 
            $fileName = (new SendExcelReportMail($emailSchedule->CustomerAdmin, $type, $emailSchedule))->store();
        }else{
            return response()->json(['status' => 400, 'message' => 'Email report not found'], 400); 
        }

        if($fileName){
            $file = '/public/reports/excel/' . $fileName;
             //check if file exists
            if( !Storage::exists($file) ){
                return response()->json([ 'status' => 'failed', 'message' => 'File does not exist' ]);
            }else{
                $file = Storage::disk('public')->get('reports/excel/'.$fileName);
                return (new Response($file, 200))
                    ->header('Content-Type', 'vnd.ms-excel');
            }
        }else{
            return response()->json(['status' => 400, 'message' => 'Failed in retrieving data'], 400);
        }

    }

    public function getReportFields()
    {
        $aResult = [];
        $aResult['by_reference'] = (new \App\Custom\SheetConfig('', 'BYREFERENCEADV'))->columnNames();
        $aResult['by_container'] = (new \App\Custom\SheetConfigByContainer())->columnNames();
        // $aResult['others'] = (new \App\Custom\SheetConfigByContainer())->columnWidths();

        return response()->json($aResult);
    }

    private function reportRecipient($recipients)
    {
       $type = gettype($recipients);
       if($type === "array") {

            $recipients = $recipients ?? [];
            $arRecipients = [];
            foreach($recipients as $key => $val){
                $ar['report_recipients'] = $val;
                $arRecipients[] = $ar;
            }
            // $recipients = json_encode($arRecipients, true);
            $recipients = $arRecipients;

       }elseif($type === "string") {
            $recipients = json_encode($recipients, true);
            $recipients = json_decode($recipients, true, JSON_UNESCAPED_SLASHES);
            $recipients = array($recipients);
       }
       return $recipients;
    }
    public function getShipmentReportData($userId, $type)
    {
        try {
            $aResult = []; 
            $customerAdmin = CustomerAdmin::where('id', $userId)->first();

            if($customerAdmin){
                $customers = $customerAdmin->customersApi;

                $shipments = collect();

                foreach($customers as $customer){
                    $shipments = $shipments->merge($customer->shipments);
                }

                $shipments = $shipments->filter->isValidForReport()->values();
                $shipments = $shipments->sortBy('eta', SORT_REGULAR, false);

                $inCompleteShipment = $shipments->filter->isShipmentActive()->values();
                $complete = $shipments->filter->isShipmentComplete()->values();
                $completeShipment = $complete->filter->isReportWithinSixMonths()->values();

                $aResult[$type]['Active'] = $this->processShipment($inCompleteShipment, $type);
                $aResult[$type]['Completed'] = $this->processShipment($completeShipment, $type);

            }
            
          
            return response()->json([
                'success' => count($aResult) > 0 ? true : false,
                'data' => $aResult,
                'message' =>  count($aResult) > 0 ? 'Data shipment successfully fetch' : 'Unable to retrieved data'
            ]);
            


        } catch (\Exception $e) {
            \Log::error($e->getMessage());
  
           return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function processShipment($shipments, $type)
    {
        $aResult = [];
        foreach($shipments as $shipment){

            $schedule = (new \App\Custom\ProcessSchedulesGroupBookings($shipment->schedules_group_bookings))->getSchedule();

            $processShipment = new \App\Custom\ProcessShipmentReport();
            $t49 = $processShipment->getShipmentContainerAndT49($shipment);
            $all_containers = $t49['all_containers'] ?? [];

            if(is_countable($all_containers) && count($all_containers) == 0){
                $all_containers = $processShipment->getContainersGroupBookings($shipment) ?? [];
            }    

            $t49_attr = $t49['attributes'] ?? [];
            $suppliers = $processShipment->getShipmentSupplier($shipment); 

           
            if($type != 'BYCONTAINER'){
                $conNum = [];
                $conSize = [];
                $dischargedDate = [];
                $lfd = [];
                $fullOut = [];
                $fullIn = [];
                $emptyIn = [];
                $emptyOut = [];
                $conSeal = [];
                $conKg = [];
                $conVolume = [];
                $cartons = [];
                
                foreach ($all_containers ?? [] as $container){
                    array_push($conNum, $container['container_num'] ?? '');
                    array_push($conSize, $container['size'] ?? '');
                    array_push($dischargedDate, $container['vessel_discharged'] ?? '');
                    array_push($lfd, $container['pickup_lfd'] ?? '');
                    array_push($fullOut, $container['full_out'] ?? '');
                    array_push($fullIn, $container['full_in'] ?? '');
                    array_push($emptyIn, $container['empty_in'] ?? '');
                    array_push($emptyOut, $container['empty_out'] ?? '');
                    array_push($conSeal, $container['seal_num'] ?? '');
                    array_push($conKg, $container['kg'] ?? '');
                    array_push($conVolume, $container['cbm'] ?? '');
                    array_push($cartons, $container['cartons'] ?? '');
                }
            }
           
            $sellRate = [];
            $customTotal = [];
            $otherServices = [];
            $carrier = '';
            $eta_latest = '';
            $originalEta = '';
            $pol = '';
            $pod = '';
            $etd = '';
            if($schedule ?? false){
                $pol = isset($schedule->location_from) ? \App\TerminalRegion::find($schedule->location_from)->name ?? ''  : '';
                $pol = !empty($pol) ? $pol : $t49_attr['port_of_lading_name'] ?? '';
                $pod = isset($schedule->location_to) ? \App\TerminalRegion::find($schedule->location_to)->name ?? ''  : '';
                $pod = !empty($pod) ? $pod : $t49_attr['port_of_discharge_name'] ?? '';
                $carrier = isset($schedule->carrier_name->id) ? \App\Carrier::find($schedule->carrier_name->id)->name ?? ''  : '';
                $carrier = !empty($carrier) ? $carrier : $t49_attr['shipping_line_name'] ?? '';
                if($schedule->sell_rates){
                    foreach($schedule->sell_rates as $sell_rate){
                        if($sell_rate->service_id == 1){
                            array_push($sellRate, $sell_rate->amount ?? '');
                        }
                        if($sell_rate->service_id == 2){
                            array_push($customTotal, $sell_rate->amount ?? '');
                        }
                        if($sell_rate->service_id != 1 && $sell_rate->service_id != 2){
                            $service = $sell_rate->service_name ?? '';
                            $amount = $sell_rate->amount ?? '';
                            array_push($otherServices, $service . ' '. $amount);
                        }
                    }
                }

                if($shipment->etd){
                    $etd = Carbon::parse($shipment->etd)->format("m-d-Y");
                }elseif($shipment->is_tracking_shipment == 1){
                    $etd = $t49_attr['pol_etd_at'] ?? '';
                    $atd = $t49_attr['pol_atd_at'] ?? '';
                    $etd = $etd ? Carbon::parse($etd)->format("m-d-Y") : Carbon::parse($atd)->format("m-d-Y");
                } 

                $eta_latest = $shipment->eta ? $shipment->eta : $shipment->getFinalETA();
                $eta_latest = $eta_latest ? Carbon::parse($eta_latest)->format("m-d-Y"): '' ;

                $etaLog = \App\EtaLog::where("mbl_num", $shipment->mbl_num)
                        ->orderBy('id', 'asc')->pluck('old_eta')
                        ->first();
                $originalEta = $etaLog ? Carbon::parse($etaLog)->format("m-d-Y") : $eta_latest;
            }
            


            if($type == 'BYREFERENCE'){

                $aResult[] = [
                    'Shifl Ref#' => $shipment->shifl_ref ?? '',
                    'MBL#' => $shipment->mbl_num ?? '',
                    'PO#' => $shipment->getTrackingAndSupplierPo($shipment),
                    'Consignee' => $shipment->customer->company_name ?? '',
                    'Shipper' => $suppliers['supplier'] ?? '',
                    'Telex Release' => $suppliers['status'] ?? '',
                    'Booked Date' => empty($shipment->booking_confirmed_at) ? " " : Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y"),
                    'ETD' => $etd ?? '',
                    'ETA' => $eta_latest ?? '',
                    'POL' => $pol ?? '',
                    'POD' => $pod ?? '',
                    'Terminal' => $shipment->terminal_id ? \App\Terminal::find($shipment->terminal_id)->name ?? '' : '',
                    'Number of Containers' => count($all_containers),
                    'Container#' => $conNum,
                    'Container Sizes' => $conSize,
                    'Discharge Date' => $dischargedDate,
                    'Freight Release' => $shipment->freight_released_processed == 1 ? 'Yes' : 'No',
                    'Customs Release' => $shipment->customs_released == 1 ? 'Yes' : 'No',
                    'Customs Submitted/Release Date' => empty($shipment->entry_netchb_date) ? " " : Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y"),
                    'LFD' => $lfd,
                    'Status' => $shipment->getGeneralStatus(),
                    'Full Gated Out' => $fullOut,
                    'Empty Gated In' => $emptyIn,
                    'Tracking Status' => $shipment->getTrackingStatus() ?? ''
                ];


            }

            if($type == 'BYREFERENCEADV' || $type == 'BYCONTAINER'){
                $customer = \App\Customer::find($shipment->customer_id)->qbo_customer ?? '';
                $qboCustomer =  !empty($customer) ? json_decode($customer) : [];

                if(!empty($qboCustomer)){
                    $invoice = new Invoice();

                    $demurrageDays = [];
                    $demurragePerDays = [];
                    $demurrages = $invoice->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Demurrage') ?? [];
                    foreach($demurrages as $service){
                        array_push($demurrageDays, $service->quantity ? (int) $service->quantity : '');
                        array_push($demurragePerDays, $service->rate ?? '');
                    }

                    $perDiems = $invoice->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Per Diem') ?? [];
                    $perDiemRatePerDay = [];
                    $perDiemDays = [];
                    foreach($perDiems as $service){
                        array_push($perDiemRatePerDay, $service->rate ?? '');
                        array_push($perDiemDays, $service->quantity ? (int) $service->quantity : '' );
                    }

                    $demurageTotal = $invoice->getTotalInvoiceServiceByShipmentId($shipment->id, ['Demurrage'], $qboCustomer) ?? '';
                    $perDiemTotal = $invoice->getTotalInvoiceServiceByShipmentId($shipment->id, ['Per Diem'], $qboCustomer) ?? '';
                    $pierPassTotal = $invoice->getTotalInvoiceServiceByShipmentId($shipment->id, ['Pier Pass', 'Pier Pass / CTF'], $qboCustomer) ?? '';
                    $others = $invoice->getOtherServiceTotal($shipment->id, $qboCustomer, ['Per Diem', 'Demurrage', 'Pier Pass', 'Pier Pass / CTF'], $schedule) ?? [];

                }

                if($type == 'BYREFERENCEADV'){
                    $aResult[] = [
                        'Shifl Ref#' => $shipment->shifl_ref ?? '',
                        'MBL#' => $shipment->mbl_num ?? '',
                        'Consignee' => $shipment->customer->company_name ?? '',
                        'Status' => $shipment->getGeneralStatus(),
                        'Booked Date' => empty($shipment->booking_confirmed_at) ? " " : Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y"),
                        'PO#' => $shipment->getTrackingAndSupplierPo($shipment),
                        'Shipper' => $suppliers['supplier'] ?? '',
                        'Supplier Cartons' => $suppliers['supplier_carton'] ?? '',
                        'HBL#' => $suppliers['hbl_num'] ?? '',
                        'Telex Release' => $suppliers['status'] ?? '',
                        'Type' => $schedule->type ?? '',
                        'Mode' => $schedule->mode ?? '',
                        'Carrier' => $carrier ?? '',
                        'Vessel Name' => $shipment->vessel ?? $t49_attr['pod_vessel_name'] ?? '',
                        'Voyage#' => $shipment->voyage_number ??  $t49_attr['pod_voyage_number'] ?? '',
                        'Total Cartons' =>  $suppliers['supplier_carton'] ?? '',
                        'No. of Containers' => count($all_containers),
                        'Container#' => $conNum ?? '',
                        'Container Seal#' => $conSeal ?? '',
                        'Container Sizes' => $conSize ?? '',
                        'Container Weight (kg)' => $conKg ?? '',
                        'Container Cartons' => $cartons ?? '',
                        'Container Volume' => $conVolume ?? '',
                        'Freight Cost' => $sellRate ?? '',
                        'Current ETD' => $etd ?? '',
                        'Current ETA' => $eta_latest ?? '',
                        'Original ETA' => $originalEta ?? '',
                        'POL' => $pol ?? '',
                        'POD' => $pod ?? '',
                        'Cargo Ready Date' => $suppliers['cargo_date'] ?? '',
                        'Empty Out' => $emptyOut ?? '',
                        'Gated In' => $fullIn ?? '',
                        'Terminal' => $shipment->terminal_id ? \App\Terminal::find($shipment->terminal_id)->name ?? '' : '',
                        'Shifl AN Sent' => $shipment->an_sent_at ?? '',
                        'Shifl DO Sent' => $shipment->do_sent_at ?? '',
                        'Delivery Loc (WRHS)' => $shipment->trucker_custom_note ?? '',
                        'Freight Release' => $shipment->freight_released_processed == 1 ? 'Yes' : 'No',
                        'Customs Release' => empty($shipment->entry_netchb_date) ? " " : Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y"),
                        'Discharge Date' => $dischargedDate,
                        'LFD (Latest)' => $lfd ?? '',
                        'Full Out' => $fullOut ?? '',
                        'Empty In' => $emptyIn ?? '',
                        'Demurrage Days' => $demurrageDays ?? '',
                        'Dmrg Rate Per Day' => $demurragePerDays ?? '',
                        'Demurrage Total' => $demurrageTotal ?? '',
                        'Per Diem Total' => $perDiemTotal ?? '',
                        'Pier Pass Total' => $pierPassTotal ?? '',
                        'Other Totals' => $others ?? '',
                        'Customs Total' => $customTotal ?? '',
                        'Other services Total' => $otherServices ?? '',
                        'Tracking Status' => $shipment->getTrackingStatus() ?? '',
                        'Booking#' => $shipment->booking_number ?? '',
                    ];
                }

                if($type == 'BYCONTAINER'){
                    $cons = \Arr::pluck($all_containers, 'container_num') ?? [];
                    $tracking = [];
                    if(isset($shipment->mbl_num) && count($cons) > 0){
                        $tracking =  (new \App\Custom\ProcessShipmentReport())->getTruckingShipmentByMBLAndCon($shipment->mbl_num, $cons);
                    }
                    

                    $mbl = $shipment->mbl_num ?? '';
                    if(count($all_containers) > 0){
                        foreach ($all_containers as $container){
                            $num = $container['container_num'] ?? '';
                            $aResult[] = [
                                'Shifl Ref#' => $shipment->shifl_ref ?? '',
                                'PO#' => $shipment->getTrackingAndSupplierPo($shipment),
                                'Shipper' => $suppliers['supplier'] ?? '',
                                'Factory Cargo Ready Date' => $suppliers['cargo_date'] ?? '',
                                'Supplier Cartons' => $suppliers['supplier_carton'] ?? '',
                                'Booked Date' => empty($shipment->booking_confirmed_at) ? " " : Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y"),
                                'POL' => $pol ?? '',
                                'POD' => $pod ?? '',
                                'Delivery Loc (WRHS)' => $shipment->trucker_custom_note ?? '',
                                'Consignee' => $shipment->customer->company_name ?? '',
                                'Type' => $schedule->type ?? '',
                                'Mode' => $schedule->mode ?? '',
                                'Carrier' => $carrier ?? '',
                                'MBL#' => $shipment->mbl_num ?? '',
                                'Vessel Name' => $shipment->vessel ?? $t49_attr['pod_vessel_name'] ?? '',
                                'Voyage#' => $shipment->voyage_number ??  $t49_attr['pod_voyage_number'] ?? '',
                                'Total Cartons' =>  $suppliers['supplier_carton'] ?? '',
                                'Container#' => $container['container_num'] ?? 'Not yet Specified',
                                'Container Sizes' => $container['size'] ?? '',
                                'Container Weight (kg)' => $container['kg'] ?? '',
                                'Container Cartons' => $container['cartons'] ?? '',
                                'Container Volume' => $container['cbm'] ?? '',
                                'Container Seal#' => $container['seal_num'] ?? '',
                                'Freight Rate Services' => $sellRate,
                                'Status' => $shipment->getGeneralStatus(),
                                'HBL#' => $suppliers['hbl_num'] ?? '',
                                'Telex Release' => $suppliers['status'] ?? '',
                                'Current ETD' => $etd ?? '',
                                'Current ETA' => $eta_latest ?? '',
                                'Original ETA' => $originalEta ?? '',
                                'Empty Out To FTY' => $container['empty_out'] ?? '',
                                'Gated In POL' => $container['full_in'] ?? '',
                                'Arrival Notice Sent' => $shipment->an_sent_at ?? '',
                                'DO Sent' => $shipment->do_sent_at ?? '',
                                'Freight Release' => $shipment->freight_released_processed == 1 ? 'Yes' : 'No',
                                'Customs Release' => empty($shipment->entry_netchb_date) ? " " : Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y"),
                                'Discharge' => $container['vessel_discharged'] ?? '',
                                'Latest LFD' => $container['pickup_lfd'] ?? '',
                                'Container Picked Up' => $container['full_out'] ?? '',
                                'Empty container returned' => $container['empty_in'] ?? '',
                                'Dmrg Rate Per Day' => $demurragePerDays ?? '',
                                'Demurrage Days' => $demurrageDays ?? '',
                                'Demurrage Total' => $demurrageTotal ?? '',
                                'Per Diem Rate Day' => $perDiemRatePerDay ?? '',
                                'Per Diem Days' => $perDiemDays ?? '',
                                'Total Per Diem' => $perDiemTotal ?? '',
                                'Pier Pass' => $pierPassTotal ?? '',
                                'Customs Entry Cost' => $customTotal ?? '',
                                'Other Charges' => $others ?? '',
                                'Other Services Total' => $otherServices ?? '',
                                'Tracking Status' => $shipment->getTrackingStatus() ?? '',
                                'Booking#' => $shipment->booking_number ?? '',
                                'Location At' => $tracking[$mbl][$num]['location_at'] ?? '',
                                'Deliver To' => $tracking[$mbl][$num]['deliver_to'] ?? '',
                                'Pre Gate Fees' => $tracking[$mbl][$num]['pre_gate_fees'] ?? '',
                                'Pickup Scheduled/Port Appointment' => $tracking[$mbl][$num]['pickup_scheduled'] ?? '',
                                'Pickup Date' => $tracking[$mbl][$num]['pickup_date'] ?? '',
                                'Prepull' => $tracking[$mbl][$num]['prepull'] ?? '',
                                'Port Wait Time' => $tracking[$mbl][$num]['port_wait_time'] ?? '',
                                'Scheduled Delivery' => $tracking[$mbl][$num]['scheduled_delivery'] ?? '',
                                'Trucking Mode' => $tracking[$mbl][$num]['mode'] ?? '',
                                'Delivered' => $tracking[$mbl][$num]['delivered'] ?? '',
                                'Container Empty' => $tracking[$mbl][$num]['container_empty'] ?? '',
                                'Wait Time' => $tracking[$mbl][$num]['wait_time'] ?? '',
                                'Empty Pickup Date' => $tracking[$mbl][$num]['empty_pickup_date'] ?? '',
                                'Return Empty' => $tracking[$mbl][$num]['return_empty'] ?? '',
                                'Per Diem Date' => $tracking[$mbl][$num]['per_diem_date'] ?? '',
                                'Chassis Days' => $tracking[$mbl][$num]['chassis_days'] ?? '',
                                'Storage Days' => $tracking[$mbl][$num]['storage_days'] ?? '',
                                'Months' => Carbon::parse($shipment->tracking_eta)->diffInMonths(now(), false) ?? '',
                                'Trucking Info' => $shipment->tracking_info ?? []
        
                            ]; 
                           
                        }

                    }else{
                        $aResult[] = [
                            'Shifl Ref#' => $shipment->shifl_ref ?? '',
                            'PO#' => $shipment->getTrackingAndSupplierPo($shipment),
                            'Shipper' => $suppliers['supplier'] ?? '',
                            'Factory Cargo Ready Date' => $suppliers['cargo_date'] ?? '',
                            'Supplier Cartons' => $suppliers['supplier_carton'] ?? '',
                            'Booked Date' => empty($shipment->booking_confirmed_at) ? " " : Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y"),
                            'POL' => $pol ?? '',
                            'POD' => $pod ?? '',
                            'Delivery Loc (WRHS)' => $shipment->trucker_custom_note ?? '',
                            'Consignee' => $shipment->customer->company_name ?? '',
                            'Type' => $schedule->type ?? '',
                            'Mode' => $schedule->mode ?? '',
                            'Carrier' => $carrier ?? '',
                            'MBL#' => $shipment->mbl_num ?? '',
                            'Vessel Name' => $shipment->vessel ?? $t49_attr['pod_vessel_name'] ?? '',
                            'Voyage#' => $shipment->voyage_number ??  $t49_attr['pod_voyage_number'] ?? '',
                            'Total Cartons' =>  $suppliers['supplier_carton'] ?? '',
                            'Container#' => $container['container_num'] ?? 'Not yet Specified',
                            'Container Sizes' => $container['size'] ?? '',
                            'Container Weight (kg)' => $container['kg'] ?? '',
                            'Container Cartons' => $container['cartons'] ?? '',
                            'Container Volume' => $container['cbm'] ?? '',
                            'Container Seal#' => $container['seal_num'] ?? '',
                            'Freight Rate Services' => $sellRate,
                            'Status' => $shipment->getGeneralStatus(),
                            'HBL#' => $suppliers['hbl_num'] ?? '',
                            'Telex Release' => $suppliers['status'] ?? '',
                            'Current ETD' => $etd,
                            'Current ETA' => $eta_latest ?? '',
                            'Original ETA' => $originalEta ?? '',
                            'Empty Out To FTY' => $container['empty_out'] ?? '',
                            'Gated In POL' => $container['full_in'] ?? '',
                            'Arrival Notice Sent' => $shipment->an_sent_at ?? '',
                            'DO Sent' => $shipment->do_sent_at ?? '',
                            'Freight Release' => $shipment->freight_released_processed == 1 ? 'Yes' : 'No',
                            'Customs Release' => empty($shipment->entry_netchb_date) ? " " : Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y"),
                            'Discharge' => $container['vessel_discharged'] ?? '',
                            'Latest LFD' => $container['pickup_lfd'] ?? '',
                            'Container Picked Up' => $container['full_out'] ?? '',
                            'Empty container returned' => $container['empty_in'] ?? '',
                            'Dmrg Rate Per Day' => $demurragePerDays ?? '',
                            'Demurrage Days' => $demurrageDays ?? '',
                            'Demurrage Total' => $demurrageTotal ?? '',
                            'Per Diem Rate Day' => $perDiemRatePerDay ?? '',
                            'Per Diem Days' => $perDiemDays ?? '',
                            'Total Per Diem' => $perDiemTotal ?? '',
                            'Pier Pass' => $pierPassTotal ?? '',
                            'Customs Entry Cost' => $customTotal ?? '',
                            'Other Charges' => $others ?? '',
                            'Other Services Total' => $otherServices ?? '',
                            'Tracking Status' => $shipment->getTrackingStatus() ?? '',
                            'Booking#' => $shipment->booking_number ?? '',
    
                        ];  
                    }                    

                }
                
            }

        }


        return $aResult;
    }

    private function getEmailReportScheduleByCustomerAdminId($id)
    {
        $emailSchedules = EmailReportSchedule::where('customer_admin_id', $id)->get();
            foreach($emailSchedules as $emailSchedule){
                $arRecipients = [];
                if (is_array($emailSchedule->report_recipients)) {
                    foreach($emailSchedule->report_recipients as $recipient){
                        array_push($arRecipients, $recipient['report_recipients']);
                    }
                }

                if($emailSchedule->report_type == 'BYREFERENCE'){
                    $emailSchedule->report_type = 'BYREFERENCEADV';
                }

                if(empty($emailSchedule->report_columns) || $emailSchedule->report_columns == null){
                    if($emailSchedule->report_type = 'BYREFERENCEADV'){
                        $emailSchedule->report_columns = (new \App\Custom\SheetConfig('', 'BYREFERENCEADV'))->columnNames();
                    }else{
                        $emailSchedule->report_columns = (new \App\Custom\SheetConfigByContainer())->columnNames();
                    }
                }

                $emailSchedule->report_recipients = $arRecipients;
            }
         
        return $emailSchedules;    
    }

    private function getEmailReportScheduleById($id)
    {
        $emailSchedule = EmailReportSchedule::where('id', $id)->first();
        $arRecipients = [];
        if (is_array($emailSchedule->report_recipients)) {
            foreach($emailSchedule->report_recipients as $recipient){
                array_push($arRecipients, $recipient['report_recipients']);
            }
        }

        $emailSchedule->report_recipients = $arRecipients;
         
        return $emailSchedule;    
    }


    /**
     * version 2 of shipment report from the new script
     */

    public function getShipmentReport($userId, $type)
    {
        // try {
            $aResult = []; 
            $customerAdmin = CustomerAdmin::where('id', $userId)->first();

            if($customerAdmin){
                $customers = $customerAdmin->customersApi;

                $shipments = collect();

                foreach($customers as $customer){
                    $shipments = $shipments->merge($customer->shipments);
                }

                $shipments = $shipments->filter->isValidForReport()->values();
                $shipments = $shipments->sortBy('eta', SORT_REGULAR, false);

                $inCompleteShipment = $shipments->filter->isShipmentActive()->values();
                $complete = $shipments->filter->isShipmentComplete()->values();
                $completeShipment = $complete->filter->isReportWithinSixMonths()->values();

                $aResult[$type]['Active'] = $this->processShipment_V2($inCompleteShipment, $type);
                $aResult[$type]['Completed'] = $this->processShipment_V2($completeShipment, $type);

            }
            
          
            return response()->json([
                'success' => count($aResult) > 0 ? true : false,
                'data' => $aResult,
                'message' =>  count($aResult) > 0 ? 'Data shipment successfully fetch' : 'Unable to retrieved data'
            ]);
            


        // } catch (\Exception $e) {
        //     \Log::error($e->getMessage());
  
        //    return response()->json([
        //         'success' => false,
        //         'message' => 'Something went wrong',
        //         'error' => $e->getMessage()
        //     ]);
        // }
    }


    public function processShipment_V2($shipments, $type)
    {
        $aResult = [];
        foreach($shipments as $shipment){

            $schedule = (new \App\Custom\ProcessSchedulesGroupBookings($shipment->schedules_group_bookings))->getSchedule();
            $all_containers = $shipment->containers_data ?? [];

            $processShipment = new \App\Custom\ProcessShipmentReport();
            $numbers = \Arr::pluck($all_containers, 'container_num') ?? [];    
            if(empty(implode("", $numbers))){
                $all_containers = $processShipment->getContainersGroupBookings($shipment) ?? [];
            }


            $t49_attr = [];
            if(isset($shipment->mbl_num)){
                $shipment_t49 = Terminal49Shipment::find($shipment->mbl_num);
                $t49_attr = isset($shipment_t49->attributes) ? json_decode($shipment_t49->attributes,true) ?? [] : [];
            }
            $suppliers = (new \App\Custom\ProcessShipmentReport())->getShipmentSupplier($shipment); 

            
            $sellRate = [];
            $customTotal = [];
            $otherServices = [];
            $carrier = '';
            $originalEta = '';
            $pol = '';
            $pod = '';
            $etd = '';
            $eta_latest = '';
            if($schedule ?? false){
                $pol = isset($schedule->location_from) ? \App\TerminalRegion::find($schedule->location_from)->name ?? ''  : '';
                $pol = !empty($pol) ? $pol : $t49_attr['port_of_lading_name'] ?? '';
                $pod = isset($schedule->location_to) ? \App\TerminalRegion::find($schedule->location_to)->name ?? ''  : '';
                $pod = !empty($pod) ? $pod : $t49_attr['port_of_discharge_name'] ?? '';
                $carrier = isset($schedule->carrier_name->id) ? \App\Carrier::find($schedule->carrier_name->id)->name ?? ''  : '';
                $carrier = !empty($carrier) ? $carrier : $t49_attr['shipping_line_name'] ?? '';
                if($schedule->sell_rates){
                    foreach($schedule->sell_rates as $sell_rate){
                        if($sell_rate->service_id == 1){
                            array_push($sellRate, $sell_rate->amount ?? '');
                        }
                        if($sell_rate->service_id == 2){
                            array_push($customTotal, $sell_rate->amount ?? '');
                        }
                        if($sell_rate->service_id != 1 && $sell_rate->service_id != 2){
                            $service = $sell_rate->service_name ?? '';
                            $amount = $sell_rate->amount ?? '';
                            array_push($otherServices, $service . ' '. $amount);
                        }
                    }
                }

                if($shipment->etd){
                    $etd = \Carbon\Carbon::parse($shipment->etd)->format("m-d-Y");
                }elseif($shipment->is_tracking_shipment == 1){
                    $etd = $t49_attr['pol_etd_at'] ?? '';
                    $atd = $t49_attr['pol_atd_at'] ?? '';
                    $etd = $etd ? \Carbon\Carbon::parse($etd)->format("m-d-Y") : \Carbon\Carbon::parse($atd)->format("m-d-Y");
                } 

                $finalETA = $shipment->getFinalETA();
                if(!empty($schedule->eta)){
                    $diff_months = \Carbon\Carbon::parse($finalETA)->diffInMonths(now(), false);
                }

               
                $eta_latest = $shipment->eta ? $shipment->eta : $finalETA;
                $eta_latest = $eta_latest ? \Carbon\Carbon::parse($eta_latest)->format("m-d-Y"): '' ;

                $etaLog = \App\EtaLog::where("mbl_num", $shipment->mbl_num)
                        ->orderBy('id', 'asc')->pluck('old_eta')
                        ->first();
                $original_eta = $etaLog ? \Carbon\Carbon::parse($etaLog)->format("m-d-Y") : $eta_latest;



            }
            


            if($type == 'BYREFERENCE'){

                $aResult[] = [
                    'Shifl Ref#' => $shipment->shifl_ref ?? '',
                    'MBL#' => $shipment->mbl_num ?? '',
                    'PO#' => $shipment->getTrackingAndSupplierPo($shipment),
                    'Consignee' => $shipment->customer->company_name ?? '',
                    'Shipper' => $suppliers['supplier'] ?? '',
                    'Telex Release' => $suppliers['status'] ?? '',
                    'Booked Date' => empty($shipment->booking_confirmed_at) ? " " : Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y"),
                    'ETD' => $etd ?? '',
                    'ETA' => $eta_latest ?? '',
                    'POL' => $pol ?? '',
                    'POD' => $pod ?? '',
                    'Terminal' => $shipment->terminal_id ? \App\Terminal::find($shipment->terminal_id)->name ?? '' : '',
                    'Number of Containers' => count($all_containers),
                    'Container#' => Arr::pluck($all_containers, 'container_num') ?? [],
                    'Container Sizes' => Arr::pluck($all_containers, 'size') ?? [],
                    'Discharge Date' => Arr::pluck($all_containers, 'vessel_discharged') ?? [],
                    'Freight Release' => $shipment->freight_released_processed == 1 ? 'Yes' : 'No',
                    'Customs Release' => $shipment->customs_released == 1 ? 'Yes' : 'No',
                    'Customs Submitted/Release Date' => empty($shipment->entry_netchb_date) ? " " : Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y"),
                    'LFD' => Arr::pluck($all_containers, 'pickup_lfd') ?? [],
                    'Status' => $shipment->status_fallback,
                    'Full Gated Out' => Arr::pluck($all_containers, 'full_out') ?? [],
                    'Empty Gated In' => Arr::pluck($all_containers, 'empty_in') ?? [],
                    'Tracking Status' => $shipment->getTrackingStatus() ?? '',
                    'Diff Months' => $diff_months ?? ''
                ];


            }

            if($type == 'BYREFERENCEADV' || $type == 'BYCONTAINER'){
                $customer = \App\Customer::find($shipment->customer_id)->qbo_customer ?? '';
                $qboCustomer =  !empty($customer) ? json_decode($customer) : [];

                if(!empty($qboCustomer)){
                    $invoice = new Invoice();

                    $demurrageDays = [];
                    $demurragePerDays = [];
                    $demurrages = $invoice->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Demurrage') ?? [];
                    foreach($demurrages as $service){
                        array_push($demurrageDays, $service->quantity ? (int) $service->quantity : '');
                        array_push($demurragePerDays, $service->rate ?? '');
                    }

                    
                    $perDiems = $invoice->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Per Diem') ?? [];
                    $perDiemRatePerDay = [];
                    $perDiemDays = [];
                    foreach($perDiems as $service){
                        array_push($perDiemRatePerDay, $service->rate ?? '');
                        array_push($perDiemDays, $service->quantity ? (int) $service->quantity : '' );
                    }

                    $demurageTotal = $invoice->getTotalInvoiceServiceByShipmentId($shipment->id, ['Demurrage'], $qboCustomer) ?? '';
                    $perDiemTotal = $invoice->getTotalInvoiceServiceByShipmentId($shipment->id, ['Per Diem'], $qboCustomer) ?? '';
                    $pierPassTotal = $invoice->getTotalInvoiceServiceByShipmentId($shipment->id, ['Pier Pass', 'Pier Pass / CTF'], $qboCustomer) ?? '';
                    $others = $invoice->getOtherServiceTotal($shipment->id, $qboCustomer, ['Per Diem', 'Demurrage', 'Pier Pass', 'Pier Pass / CTF'], $schedule) ?? [];

                }

                if($type == 'BYREFERENCEADV'){
                    $aResult[] = [
                        'Shifl Ref#' => $shipment->shifl_ref ?? '',
                        'MBL#' => $shipment->mbl_num ?? '',
                        'Consignee' => $shipment->customer->company_name ?? '',
                        'Status' => $shipment->status_fallback,
                        'Booked Date' => empty($shipment->booking_confirmed_at) ? " " : Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y"),
                        'PO#' => $shipment->getTrackingAndSupplierPo($shipment),
                        'Shipper' => $suppliers['supplier'] ?? '',
                        'Supplier Cartons' => $suppliers['supplier_carton'] ?? '',
                        'HBL#' => $suppliers['hbl_num'] ?? '',
                        'Telex Release' => $suppliers['status'] ?? '',
                        'Type' => $schedule->type ?? '',
                        'Mode' => $schedule->mode ?? '',
                        'Carrier' => $carrier ?? '',
                        'Vessel Name' => $shipment->vessel ?? $t49_attr['pod_vessel_name'] ?? '',
                        'Voyage#' => $shipment->voyage_number ??  $t49_attr['pod_voyage_number'] ?? '',
                        'Total Cartons' =>  $suppliers['supplier_carton'] ?? '',
                        'No. of Containers' => count($all_containers),
                        'Container#' => Arr::pluck($all_containers, 'container_num') ?? [],
                        'Container Seal#' => Arr::pluck($all_containers, 'seal_num') ?? [],
                        'Container Sizes' => Arr::pluck($all_containers, 'size') ?? [],
                        'Container Weight (kg)' => Arr::pluck($all_containers, 'kg') ?? [],
                        'Container Cartons' => Arr::pluck($all_containers, 'cartons') ?? [],
                        'Container Volume' => Arr::pluck($all_containers, 'cbm') ?? [],
                        'Freight Cost' => $sellRate ?? '',
                        'Current ETD' => $etd ?? '',
                        'Current ETA' => $eta_latest ?? '',
                        'Original ETA' => $originalEta ?? '',
                        'POL' => $pol ?? '',
                        'POD' => $pod ?? '',
                        'Cargo Ready Date' => $suppliers['cargo_date'] ?? '',
                        'Empty Out' => Arr::pluck($all_containers, 'empty_out') ?? [],
                        'Gated In' => Arr::pluck($all_containers, 'full_in') ?? [],
                        'Terminal' => $shipment->terminal_id ? \App\Terminal::find($shipment->terminal_id)->name ?? '' : '',
                        'Shifl AN Sent' => $shipment->an_sent_at ?? '',
                        'Shifl DO Sent' => $shipment->do_sent_at ?? '',
                        'Delivery Loc (WRHS)' => $shipment->trucker_custom_note ?? '',
                        'Freight Release' => $shipment->freight_released_processed == 1 ? 'Yes' : 'No',
                        'Customs Release' => empty($shipment->entry_netchb_date) ? " " : Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y"),
                        'Discharge Date' => Arr::pluck($all_containers, 'vessel_discharged') ?? [],
                        'LFD (Latest)' => Arr::pluck($all_containers, 'pickup_lfd') ?? [],
                        'Available for Pickup' => Arr::pluck($all_containers, 'available_for_pickup') ?? [],
                        'Full Out' => Arr::pluck($all_containers, 'full_out') ?? [],
                        'Empty In' => Arr::pluck($all_containers, 'empty_in') ?? [],
                        'Demurrage Days' => $demurrageDays ?? '',
                        'Dmrg Rate Per Day' => $demurragePerDays ?? '',
                        'Demurrage Total' => $demurrageTotal ?? '',
                        'Per Diem Total' => $perDiemTotal ?? '',
                        'Pier Pass Total' => $pierPassTotal ?? '',
                        'Other Totals' => $others ?? '',
                        'Customs Total' => $customTotal ?? '',
                        'Other services Total' => $otherServices ?? '',
                        'Tracking Status' => $shipment->getTrackingStatus() ?? '',
                        'Booking#' => $shipment->booking_number ?? '',
                        'Diff Months' => $diff_months ?? ''
                    ];
                }

                if($type == 'BYCONTAINER'){

                    $cons = \Arr::pluck($all_containers, 'container_num') ?? [];
                    $tracking = [];
                    if($shipment->mbl_num && count($cons) > 0){
                        $tracking =  $processShipment->getTruckingShipmentByMBLAndCon($shipment->mbl_num, $cons);
                    }
                   
                    $mbl = $shipment->mbl_num ?? '';
                    if(count($all_containers) > 0){
                        foreach ($all_containers as $container){
                            $num = $container['container_num'] ?? '';
                            $aResult[] = [
                                'Shifl Ref#' => $shipment->shifl_ref ?? '',
                                'PO#' => $shipment->getTrackingAndSupplierPo($shipment),
                                'Shipper' => $suppliers['supplier'] ?? '',
                                'Factory Cargo Ready Date' => $suppliers['cargo_date'] ?? '',
                                'Supplier Cartons' => $suppliers['supplier_carton'] ?? '',
                                'Booked Date' => empty($shipment->booking_confirmed_at) ? " " : Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y"),
                                'POL' => $pol ?? '',
                                'POD' => $pod ?? '',
                                'Delivery Loc (WRHS)' => $shipment->trucker_custom_note ?? '',
                                'Consignee' => $shipment->customer->company_name ?? '',
                                'Type' => $schedule->type ?? '',
                                'Mode' => $schedule->mode ?? '',
                                'Carrier' => $carrier ?? '',
                                'MBL#' => $shipment->mbl_num ?? '',
                                'Vessel Name' => $shipment->vessel ?? $t49_attr['pod_vessel_name'] ?? '',
                                'Voyage#' => $shipment->voyage_number ??  $t49_attr['pod_voyage_number'] ?? '',
                                'Total Cartons' =>  $suppliers['supplier_carton'] ?? '',
                                'Container#' => $container['container_num'] ?? 'Not yet Specified',
                                'Container Sizes' => $container['size'] ?? '',
                                'Container Weight (kg)' => $container['kg'] ?? '',
                                'Container Cartons' => $container['cartons'] ?? '',
                                'Container Volume' => $container['cbm'] ?? '',
                                'Container Seal#' => $container['seal_num'] ?? '',
                                'Freight Rate Services' => $sellRate,
                                'Status' => $shipment->status_fallback,
                                'HBL#' => $suppliers['hbl_num'] ?? '',
                                'Telex Release' => $suppliers['status'] ?? '',
                                'Current ETD' => $etd ?? '',
                                'Current ETA' => $eta_latest ?? '',
                                'Original ETA' => $originalEta ?? '',
                                'Empty Out To FTY' => $container['empty_out'] ?? '',
                                'Gated In POL' => $container['full_in'] ?? '',
                                'Arrival Notice Sent' => $shipment->an_sent_at ?? '',
                                'DO Sent' => $shipment->do_sent_at ?? '',
                                'Freight Release' => $shipment->freight_released_processed == 1 ? 'Yes' : 'No',
                                'Customs Release' => empty($shipment->entry_netchb_date) ? " " : Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y"),
                                'Discharge' => $container['vessel_discharged'] ?? '',
                                'Available for Pickup' => $container['available_for_pickup'] ?? '',
                                'Latest LFD' => $container['pickup_lfd'] ?? '',
                                'Container Picked Up' => $container['full_out'] ?? '',
                                'Empty container returned' => $container['empty_in'] ?? '',
                                'Dmrg Rate Per Day' => $demurragePerDays ?? '',
                                'Demurrage Days' => $demurrageDays ?? '',
                                'Demurrage Total' => $demurrageTotal ?? '',
                                'Per Diem Rate Day' => $perDiemRatePerDay ?? '',
                                'Per Diem Days' => $perDiemDays ?? '',
                                'Total Per Diem' => $perDiemTotal ?? '',
                                'Pier Pass' => $pierPassTotal ?? '',
                                'Customs Entry Cost' => $customTotal ?? '',
                                'Other Charges' => $others ?? '',
                                'Other Services Total' => $otherServices ?? '',
                                'Tracking Status' => $shipment->getTrackingStatus() ?? '',
                                'Booking#' => $shipment->booking_number ?? '',
                                //tracking info added
                                'Location At' => $tracking[$mbl][$num]['location_at'] ?? '',
                                'Deliver To' => $tracking[$mbl][$num]['deliver_to'] ?? '',
                                'Pre Gate Fees' => $tracking[$mbl][$num]['pre_gate_fees'] ?? '',
                                'Pickup Scheduled/Port Appointment' => $tracking[$mbl][$num]['pickup_scheduled'] ?? '',
                                'Pickup Date' => $tracking[$mbl][$num]['pickup_date'] ?? '',
                                'Prepull' => $tracking[$mbl][$num]['prepull'] ?? '',
                                'Port Wait Time' => $tracking[$mbl][$num]['port_wait_time'] ?? '',
                                'Scheduled Delivery' => $tracking[$mbl][$num]['scheduled_delivery'] ?? '',
                                'Trucking Mode' => $tracking[$mbl][$num]['mode'] ?? '',
                                'Delivered' => $tracking[$mbl][$num]['delivered'] ?? '',
                                'Container Empty' => $tracking[$mbl][$num]['container_empty'] ?? '',
                                'Wait Time' => $tracking[$mbl][$num]['wait_time'] ?? '',
                                'Empty Pickup Date' => $tracking[$mbl][$num]['empty_pickup_date'] ?? '',
                                'Return Empty' => $tracking[$mbl][$num]['return_empty'] ?? '',
                                'Per Diem Date' => $tracking[$mbl][$num]['per_diem_date'] ?? '',
                                'Chassis Days' => $tracking[$mbl][$num]['chassis_days'] ?? '',
                                'Storage Days' => $tracking[$mbl][$num]['storage_days'] ?? '',
                                'Diff Months' => $diff_months ?? ''

        
                            ]; 
                           
                        }

                    }else{
                        //this is still BYCONTAINER
                        $aResult[] = [
                            'Shifl Ref#' => $shipment->shifl_ref ?? '',
                            'PO#' => $shipment->getTrackingAndSupplierPo($shipment),
                            'Shipper' => $suppliers['supplier'] ?? '',
                            'Factory Cargo Ready Date' => $suppliers['cargo_date'] ?? '',
                            'Supplier Cartons' => $suppliers['supplier_carton'] ?? '',
                            'Booked Date' => empty($shipment->booking_confirmed_at) ? " " : Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y"),
                            'POL' => $pol ?? '',
                            'POD' => $pod ?? '',
                            'Delivery Loc (WRHS)' => $shipment->trucker_custom_note ?? '',
                            'Consignee' => $shipment->customer->company_name ?? '',
                            'Type' => $schedule->type ?? '',
                            'Mode' => $schedule->mode ?? '',
                            'Carrier' => $carrier ?? '',
                            'MBL#' => $shipment->mbl_num ?? '',
                            'Vessel Name' => $shipment->vessel ?? $t49_attr['pod_vessel_name'] ?? '',
                            'Voyage#' => $shipment->voyage_number ??  $t49_attr['pod_voyage_number'] ?? '',
                            'Total Cartons' =>  $suppliers['supplier_carton'] ?? '',
                            'Container#' => $container['container_num'] ?? 'Not yet Specified',
                            'Container Sizes' => '',
                            'Container Weight (kg)' => '',
                            'Container Cartons' => '',
                            'Container Volume' => '',
                            'Container Seal#' => '',
                            'Freight Rate Services' => $sellRate,
                            'Status' => $shipment->status_fallback,
                            'HBL#' => $suppliers['hbl_num'] ?? '',
                            'Telex Release' => $suppliers['status'] ?? '',
                            'Current ETD' => $etd ?? '',
                            'Current ETA' => $eta_latest ?? '',
                            'Original ETA' => $originalEta ?? '',
                            'Empty Out To FTY' => '',
                            'Gated In POL' => '',
                            'Arrival Notice Sent' => $shipment->an_sent_at ?? '',
                            'DO Sent' => $shipment->do_sent_at ?? '',
                            'Freight Release' => $shipment->freight_released_processed == 1 ? 'Yes' : 'No',
                            'Customs Release' => empty($shipment->entry_netchb_date) ? " " : Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y"),
                            'Discharge' => '',
                            'Latest LFD' => '',
                            'Available for Pickup' => '',
                            'Container Picked Up' => '',
                            'Empty container returned' => '',
                            'Dmrg Rate Per Day' => $demurragePerDays ?? '',
                            'Demurrage Days' => $demurrageDays ?? '',
                            'Demurrage Total' => $demurrageTotal ?? '',
                            'Per Diem Rate Day' => $perDiemRatePerDay ?? '',
                            'Per Diem Days' => $perDiemDays ?? '',
                            'Total Per Diem' => $perDiemTotal ?? '',
                            'Pier Pass' => $pierPassTotal ?? '',
                            'Customs Entry Cost' => $customTotal ?? '',
                            'Other Charges' => $others ?? '',
                            'Other Services Total' => $otherServices ?? '',
                            'Tracking Status' => $shipment->getTrackingStatus() ?? '',
                            'Booking#' => $shipment->booking_number ?? '',
                            //tracking info added
                            'Location At' => '',
                            'Deliver To' => '',
                            'Pre Gate Fees' => '',
                            'Pickup Scheduled/Port Appointment' => '',
                            'Pickup Date' => '',
                            'Prepull' => '',
                            'Port Wait Time' => '',
                            'Scheduled Delivery' => '',
                            'Mode' => '',
                            'Delivered' => '',
                            'Container Empty' => '',
                            'Wait Time' => '',
                            'Empty Pickup Date' => '',
                            'Return Empty' => '',
                            'Per Diem Date' => '',
                            'Chassis Days' => '',
                            'Storage Days' => '',
                            'Diff Months' => $diff_months ?? ''
    
                        ];  
                    }                    

                }
                
            }

        }


        return $aResult;
    }

   

    public function getShipmentReportById($id)
    {
        //this is only for testing purposes 
        $shipment = Shipment::find($id);

        $schedule = (new \App\Custom\ProcessSchedulesGroupBookings($shipment->schedules_group_bookings))->getSchedule();
        $all_containers = $shipment->containers_data ?? [];
        $t49_attr = [];
        if(isset($shipment->mbl_num)){
            $shipment_t49 = Terminal49Shipment::find($shipment->mbl_num);
            $t49_attr = isset($shipment_t49->attributes) ? json_decode($shipment_t49->attributes,true) ?? [] : [];
        }
        $suppliers = (new \App\Custom\ProcessShipmentReport())->getShipmentSupplier($shipment); 

            
        $sellRate = [];
        $customTotal = [];
        $otherServices = [];
        $carrier = '';
        $pol = '';
        $pod = '';
        $etd = '';
        $eta_latest = '';
        $original_eta = '';
        if($schedule ?? false){
            $pol = isset($schedule->location_from) ? \App\TerminalRegion::find($schedule->location_from)->name ?? ''  : '';
            $pol = !empty($pol) ? $pol : $t49_attr['port_of_lading_name'] ?? '';
            $pod = isset($schedule->location_to) ? \App\TerminalRegion::find($schedule->location_to)->name ?? ''  : '';
            $pod = !empty($pod) ? $pod : $t49_attr['port_of_discharge_name'] ?? '';
            $carrier = isset($schedule->carrier_name->id) ? \App\Carrier::find($schedule->carrier_name->id)->name ?? ''  : '';
            $carrier = !empty($carrier) ? $carrier : $t49_attr['shipping_line_name'] ?? '';
            if($schedule->sell_rates){
                foreach($schedule->sell_rates as $sell_rate){
                    if($sell_rate->service_id == 1){
                        array_push($sellRate, $sell_rate->amount ?? '');
                    }
                    if($sell_rate->service_id == 2){
                        array_push($customTotal, $sell_rate->amount ?? '');
                    }
                    if($sell_rate->service_id != 1 && $sell_rate->service_id != 2){
                        $service = $sell_rate->service_name ?? '';
                        $amount = $sell_rate->amount ?? '';
                        array_push($otherServices, $service . ' '. $amount);
                    }
                }
            }

            if($shipment->etd){
                $etd = \Carbon\Carbon::parse($shipment->etd)->format("m-d-Y");
            }elseif($shipment->is_tracking_shipment == 1){
                $etd = $t49_attr['pol_etd_at'] ?? '';
                $atd = $t49_attr['pol_atd_at'] ?? '';
                $etd = $etd ? \Carbon\Carbon::parse($etd)->format("m-d-Y") : \Carbon\Carbon::parse($atd)->format("m-d-Y");
            } 

            $finalETA = $shipment->getFinalETA();
            if(!empty($schedule->eta)){
                $diff_months = \Carbon\Carbon::parse($finalETA)->diffInMonths(now(), false);
            }

            $eta_latest = $shipment->eta ? $shipment->eta : $finalETA;
            $eta_latest = $eta_latest ? \Carbon\Carbon::parse($eta_latest)->format("m-d-Y"): '' ;

            $etaLog = \App\EtaLog::where("mbl_num", $shipment->mbl_num)
                    ->orderBy('id', 'asc')->pluck('old_eta')
                    ->first();
            $original_eta = $etaLog ? \Carbon\Carbon::parse($etaLog)->format("m-d-Y") : $eta_latest;

        }

        $customer = \App\Customer::find($shipment->customer_id)->qbo_customer ?? '';
        $qboCustomer =  !empty($customer) ? json_decode($customer) : [];

        if(!empty($qboCustomer)){
            $invoice = new Invoice();

            $demurrageDays = [];
            $demurragePerDays = [];
            $demurrages = $invoice->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Demurrage') ?? [];
            foreach($demurrages as $service){
                array_push($demurrageDays, $service->quantity ? (int) $service->quantity : '');
                array_push($demurragePerDays, $service->rate ?? '');
            }

            
            $perDiems = $invoice->getInvoiceServiceRateAndDays($shipment->id, $qboCustomer, 'Per Diem') ?? [];
            $perDiemRatePerDay = [];
            $perDiemDays = [];
            foreach($perDiems as $service){
                array_push($perDiemRatePerDay, $service->rate ?? '');
                array_push($perDiemDays, $service->quantity ? (int) $service->quantity : '' );
            }

            $demurageTotal = $invoice->getTotalInvoiceServiceByShipmentId($shipment->id, ['Demurrage'], $qboCustomer) ?? '';
            $perDiemTotal = $invoice->getTotalInvoiceServiceByShipmentId($shipment->id, ['Per Diem'], $qboCustomer) ?? '';
            $pierPassTotal = $invoice->getTotalInvoiceServiceByShipmentId($shipment->id, ['Pier Pass', 'Pier Pass / CTF'], $qboCustomer) ?? '';
            $others = $invoice->getOtherServiceTotal($shipment->id, $qboCustomer, ['Per Diem', 'Demurrage', 'Pier Pass', 'Pier Pass / CTF'], $schedule) ?? [];

        }

        $t49 = (new \App\Custom\ProcessShipmentReport())->getShipmentContainerAndT49($shipment);

        $aResult['REFADV'] = [
            'Shifl Ref#' => $shipment->shifl_ref ?? '',
            'MBL#' => $shipment->mbl_num ?? '',
            'Consignee' => $shipment->customer->company_name ?? '',
            'Status Fallback' => $shipment->status_fallback ?? '',
            'General Status' => $shipment->getGeneralStatus() ?? '',
            'Booked Date' => empty($shipment->booking_confirmed_at) ? " " : Carbon::parse($shipment->booking_confirmed_at)->format("m-d-Y"),
            'PO#' => $shipment->getTrackingAndSupplierPo($shipment),
            'Shipper' => $suppliers['supplier'] ?? '',
            'Supplier Cartons' => $suppliers['supplier_carton'] ?? '',
            'HBL#' => $suppliers['hbl_num'] ?? '',
            'Telex Release' => $suppliers['status'] ?? '',
            'Type' => $schedule->type ?? '',
            'Mode' => $schedule->mode ?? '',
            'Carrier' => $carrier ?? '',
            'Vessel Name' => $shipment->vessel ?? $t49_attr['pod_vessel_name'] ?? '',
            'Voyage#' => $shipment->voyage_number ??  $t49_attr['pod_voyage_number'] ?? '',
            'Total Cartons' =>  $suppliers['supplier_carton'] ?? '',
            'No. of Containers' => count($all_containers),
            'Container#' => Arr::pluck($all_containers, 'container_num') ?? [],
            'Container Seal#' => Arr::pluck($all_containers, 'seal_num') ?? [],
            'Container Sizes' => Arr::pluck($all_containers, 'size') ?? [],
            'Container Weight (kg)' => Arr::pluck($all_containers, 'kg') ?? [],
            'Container Cartons' => Arr::pluck($all_containers, 'cartons') ?? [],
            'Container Volume' => Arr::pluck($all_containers, 'cbm') ?? [],
            'T49' => $t49['all_containers'] ?? [],
            'Freight Cost' => $sellRate ?? '',
            'Current ETD' => $etd ?? '',
            'Current ETA' => $eta_latest ?? '',
            'Original ETA' => $original_eta ?? '',
            'POL' => $pol ?? '',
            'POD' => $pod ?? '',
            'Cargo Ready Date' => $suppliers['cargo_date'] ?? '',
            'Empty Out' => Arr::pluck($all_containers, 'empty_out') ?? [],
            'Gated In' => Arr::pluck($all_containers, 'full_in') ?? [],
            'Terminal' => $shipment->terminal_id ? \App\Terminal::find($shipment->terminal_id)->name ?? '' : '',
            'Shifl AN Sent' => $shipment->an_sent_at ?? '',
            'Shifl DO Sent' => $shipment->do_sent_at ?? '',
            'Delivery Loc (WRHS)' => $shipment->trucker_custom_note ?? '',
            'Freight Release' => $shipment->freight_released_processed == 1 ? 'Yes' : 'No',
            'Customs Release' => empty($shipment->entry_netchb_date) ? " " : Carbon::parse($shipment->entry_netchb_date)->format("m-d-Y"),
            'Discharge Date' => Arr::pluck($all_containers, 'vessel_discharged') ?? [],
            'LFD (Latest)' => Arr::pluck($all_containers, 'pickup_lfd') ?? [],
            'Available for Pickup' => Arr::pluck($all_containers, 'available_for_pickup') ?? [],
            'Full Out' => Arr::pluck($all_containers, 'full_out') ?? [],
            'Empty In' => Arr::pluck($all_containers, 'empty_in') ?? [],
            'Demurrage Days' => $demurrageDays ?? '',
            'Dmrg Rate Per Day' => $demurragePerDays ?? '',
            'Demurrage Total' => $demurrageTotal ?? '',
            'Per Diem Total' => $perDiemTotal ?? '',
            'Pier Pass Total' => $pierPassTotal ?? '',
            'Other Totals' => $others ?? '',
            'Customs Total' => $customTotal ?? '',
            'Other services Total' => $otherServices ?? '',
            'Tracking Status' => $shipment->getTrackingStatus() ?? '',
            'Booking#' => $shipment->booking_number ?? '',
            'Diff Months' => $diff_months ?? ''
        ];

        return response()->json($aResult);
    }    

    //this is only to check for a matching shipment in central and trucking
    //via mbl & container number
    public function getShipmentTrucking(Request $request, $mbl) 
    {
        $aResponse = [];
        $container = $request->container ?? '';
        $start = $request->start;
        $end = $request->end;

        $dbTrucking = env('TRUCKING_DB_CONNECTION');
        $aResponse['TRUCKING_DB_CONNECTION'] =  $dbTrucking;
        $aResponse['DB_CONNECTION'] =  env('DB_CONNECTION');
        $aResponse['Central-ID'] = Shipment::where('mbl_num', $mbl)->pluck('id') ?? '';
        if(is_countable($container) && count($container) > 0){
            $truckingShipment =  (new \App\Custom\ProcessShipmentReport())->getTruckingShipmentByMBLAndCon($mbl, $container);
            if(count($truckingShipment) > 0){
                $aResponse['trucking-shipment'] = $truckingShipment;
            }
            
        }

        if($request->has('start') && $request->has('end')){


            $shipments = Shipment::where('cancelled', 0)->whereNotNull('mbl_num')
                ->where('id', '>', $start)->where('id', '<', $end)->get();        

        
            $trucking = [];
            foreach($shipments as $shipment){

                $t49 = (new \App\Custom\ProcessShipmentReport())->getShipmentContainerAndT49($shipment);
                $all_containers = $t49['all_containers'] ?? [];
                $cons = \Arr::pluck($all_containers, 'container_num') ?? [];
                
                if($shipment->mbl_num && count($cons) > 0){
                        $truckingShipment =  (new \App\Custom\ProcessShipmentReport())->getTruckingShipmentByMBLAndCon($shipment->mbl_num, $cons);
                    if(count($trucking) > 0){
                        array_push($trucking, $truckingShipment);
                    }   
                    
                }

            } 
            $aResponse['trucking'] = $trucking;  
        }

        if($request->has('shipment')){
            // $aResponse['shipment'] = \DB::connection('mysql-trucking')->table('shipments')
            //     ->where('shipments.cancelled', 0)
            //     ->limit(150)
                // ->where('shipments.id', $request->shipment)
                // ->join('dispatches', 'dispatches.shipment_id', '=', 'shipments.id')
                // ->join('dispatch_legs', 'dispatch_legs.dispatch_id', '=', 'dispatches.id')
                // ->join('terminals', 'terminals.id', '=', 'dispatches.terminal_id')
                // ->join('customers', 'customers.id', '=', 'shipments.customer_id')
                // ->select('shipments.*', 'dispatch_legs.*', 'shipments.id as shipments_id', 'dispatch_legs.id as dispatch_legs_id',
                //     'dispatch_legs.type as dispatch_legs_type', 'dispatch_legs.completed as dispatch_legs_completed', 'terminals.name as terminal')
                //->get();     

            $aResponse['shipment-mbl-con'] = \DB::connection('mysql-trucking')->table('shipments')
                ->where('shipments.cancelled', 0)
                ->where('mbl_num', $mbl)
                ->join('dispatches', 'dispatches.shipment_id', '=', 'shipments.id')
                ->join('dispatch_legs', 'dispatch_legs.dispatch_id', '=', 'dispatches.id')
                ->join('terminals', 'terminals.id', '=', 'dispatches.terminal_id')
                ->join('customers', 'customers.id', '=', 'shipments.customer_id')
                ->select('shipments.*', 'dispatch_legs.*', 'shipments.id as shipments_id', 'dispatch_legs.id as dispatch_legs_id',
                    'dispatch_legs.type as dispatch_legs_type', 'dispatch_legs.completed as dispatch_legs_completed', 'terminals.name as terminal')
                ->get();    

            $aResponse['shipment2'] = \DB::connection('mysql-trucking')->table('shipments')
                ->where('shipments.cancelled', 0)
                ->join('dispatches', 'dispatches.shipment_id', '=', 'shipments.id')
                ->join('dispatch_legs', 'dispatch_legs.dispatch_id', '=', 'dispatches.id')
                ->join('terminals', 'terminals.id', '=', 'dispatches.terminal_id')
                ->join('customers', 'customers.id', '=', 'shipments.customer_id')
                ->select('shipments.*', 'dispatch_legs.*', 'shipments.id as shipments_id', 'shipments.trucker_container as ship_trucker_con', 'dispatch_legs.id as dispatch_legs_id',
                    'dispatch_legs.type as dispatch_legs_type', 'dispatch_legs.completed as dispatch_legs_completed', 'terminals.name as terminal')
                ->get();         
            
        }
 
        return $aResponse;

    }
    
    public function getTruckingCustomer($id)
    {
        $customer = Customer::find($id);

        $truckingCustomer = [];
        if(isset($customer->company_key) && !empty($customer->company_key)){
          $truckingCustomer = \DB::connection('mysql-trucking')->table('customers')
                ->where('central_customer_key', $customer->company_key)->get();
        }

        return $truckingCustomer;
        

    }

    public function getShipmentAutomatedTracking($status)
    {

        $shipments = Shipment::where('is_automated_tracking', $status)
                        ->whereNotNull('mbl_num')
                        ->where('type', '!=', 'Air')
                        ->where('cancelled', '!=', 1)
                        ->orderBy('id', 'desc')
                        // ->pluck('id', 'is_automated_tracking', 'automated_request_id')
                        ->get();
        
        $allShipments = [];
        foreach($shipments as $shipment){

            $data = [
                'id' => $shipment->id,
                'is_automated_tracking' => $shipment->is_automated_tracking,
                'automated_request_id' => $shipment->automated_request_id
            ];
            array_push($allShipments, $data);
        }

        $activeShipment = Shipment::whereNotNull('mbl_num')
                        ->where('type', '!=', 'Air')
                        ->where('cancelled', '!=', 1)
                        ->get();                      
    
        $activeShipment = $activeShipment->filter->isShipmentActive()->values();                        

        return response()->json([
            'count' => count($shipments),
            'shipments' => $allShipments,
            'active' => count($activeShipment),
            'active-shipments' => $activeShipment
        ], 200);
    }    
    
}
