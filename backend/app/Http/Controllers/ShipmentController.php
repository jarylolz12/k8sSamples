<?php

namespace App\Http\Controllers;

use App\Coloaders;
use App\Custom\CustomJWTGenerator;
use App\Mail\SendBookingToAgent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Shipment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;
use App\TerminalRegion;
use App\Supplier;
use App\ImportNames;

use App\Events\SendUpdatedBookingEmailEvent;
use App\Events\SendBookingEmailEvent;


use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class ShipmentController extends Controller
{

    private static $token = null;

    function __construct() {
        self::$token = CustomJWTGenerator::generateToken();
    }

    public function saveByDepartureNotice(Request $request)
    {
        $response = ['status' => 'not ok'];
        return response()->json($response);
    }

    public function download(Request $request)
    {

        if ($request->has('link')) {
            $file = urldecode(strval($request->link));
            return Storage::download(
                'public/'.$file
            );
          /* $headers = [
              'Content-type'        => 'application/octet-stream',
              'Content-Disposition' => 'attachment; filename='."$file",
          ];
          return Storage::download(
              'public/'.$file,
              $file,
              $headers
          ); */
          /* return response()->download('public/'.$file, $file, $headers); */
        }
    }
    public function downloadMblCopySurrendered(Request $request, $shipment_id)
    {

        $findShipment = Shipment::find($shipment_id);
        if (isset($findShipment->id)) {
            $checkMblSurrendered = $findShipment->mbl_copy_surrendered;

            if ($checkMblSurrendered!=='') {
                return Storage::download(
                    'public/shipment/mbl_copy_surrendered/'.$findShipment->mbl_copy_surrendered
                );
            }
        }
    }

    public function sendBookingEmail(Request $request)
    {

        $response = ['status' => 'not ok'];
        if ($request->has('shipment_id') && $request->has('is_booking')) {
            $findShipment = Shipment::find($request->input('shipment_id'));

            if (isset($findShipment->id)) {
                if ($request->input('is_booking')==1) {
                    event(new SendBookingEmailEvent($findShipment));
                    $response['status'] = 'ok';
                } else {
                    event(new SendUpdatedBookingEmailEvent($findShipment));
                    $response['status'] = 'ok';
                }
            }
        }

        return response()->json($response);
    }
    private function getOfficeFromOfficeToManagers($shipment)
    {
        $cc = [];
        if (isset($shipment->customer)) {
            if (isset($shipment->customer->offices_managers)) {
                try {
                    $allManagers = json_decode($shipment->customer->offices_managers);

                    if (is_array($allManagers) && count($allManagers) > 0) {

                        foreach ($allManagers as $allManager) {
                            if ($allManager->office_id==$shipment->officeFrom->id || $allManager->office_id==$shipment->officeTo->id) {
                                $setManager = \App\AccountManager::find($allManager->manager_id);

                                if (isset($setManager->id)) {
                                    $cc[] = $setManager->email;
                                }
                            }
                        }
                    }
                } catch (Exception $e) {
                }
            }
        }

        return $cc;
    }
    private function getHblNumber($supplier_group_bookings)
    {
        $hbl_nums  = [];
        foreach ($supplier_group_bookings as $supplier_group_booking) {
            $hbl_nums[] = $supplier_group_booking['hbl_num'];
        }

        $last  = array_slice($hbl_nums, -1);
        $first = join(', ', array_slice($hbl_nums, 0, -1));
        $both  = array_filter(array_merge(array($first), $last), 'strlen');

        return trim(join(' and ', $both)) != "" ? join(' and ', $both) : 'Not specified';
    }

    public function getAgentBookingEmail($agentId){
        $agentData = Coloaders::find($agentId);
        if($agentData){
            if($agentData->email){
                return response()->json([ 'data' => $agentData->email, 'emails' => $agentData->emails, 'status' => 200], 200);
            }
            return response()->json([ 'data' => 'Agent have not valid email', 'status' => 202], 202);
        }
        return response()->json([ 'data' => 'Agent Not Found', 'status' => 204], 204);
    }

    /**
     * Tests, if the given $value parameter is a JSON string.
     * When it is a valid JSON value, the decoded value is returned.
     * When the value is no JSON value (i.e. it was decoded already), then
     * the original value is returned.
     */
    function getData( $value, $as_object = false ) {
        if ( is_numeric( $value ) ) { return 0 + $value; }
        if ( ! is_string( $value ) ) { return $value; }
        if ( strlen( $value ) < 2 ) { return $value; }
        if ( 'null' === $value ) { return null; }
        if ( 'true' === $value ) { return true; }
        if ( 'false' === $value ) { return false; }
        if ( '{' != $value[0] && '[' != $value[0] && '"' != $value[0] ) { return $value; }

        $json_data = json_decode( $value, $as_object );
        if ( is_null( $json_data ) ) { return []; }
        return $json_data;
    }

    public function sendBookingToAgent(Request $request)
    {
        try {
            $schedules = json_decode($request['item'], true);
            $shipment  = Shipment::find($schedules['shipment_id']);

            $to        = [];
            $cc        = [];

            $cc[] = 'vishal@shifl.com';
            $cc[] = 'bhavin@shifl.com';

            if ($schedules['agentEmail']) {
                $agentEmail = $schedules['agentEmail'];
                $supplier_group_bookings = json_decode($shipment->suppliers_group_bookings, true);

                foreach ($schedules['items']['legs'] as $key => $leg) {
                    if ($key==0) {
                        $schedules['first_leg_location_to'] = $leg['location_to_name'];
                        $schedules['eta_of_first_leg']      = $leg['eta'];
                    }
                    if ($key>0 && $key+1 == sizeof($schedules['items']['legs'])) {
                        $schedules['last_leg_location_to'] = $leg['location_to_name'];
                    }
                }

                $filtered_collection = [];
                if ($schedules['items']['buy_rates']) {
                    if(is_array($schedules['items']['buy_rates'])){
                        $schedulesContainers = collect($schedules['items']['buy_rates']);
                        $filtered_collection = $schedulesContainers->filter(function ($item) {
                            return $item['service_name'] == 'Freight (port to port)';
                        })->all();
                    }
                    $schedules['buy_rates'] = $schedules['items']['buy_rates'][0];
                    if ($schedules['buy_rates']['service_name'] == 'Freight (port to port)') {
                        $schedules['buy_rates'] = $schedules['items']['buy_rates'][0]['amount'];
                    }
                }

                $supplierDataCollection = collect($schedules['suppliers_group']);
                $modifiedSupplierData = $supplierDataCollection->map(function ($item, $key) {
                    if(isset($item['supplier_id'])){
                        $singleSupplier = Supplier::find($item['supplier_id']);
                        if($singleSupplier != null) {
                            $item['supplier_address'] = $singleSupplier->address;

                            $supplierEmails = $singleSupplier->emails;
                            if (!is_array($singleSupplier->emails)) {
                                $supplierEmails = $this->getData($singleSupplier->emails);
                            }
                            $supplierEmailsDisplay = '';
                            if (is_array($supplierEmails)) {
                                foreach ($supplierEmails as $supplierEmail) {
                                    if(gettype($supplierEmail) == 'string'){
                                        $supplierEmailsDisplay = $supplierEmailsDisplay . ' ' . $supplierEmail . ",";
                                    }
                                }
                            }
                            $item['supplier_email'] = $supplierEmailsDisplay;
                        }
                    }
                    return $item;
                });

                $grouped_array = array();
                foreach ($filtered_collection as $element) {
                    $grouped_array[$element['container_size_name']][] = $element;
                }
                foreach ($grouped_array as $key => $grouped_array_row) {
                    $containerBysize[$key]['name'] = $key;
                    $containerBysize[$key]['total'] = collect($filtered_collection)->filter(function ($item) use($key) {
                        return $item['container_size_name'] == $key;
                    })->reduce(function($carry, $item){
                        return $carry + $item["qty"];
                    }, 0);
                }

                $content['suppliers_group'] = $this->getData($modifiedSupplierData->all());
                $content['container_sizes'] = $filtered_collection;
                $content['container_group'] = $containerBysize;
                $content['agent_booking_sent'] = $schedules['agent_booking_sent'];
                $content['hbl_num']   = $this->getHblNumber($supplier_group_bookings);
                $content['buy_rates'] = $schedules['buy_rates']??'Not specified';
                $content['shipment']  = $shipment;
                $content["schedules"] = $schedules;
                $content["officeFrom"] = (isset($shipment->officeFrom)) ? $shipment->officeFrom : '';
                $content["officeTo"] = (isset($shipment->officeTo)) ? $shipment->officeTo : '';
                $content["account_representative_phone"] = (isset($shipment->customer)) ? (isset($shipment->customer->manager)) ? $shipment->customer->manager->phone : '' : '';
                $content["account_representative_email"] = (isset($shipment->customer)) ? (isset($shipment->customer->manager)) ? $shipment->customer->manager->email : 'No match' : 'No match';
                $content["account_representative_name"]  = (isset($shipment->customer)) ? (isset($shipment->customer->manager)) ? $shipment->customer->manager->name : 'No match' : 'No match';
                if ($request->hasFile('file')) {
                    $content['files'] = $request->file('file');
                }
                if(isset($content["schedules"]['items']['legs']) && is_array($content["schedules"]['items']['legs']) && count($content["schedules"]['items']['legs']) > 0){
                    $content["port_of_discharge"] = collect($content["schedules"]['items']['legs'])->first()['location_from_name'];
                    $content["destination"] = collect($content["schedules"]['items']['legs'])->last()['location_to_name'];
                } else {
                    $content["port_of_discharge"] = $content["schedules"]['items']['location_to_name'];
                    $content["destination"] = $content["schedules"]['items']['location_to_name'];
                }

                $to[] = $agentEmail;
                $cc[] = auth()->user()->email;
                foreach ($schedules['shiflOfficeFromDisplay'] as $representative) {
                    $cc[] = $representative;
                }

                foreach ($schedules['emails'] as $email) {
                    $cc[] = $email;
                }

                foreach ($schedules['ccEmails'] as $email) {
                    $cc[] = $email;
                }

                $extra_ccs = [];

                if ( isset($shipment->customer->agent_booking_updated_emails) ) {
                    $dynamic_emails = $shipment->customer->agent_booking_updated_emails;
                    
                    if ( $dynamic_emails !=null ) {

                        $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                        try {
                            foreach($dynamic_emails as $dynamic_email ) {
                                if ( !in_array($dynamic_email, $cc)) {
                                    array_push($cc, $dynamic_email);
                                    //array_push($extra_ccs, $dynamic_email);
                                }
                            }
                        } catch(Exception $e ) {

                        }
                    }
                }

                $check_office_to = \App\ShiflOfficeEmail::where('type', 'to')
                                                ->where('office_id', $shipment->officeTo->id)
                                                ->first();

                $check_office_from = \App\ShiflOfficeEmail::where('type', 'from')
                                                    ->where('office_id', $shipment->officeFrom->id)
                                                    ->first();

                if ( isset($check_office_to->id) ) {

                    $dynamic_emails = $check_office_to->agent_booking_updated_emails;
                    if ( $dynamic_emails !=null ) {

                        $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                        $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;

                        foreach($dynamic_emails as $dynamic_email ) {
                            if ( !in_array($dynamic_email, $cc)) {
                                array_push($cc, $dynamic_email);
                                //array_push($extra_ccs, $dynamic_email);
                            }
                        }
                    }
                }

                if ( isset($check_office_from->id) ) {
                    
                    $dynamic_emails = $check_office_from->agent_booking_updated_emails;
                    if ( $dynamic_emails !=null ) {

                        $dynamic_emails = ( is_array($dynamic_emails)) ? $dynamic_emails : json_decode($dynamic_emails);
                        $dynamic_emails = ($dynamic_emails == '') ? [] : $dynamic_emails;

                        foreach($dynamic_emails as $dynamic_email ) {
                            if ( !in_array($dynamic_email, $cc)) {
                                array_push($cc, $dynamic_email);
                                //array_push($extra_ccs, $dynamic_email);
                            }
                        }
                    }
                }


                Mail::to($to)->cc($cc)->send(new SendBookingToAgent($content));

                $shipment_schedules = json_decode($shipment->schedules_group_bookings, true);
                $shipment_schedules[$schedules['itemIndex']]['agent_booking_sent'] = true;
                $shipment_schedules[$schedules['itemIndex']]['agent_booking_sent_at'] = Carbon::now();
                $shipment->schedules_group_bookings = json_encode($shipment_schedules);
                $shipment->save();

                return response()->json('Success', 200);
            } else {
                return response()->json('Failed no agent assigned!', 422, [], JSON_PRETTY_PRINT);
            }
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 422, [], JSON_PRETTY_PRINT);
        }
    }

    public function findById(Request $request)
    {

        $response = ['status' => 'not ok', 'result' => null];
        $findShipment = Shipment::find($request->input('id'));

        if (isset($findShipment->id)) {
            $response['status'] = 'ok';
            $response['result'] = $findShipment;
        }

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage. Only for Header Info Tab
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateHeaderInfo(Request $request, $id)
    {
        $shipment = Shipment::find($id);
        if (!$shipment) {
            throw new NotFoundHttpException;
        }

        if($request->cancelled == 1)
        {
            try {
                $client = new \GuzzleHttp\Client([
                    'base_uri' => env('PO_URL'),
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept'     => 'application/json',
                        'Authorization'  => 'Bearer ' . self::$token,
                    ]
                ]);
                $client->delete('/api/po/shipments/'. $id .'/orders');
            } catch(Exception $e) {

            }

        }

        $shipment->fill($request->all());
        $shipment->customer()->associate($request->input('custom_customer'));

        if ($request->has('mbl_copy_surrendered_remove')) {
            $check_value = $request->input('mbl_copy_surrendered_remove');

            if ($check_value==1 && $shipment->mbl_copy_surrendered!=='' && $shipment->mbl_copy_surrendered!==null) {
                if (Storage::exists('public/shipment/mbl_copy_surrendered/'.$shipment->mbl_copy_surrendered)) {
                    Storage::delete('public/shipment/mbl_copy_surrendered/'.$shipment->mbl_copy_surrendered);
                }

                $shipment->mbl_copy_surrendered = '';
            }
        }

        if ($request->has('mbl_copy_surrendered_file')) {
            if (!is_null($file = $request->file('mbl_copy_surrendered_file')) && $file->isValid()) {
                $hash_file = md5($request->input('mbl_copy_surrendered_name') . now());
                $final_display_name = $hash_file . '.' . $request->file('mbl_copy_surrendered_file')->guessExtension();
                $final_name = 'shipment/mbl_copy_surrendered/'.$final_display_name;
                Storage::disk('local')->putFileAs('/public', $request->file('mbl_copy_surrendered_file'), $final_name);

                $shipment->mbl_copy_surrendered = $final_display_name;
            }
        }

        $shipment->mbl_released_confirmed = ($shipment->mbl_copy_surrendered=='' || $shipment->mbl_copy_surrendered==null) ? 0 : 1;

        $importName = new ImportNames();
        if($request->has('custom_customer') && $request->has('import_name_id')){
            //check if import name is for that customer
            $import = $importName->getImportNameByCustomerId($request->custom_customer)
                ->where('id', $request->import_name_id)->get();
            if(count($import) == 0 ){
                $shipment->import_name_id = "";

            }

        }

        if($request->has('custom_customer') && !isset($request->import_name_id) || $request->import_name_id == 0){
            $import = $importName->getImportNameByCustomerId($request->custom_customer)->get();
            if(count($import) > 0){
                return response()->json([
                    'status' => 'Import name is required',
                    'shipment_id' => $id
                ], 400);
            }

        }


        if ($shipment->isDirty()) {
            $shipment->noSync = true;
            $shipment->save();

            if (!$shipment->getChanges()) {
                //return $this->response->error('could_not_update_shipment', 500);
                return response()->json([
                    'status' => 'could not update shipment',
                    'shipment_id' => $id
                ], 500);
            } else {
                return response()->json([
                    'status' => 'update successful',
                    'shipment_id' => $id
                ], 201);
                // return $this->response->noContent();
            }
        } else {
            return response()->json([
                'status' => 'no changes made',
                'shipment_id' => $id
            ], 201);
            // return $this->response->error('no changes made', 500);
        }
    }

    public function getReferenceNumber($shipment_id)
    {

        $response = ['status' => 'failed', 'result' => null];

        $getRef = Shipment::where('id', $shipment_id)->select('shifl_ref')->get();

        if ($getRef !== '') {
            $response['status'] = 'success';
            $response['result'] = $getRef;
        }

        return response()->json($response);
    }

    public function getShipmentDataForInvoice(Request $request)
    {
        $response = ['status' => 'fail', 'result' => null];
        $shipment = Shipment::where('id', $request->input('id'))->with(['containers'])->get();

        $getContainers = json_decode($shipment[0]->containers);
        $containers = [];
        if ($getContainers) {
            $containers = collect($getContainers)->pluck('container_num');
        }

        $getSuppliersBooking = json_decode($shipment[0]->suppliers_group_bookings);
        $suppliers = [];
        $po_nums = [];
        if ($getSuppliersBooking) {
            $po_nums = collect($getSuppliersBooking)->pluck('po_num');

            foreach ($getSuppliersBooking as $sup) {
                $getSupplier = isset($sup->supplier_id) ? Supplier::where('id', $sup->supplier_id)->select('company_name')->first() : '';
                $setSupplierData = [
                    'name' => isset($getSupplier->company_name) ? $getSupplier->company_name : '',
                    'po_num' => isset($sup->po_num) ? $sup->po_num : '',
                    'cartons' => isset($sup->total_cartons) ? $sup->total_cartons : 0,
                ];
                array_push($suppliers, $setSupplierData);
            }
        }

        $scheduleBooking = json_decode($shipment[0]->schedules_group_bookings);

        if ($shipment) {
            $response['status'] = 'success';
            $response['result'] = [
                'shipment_id' => $shipment[0]->id,
                'mbl_num' => $shipment[0]->mbl_num,
                'container_numbers' => $containers,
                'po_numbers' => $po_nums,
                'etd' => $shipment[0]->etd,
                'eta' => $shipment[0]->eta,
                'location_from' => isset($scheduleBooking[0]->location_from) && $scheduleBooking[0]->location_from !== '' ? TerminalRegion::findOrFail($scheduleBooking[0]->location_from)->name : '',
                'location_to' => isset($scheduleBooking[0]->location_to) && $scheduleBooking[0]->location_to !== '' ? TerminalRegion::findOrFail($scheduleBooking[0]->location_to)->name : '',
                'suppliers' => $suppliers,
            ];
        }

        return response()->json($response);
    }

    public function projected_profit(Request $request)
    {
        $sh = Shipment::select('schedules_group_bookings')->where('id', $request->shipmentId);

        return response()->json([ 'success' => true, 'message' => ( $sh->exists() ? $sh->first()->projected_profit : '' ) ]);
    }

    public function updateRateConfirmed(){
        Shipment::where('etd', '<', Carbon::now()->toDateTimeString())->update(['rate_confirmed' => 1]);
    }
}
