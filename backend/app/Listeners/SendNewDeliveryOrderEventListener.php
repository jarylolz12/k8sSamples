<?php

namespace App\Listeners;

use App\Events\SendNewDeliveryOrderEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\ShipmentLeave;

use PDF;
use App;
use Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Controllers\API\UserController;
use App\Terminal49Shipment;
use App\Mail\SimpleString;
use App\Trucker;
use App\Carrier;
use App\Terminal;
use App\CustomTruckingDO;
use App\Customer;

class SendNewDeliveryOrderEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Handle the event.
     *
     * @param  SendDeliveryOrderEvent  $event
     * @return void
     */
    public function handle(SendNewDeliveryOrderEvent $event)
    {
        $delivery_order_objectConnect = \DB::connection('mysql-trucking');
        $shipment = $event->resource; 
               
        /*
        [id] => 18540
        [po_num] => 
        [mbl_num] => COSU6346016790
        [type] => 
        [term] => 
        [customer_id] => 481
        [shipment_status] => Approved
        [shifl_ref] => 718540
        [origin_country] => 
        [destination_country] => 
        [etd] => 2022-10-30T00:00:00.000000Z
        [eta] => 2022-12-17T00:00:00.000000Z
        [custom_notes] => 
        [total_cbm] => 
        [total_kg] => 
        [total_cartons] => 
        [created_at] => 2022-09-20T07:44:57.000000Z
        [updated_at] => 2022-10-17T15:41:45.000000Z
        [carrier_id] => 
        [custom_content] => 
        [suppliers_group] => [{"id":1663658252750,"hbl_copy":null,"packing_list":null,"commercial_documents":null,"commercial_invoice":null,"po_id":"","po_num":"4500920865","volume":"","supplier_id":1926,"buyer_id":null,"cargo_ready_date":"2022-09-29","kg":"6412.5","cbm":"65.64","incoterm_id":2,"hbl_num":"","product_description":"LUGGAGE","total_cartons":"475","bl_status":"Pending","ams_num":"","containers":[],"customerHasPOs":false}]
        [schedules_group] => []
        [containers_group] => []
        [mbl_copy] => 
        [debit_note] => 
        [vessel] => COSCO SHANGHAI
        [voyage_number] => V.197N
        [booking_confirmed] => 1
        [arrival_notice_confirmed] => 0
        [freight_released_processed] => 0
        [customs_processed] => 0
        [DO_confirmed] => 0
        [freight_confirmed] => 0
        [customs_released] => 0
        [pending_refund] => 0
        [delivered] => 0
        [billed] => 0
        [cancelled] => 0
        [shipment_left] => 0
        [terminal_id] => 
        [arrival_notice] => 0
        [cloned_from] => 18539
        [trucker_id] => 283
        [trucker_custom_note] => test
        [delivery_order_left] => 0
        [copy_customer] => 1
        [memo_customer] => Pls check for the new booking and confirm which option approved, thanks
        [suppliers_group_bookings] => [{"id":1663658252750,"hbl_copy":null,"packing_list":null,"commercial_documents":null,"commercial_invoice":null,"po_id":"","po_num":"4500920865","volume":"","supplier_id":1926,"buyer_id":null,"cargo_ready_date":"2022-09-29","kg":"6412.5","cbm":"65.64","incoterm_id":2,"hbl_num":"","product_description":"LUGGAGE","total_cartons":"475","bl_status":"Pending","ams_num":"","containers":[],"customerHasPOs":false}]
        [containers_group_bookings] => []
        [schedules_group_bookings] => [{"id":1663658211839,"eta":"2022-12-17 09:07:48","etd":"2022-10-30 09:07:48","etb":"","cutoff":"","location_from":136,"location_to":1,"mode":"Ocean","legs":[],"type":"FCL","carrier_name":{"id":1},"size_id":null,"price":null,"selling_size_id":null,"selling_price":null,"sell_rates":[{"id":1665383223661,"shipment_schedules_id":1663658211839,"service_id":1,"amount":"6600","qty":"1","container_size_id":3,"valid_from":null,"valid_to":"2022-10-31","container_size_name":"40'HQ","service_name":"Freight (port to port)"},{"id":1665383224557,"shipment_schedules_id":1663658211839,"service_id":2,"amount":"150","qty":"1","container_size_id":5,"valid_from":null,"valid_to":null,"container_size_name":"Shipment","service_name":"ISF \/ Customs Clearance"}],"buy_rates":[{"id":1665383213793,"shipment_schedules_id":1663658211839,"service_id":1,"amount":"6250","qty":"1","container_size_id":3,"valid_from":null,"valid_to":"2022-10-31","container_size_name":"40'HQ","service_name":"Freight (port to port)"},{"id":1665383214433,"shipment_schedules_id":1663658211839,"service_id":22,"amount":"50","qty":"1","container_size_id":3,"valid_from":null,"valid_to":null,"container_size_name":"40'HQ","service_name":"Handling Charge at destination."}],"is_confirmed":1,"vendor_id":0,"etaError":{},"etdError":{},"agent_booking_notes":"","agent_booking_sent":false,"size_name":"","selling_size_name":"","agent_id":24,"vendor_name":"","agent_name":"\u4e50\u4ed5\u661f","carrier_name_label":"Cosco","location_to_name":"NY\/NJ","location_from_name":"Laem Chabang"}]
        [mbl_shipper] => 
        [mbl_shipper_address] => 
        [mbl_shipper_phone_number] => 
        [mbl_consignee] => 
        [mbl_consignee_address] => 
        [mbl_consignee_phone_number] => 
        [mbl_notify] => 
        [mbl_notify_address] => 
        [mbl_notify_phone_number] => 
        [mbl_description] => 
        [mbl_marks] => 
        [booking_confirmed_at] => 2022-10-10 07:56:30
        [tracking_request_id] => d3a5b72f-2c68-47a7-be50-16e30698912b
        [entry_netchb_submitted] => 0
        [entry_netchb_no] => 
        [entry_netchb_date] => 
        [entry_data] => 
        [do_sent_at] => 
        [an_sent_at] => 
        [retry_tracking_request] => 3
        [suppliers_commercial_docs] => 
        [services_section] => {"isf": 0, "customs": 0, "trucking": 0, "pier_pass": 0, "duty_layout": 0, "freight_forwarding": 0}
        [netchb_pdf] => 
        [netchb_xml] => 
        [shifl_office_origin_from_id] => 2
        [shifl_office_origin_to_id] => 1
        [rate_confirmed] => 0
        [so_released] => 1
        [released_at_terminal] => 0
        [isf_done] => 0
        [ams_done] => 0
        [is_tracking_shipment] => 0
        [containers_group_untracked] => 
        [untracked_tool_last_updated_at] => 
        [carrier_arrival_notice_eta] => 
        [carrier_arrival_notice_firms] => 
        [tracked_up_to] => 2022-10-24 05:00:00
        [customs_sent] => 0
        [customs_sent_at] => 
        [mbl_released_confirmed] => 0
        [mbl_copy_surrendered] => 
        [is_schedule_requested] => 0
        [is_booking_email_sent] => 0
        [is_agent_booking_confirm] => 0
        [snooze_date_at] => 
        [customs_broker_id] => 
        [booking_number] => 6346016790
        [import_name_id] => 0
        [untracked_tool_last_updated_by] => 535
        [not_tracking_manually] => 0
        [so_received] => 0
        [untracked_tool_last_tracked_at] => 2022-10-17 11:41:45
        [gate_full_in] => 0
        [mt_expected_to_be_addressed] => 
        [projected_profit] => 450.00
        [profitt] => 0
        [sort_score] => 0
        */    

        // check the trucker if it has trucking_company_key 
        // if it does, then it is connected to the trucking Backend Trucker Resource.
        $customer_trucker_resource = Trucker::where('id',$shipment->trucker_id)->where('trucking_company_key','<>','');
        
        // Check if it has Mbl #
        // we dont allow empty MBL it is required.
        if( !empty($shipment->mbl_num))
        {
            if($customer_trucker_resource->count())
            {               
                // Check if the do is existing in Trucking Platform by central_shipment_id      
                $get_central_shipment_id_in_trucking_be = CustomTruckingDO::where('central_shipment_id',$shipment->id);
                $customer_trucker_resource = $customer_trucker_resource->first();  

                $has_do_in_trucking_be = $delivery_order_objectConnect->table('truckers')->where('trucking_company_key',$customer_trucker_resource->trucking_company_key);
                
                // If Zero no Do yet in Trucking
                if($has_do_in_trucking_be->count())
                {
                    $delivery_order_object = ( $get_central_shipment_id_in_trucking_be->count() == 0 ) ? new CustomTruckingDO() : $get_central_shipment_id_in_trucking_be->first();
                    
                    // $shipment->id we use this to connect to trucking delivery order column central_shipment_id, 
                    // if we send and is already in trucking DB then we update
                    $delivery_order_object->central_shipment_id = $shipment->id;

                    $delivery_order_object->mbl_number    = $shipment->mbl_num;
                    $delivery_order_object->eta           = date('Y-m-d', strtotime($shipment->mbl_num));
                    $delivery_order_object->vessel        = $shipment->vessel;
                    $delivery_order_object->trucker       = $has_do_in_trucking_be->first()->id;   // trucking owner in trucking platform                   
                    $delivery_order_object->carrier       = !empty($shipment->carrier_id) ? Carrier::where('id',$shipment->carrier_id)->first()->name : '';
                    $delivery_order_object->terminal_name = !empty($shipment->terminal_id)? Terminal::where('id',$shipment->terminal_id)->first()->name : '';
                    $delivery_order_object->customer_ref_number = $shipment->po_num;
                    $delivery_order_object->containers_group_bookings = !empty( $shipment->containers_group_bookings && $shipment->containers_group_bookings !='[]') ? $shipment->containers_group_bookings : null;               
                    $delivery_order_object->description   = $shipment->trucker_custom_note;
                    $delivery_order_object->customer_name = !empty($shipment->customer_id) ? Customer::where('id',$shipment->customer_id)->first()->name : '';
                    $delivery_order_object->save();     // save Delivery order with Default Pending
                    return 'Successfully Saved.';
                }else{
                    throw new \Exception("We cannot send the Delivery Order because the Trucking Company Key does not exist.");
                }
            }else{
                throw new \Exception('We cannot send the Delivery Order because the Trucking Company Key does not exist');
            } 
        }else{
            throw new \Exception('Unable to send the Delivery Order because it has no MBL#.');
        }      
    }
}
