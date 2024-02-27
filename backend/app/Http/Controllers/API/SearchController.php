<?php

namespace App\Http\Controllers\API;

use App\Buyer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Http\Resources\Customer as CustomerResource;
use Illuminate\Support\Collection;
use App\Http\Resources\Shipment as ShipmentResource;
use App\Http\Resources\Standard as StandardResource;
use App\Shipment;
use App\TerminalRegion;
use App\Supplier;
/**
 *
 * @authenticated
 *
 * @group Search
 *
 * APIs to manage the Search resource
*/
class SearchController extends Controller
{
    // return searched customers


    public function searchThroughCustomers($request)
    {
        $customers = [];
        $request->search_text = '%'. trim($request->search_text) .'%';
        if ($request->search_text == '%%') {
            $customers = array_merge($customers, Auth::user()->customersApi->toArray());
        } else {
            $customers = array_merge($customers, Auth::user()->customersApi->filter(function ($customer) use ($request) {
                $search_text = str_replace('%', '.*', preg_quote($request->search_text, '/'));
                return (bool) preg_match("/^{$search_text}$/i", $customer->company_name);
            })->toArray());
        }
        return $customers;
    }
    // return searched shipments
    public function searchThroughShipments($request)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();
        $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        $shipments = [];
        $search_text = $request->search_text;
        $search_texts = explode(' ', $search_text);
        $request->search_text = '%'.$request->search_text.'%';


        if (count($customers) > 0) {
            $buyerIds = Buyer::where('connected_customer', $customers[0])->pluck('id');
            $shipperIds = Supplier::where('connected_customer', $customers[0])->pluck('id');

            $shipments = Shipment::where(function($q) use ($customers, $buyerIds, $shipperIds) {
                $q->whereHas('customer', function($qq) use ($customers){
                    $qq->whereIn('id', $customers)
                        ->where(function($q) {
                            $q->where('is_draft', 0);
                            $q->orWhereNull('is_draft');
                        });
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($buyerIds) {
                    $qq->whereIn('buyer_id', $buyerIds)
                        ->where(function($q) {
                            $q->where('is_draft', 0);
                            $q->orWhereNull('is_draft');
                        });
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($shipperIds) {
                    $qq->whereIn('supplier_id', $shipperIds)
                        ->where(function($q) {
                            $q->where('is_draft', 0);
                            $q->orWhereNull('is_draft');
                        });
                });
            })
            ->orWhere(function ($qq) use($customers) {
                $qq->where('is_draft', 1);
                $qq->whereIn('created_by', $customers);
            })
            ->get();
        }


        $new_shipments = [];
        if ( count ($shipments) > 0) {
            foreach ($shipments as $key => $shipment ) {

                $hasMatch = false;
                $search_text = str_replace('%', '.*', preg_quote($request->search_text, '/'));
                if ( $this->match ($shipment, $search_text) ) {
                    array_push($new_shipments, $shipment);
                    $hasMatch = true;
                }

                if ( !$hasMatch && count($search_texts) > 0 ) {
                    foreach($search_texts as $st) {
                        $search_text = str_replace('%', '.*', preg_quote('%'.$st.'%', '/'));
                        if ( $this->match ($shipment, $search_text) && !$hasMatch) {
                            array_push($new_shipments, $shipment);
                            $hasMatch = true;
                        }
                    }
                }
                /*
                $search_text = str_replace('%', '.*', preg_quote($request->search_text, '/'));
                if ( $this->match ($shipment, $search_text) ) {
                    array_push($new_shipments, $shipment);
                }*/
            }
        }
        return $this->modify($new_shipments);
        //return $this->modify($shipments);
    }

    /**
     *
     * Display specific customers search
     *
     * @bodyParam search_text string required The customers COMPANY_NAME
     *
     * @apiResource 201 App\Http\Resources\Standard
     * @apiResourceModel App\User with=customersApi
     *
     * @response status=400 {
     *    "message": "Unauthenticated",
     * }
     *
     */
    public function customers(Request $request)
    {

        $customers = $this->searchThroughCustomers($request);
        return StandardResource::collection((new Collection($customers))->paginate(5));

        // search implement using db joins
        // return
        //     CustomerResource::collection(
        //         (new Collection(
        //             \DB::Table('customer_admins')
        //                 ->where('user_id', Auth::id())
        //                 ->join('customers', 'customer_admins.customer_id', '=', 'customers.id')
        //                 ->where('company_name', 'like', '%'.$request->search_text.'%')
        //                 ->select('customers.id', 'company_name', 'address', 'phone', 'created_at', 'updated_at', 'managers', 'sale_persons')
        //                 ->get()
        //                 )
        //             )
        //         )->paginate(5);
    }

    /**
     *
     * Display specific shipments search
     *
     * @bodyParam search_text string required
     *
     * @apiResource 201 App\Http\Resources\Standard
     * @apiResourceModel App\Shipment
     *
     * @response status=400 {
     *    "message": "Unauthenticated",
     * }
     *
     */
    public function shipments(Request $request)
    {
        $shipments = $this->searchThroughShipments($request);
        return StandardResource::collection((new Collection($shipments))->paginate(5));

        // search implement using db joins
        // return
        //     ShipmentResource::collection(
        //         (new Collection(
        //             \DB::Table('customer_admins')
        //                 ->where('user_id', Auth::id())
        //                 ->join('customers', 'customer_admins.customer_id', '=', 'customers.id')
        //                 ->join('shipments', 'shipments.customer_id', '=', 'customers.id')
        //                 ->where('shipment_status', 'like', '%'.$request->search_text.'%')
        //                 ->orWhere('shifl_ref', 'like', '%'.$request->search_text.'%')
        //                 ->select('shipments.id', 'po_num', 'mbl_num', 'type', 'term', 'shipment_status', 'shifl_ref', 'etd', 'eta', 'custom_notes', 'total_cbm', 'total_kg', 'total_cartons', 'suppliers_group', 'shipments.created_at', 'shipments.updated_at')
        //                 ->get()
        //                 )
        //             )
        //         )->paginate(5);
    }
    /**
     *
     * Display specific search
     *
     * @bodyParam search_text string required
     *
     * @apiResource 201 App\Http\Resources\Standard
     * @apiResourceModel App\User with=customersApi
     *
     * @response status=400 {
     *    "message": "Unauthenticated",
     * }
     *
     */
    public function index(Request $request)
    {
        $shipments = $this->searchThroughShipments($request);
        //$customers = $this->searchThroughCustomers($request);
        $arrData = array('shipments' => $shipments);//, 'customers' => $customers
        //$search_result = array_merge($shipments, $customers);
        //shuffle($search_result);
        return StandardResource::collection($arrData);
    }

    // add necessary extra data
    private function modify($shipments)
    {
        $processSchedulesGroupBookings = new \App\Custom\ProcessSchedulesGroupBookings();

        return collect($shipments)->map(function ($shipment) use ($processSchedulesGroupBookings) {
            if (!empty($shipment['schedules_group_bookings'])) {
                // if shipment schedules_group_bookings field has value
                $schedule_bookings = $processSchedulesGroupBookings->getSchedule($shipment['schedules_group_bookings']);
                if ($schedule_bookings) {
                    if (!empty($schedule_bookings->location_from)) {
                        $getTerminal = TerminalRegion::find($schedule_bookings->location_from);
                        if ($getTerminal) {
                            $shipment['location_from_name'] = $getTerminal->name;
                        }
                    }
                    if (!empty($schedule_bookings->location_to)) {
                        $getTerminal = TerminalRegion::find($schedule_bookings->location_to);
                        if ($getTerminal) {
                            $shipment['location_to_name'] = $getTerminal->name;
                        }
                    }
                    return $shipment;
                }
            }
            $schedules = json_decode($shipment['schedules_group']);
            if (!empty($schedules)) {
                if (isset($schedules[0]->location_from)) {
                    $getTerminal = TerminalRegion::find($schedules[0]->location_from);
                    if ($getTerminal) {
                        $shipment['location_from_name'] = $getTerminal->name;
                    }
                }
                if (isset($schedules[0]->location_to)) {
                    $getTerminal = TerminalRegion::find($schedules[0]->location_to);
                    if ($getTerminal) {
                        $shipment['location_to_name'] = $getTerminal->name;
                    }
                }
            }
            return $shipment;
        })->toArray();
    }

    /*
      search through
          Factory name (Supplier Name)
          Container#
          Mbl#
          Ams#
          Hbl#
          Po#
    */

    private function match($shipment, $search_text)
    {

        $terminal_fortynine = $shipment->terminal_fortynine;
        $check_external_containers = (isset($terminal_fortynine) && isset($terminal_fortynine->containers)) ? json_decode($terminal_fortynine->containers) : [];

        if ( count($check_external_containers) > 0) {
            if ( count($check_external_containers)>0 ) {
                foreach( $check_external_containers as $container ) {

                    if ( (bool) preg_match("/^{$search_text}$/i", $container->attributes->number)) {
                        return true;
                    }
                    /*
                    if ( strpos(strval(strtolower($container->attributes->number)),strval(strtolower($search_text)))!==false ){
                        return true;
                    }*/
                }
            }
        }

        // search through container
        $containers_group_bookings = json_decode($shipment->containers_group_bookings);

        try {
            foreach ($containers_group_bookings ?? [] as $container) {
                // code...
                $container_matched = (bool) preg_match("/^{$search_text}$/i", $container->container_num);
                if ($container_matched) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        // search through supplier

        $suppliers_group_bookings = json_decode($shipment->suppliers_group_bookings);
        try {
            foreach ($suppliers_group_bookings ?? []  as $supplier) {
                // code...

                $supplier_matched =    (bool) preg_match("/^{$search_text}$/i", $supplier->ams_num) // search through ams#
                                   ||  (bool) preg_match("/^{$search_text}$/i", $supplier->hbl_num) // search through hbl#
                                   ||  (bool) preg_match("/^{$search_text}$/i", $supplier->po_num); // search through po#
                if ($supplier_matched) {
                    return true;
                }

                //
                if (isset($supplier->supplier_id)) {
                    $shipmentSupplier = Supplier::find($supplier->supplier_id);
                    if ($shipmentSupplier) {
                        $supplier_matched = (bool) preg_match("/^{$search_text}$/i", $shipmentSupplier->company_name);
                        if ($supplier_matched) {
                            return true;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            return false;
        }

        //
        return     (bool) preg_match("/^{$search_text}$/i", $shipment->getStatus()) // search through shipment status
                || (bool) preg_match("/^{$search_text}$/i", $shipment->shifl_ref) // search through reference number
                || (bool) (bool) preg_match("/^{$search_text}$/i", $shipment->mbl_num); // search through mbl number
    }
}
