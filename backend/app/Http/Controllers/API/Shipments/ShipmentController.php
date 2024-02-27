<?php

/**
 * @author Kenneth
 */
namespace App\Http\Controllers\API\Shipments;
use App\Customer;
use App\CustomerBuyer;
use App\CustomerSupplier;
use App\Incoterm;
use App\Http\Controllers\Controller;
use Dompdf\Exception;
use App\Events\SendForwarderEmailEvent;
use App\Events\SendForwarderEmailAdminEvent;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Standard as StandardResource;
use App\Shipment;
use App\User;
use Illuminate\Support\Collection;
use App\TerminalRegion;
use App\Supplier;
use App\Buyer;
use App\Carrier;
use App\Document;
use App\ShipmentMeta;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Events\SendNewBookingEmailEvent;
use App\Events\InsertDocumentEvent;
use App\Terminal49Shipment;
use App\Custom\CustomJWTGenerator;
use App\Rules\CheckIfOwnCustomer;
use App\Rules\CheckShipmentByReference;
use App\Rules\ValidateShipment;
use App\Custom\Traits\HelperMethods;
use Illuminate\Validation\Rule;
use App\Mail\ForwarderMail;



/**
 * @authenticated
 *
 * @group Shipment
 *
 * APIs to manage the Shipment resource
 *
 */
class ShipmentController extends ShipmentBaseController
{

    public function editShipmentBak(Request $request)
    {
        $response = ['status' => 'not ok'];
        $validator = Validator::make($request->all(), [
            'eta' => 'required',
            'etd' => 'required',
            'mbl_num' => 'required',
            'date_id' => 'required',
            'id' => ['required', new ValidateShipment],
        ]);

        if ( $validator->fails() ) {
            $response['errors'] = $validator->errors();
            return response()->json($response, 401);
        } else {
            $args = $request->all();
            $checkShipment = Shipment::find($args['id']);
            $schedules = $this->merge_group($checkShipment->schedules_group_bookings, $checkShipment->schedules_group);
            $first_schedule = null;
            if ( is_countable($schedules) && count($schedules) > 0 ) {
                $first_schedule =  $schedules[0];
            }

            $newSchedules = [];

            array_push($newSchedules, [
                'id' => $first_schedule!==null ? $first_schedule['id'] : $args['date_id'],
                'eta' => ($args['eta']!==null && $args['eta']!=='' && $args['eta']!=='null') ? $this->formatDate($args['eta']) : '',
                'etd' => ($args['etd']!==null && $args['etd']!=='' && $args['etd']!=='null') ? $this->formatDate($args['etd']) : '' ,
                'location_from' => intval($args['location_from']),
                'location_to' => intval($args['location_to']),
                'mode' => isset($args['mode']) ? $args['mode'] : '',
                'legs' => $first_schedule!==null ? $first_schedule['legs'] : [],
                'type' => isset($args['type']) ? $args['type'] : '',
                'carrier_name' => [
                    'id' => intval($args['carrier_id'])
                ],
                'carrier_name_label' => (isset($args['carrier_id']) && $args['carrier_id']!==null && $args['carrier_id']!=='' && $args['carrier_id']!=='null') ? Carrier::find(intval($args['carrier_id']))->name : '',
                'is_confirmed' => $first_schedule!==null ? $first_schedule['is_confirmed'] : 0,
                'size_id' => $first_schedule!==null ? $first_schedule['size_id'] : null,
                'price' => $first_schedule!==null ? $first_schedule['price'] : null,
                'selling_size_id' => $first_schedule!==null ? $first_schedule['selling_size_id'] : null,
                'selling_price' => $first_schedule!==null ? $first_schedule['selling_price'] : null,
                'sell_rates' => $first_schedule!==null ? $first_schedule['sell_rates'] : [],
                'buy_rates' => $first_schedule!==null ? $first_schedule['buy_rates'] : [],
            ]);

            if ( is_countable($schedules) && count($schedules) > 0 ) {
                $schedules[0] = $newSchedules[0];
            } else {
                $schedules = $newSchedules;
            }

            $checkShipment->schedules_group_bookings = json_encode($schedules);
            $checkShipment->schedules_group = json_encode($schedules);

            if ( $newSchedules[0]['is_confirmed'] == 1) {
                $scheckShipment->eta = $args['eta'];
                $scheckShipment->etd = $args['etd'];
            }

            $checkShipment->mbl_num = $args['mbl_num'];
            $checkShipment->booking_number = $args['booking_num'];
            $checkShipment->vessel = $args['vessel'];
            $checkShipment->voyage_number = $args['voyage_number'];
            $checkShipment->terminal_id = intval($args['terminal_id']);
            $checkShipment->carrier_id = intval($args['carrier_id']);
            $checkShipment->save();

            $response['status'] = 'ok';

        }
        return response()->json($response);

    }

    public function markBookedExternal(Request $request)
    {
        $response = ['status' => 'not ok'];
        $customer_id = $this->getCurrentSelectedCustomer();

        $validator = Validator::make($request->all(), [
            //'mbl_num' => 'required',
            'id' => ['required', new ValidateShipment],
        ]);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors(),
                'status' => 'not ok'
            ]
            ,401);
        } else {
            $args = $request->all();
            $suppliers = [];
            if ( isset($args['purchase_orders']) && count($args['purchase_orders']) > 0 ) {
                foreach( $args['purchase_orders'] as $key => $purchase_order ) {
                    array_push($suppliers, [
                        'id' => $args['supplier_timestamps'][$key],
                        'hbl_copy' => null,
                        'packing_list' => null,
                        'commercial_documents' => null,
                        'commercial_invoice' => null,
                        'po_id' => '',
                        'po_num' => '',
                        'volume' => '',
                        'supplier_id' => $purchase_order['supplier_id'],
                        'cargo_ready_date' => '',
                        'kg' => '',
                        'cbm' => '',
                        'incoterm_id' => '',
                        'hbl_num' => '',
                        'product_description' => '',
                        'total_cartons' => '',
                        'bl_status' => 'Pending',
                        'ams_num' => '',
                        'containers' => [],
                        'isOpenAddPurchaseOrders' => false
                    ]);

                    $products = [];
                    //remove special fields
                    if ( count ($purchase_order['products']) > 0 ) {
                        foreach( $purchase_order['products'] as $product ) {
                            if ( !isset($product['special']) && !isset($product['addSpecial']) ) {
                                array_push($products, [
                                    'id' => $product['product_id'],
                                    'is_shipment' => 1,
                                    'ship_cartons' => intval($product['carton'])
                                ]);
                            }
                        }
                    }
                    $args['purchase_orders'][$key]['products'] = $products;
                }
            }

            if ( $customer_id !== 0 ) {

                $check_shipment = Shipment::find($args['id']);

                if ( isset($check_shipment->id) ) {

                    $schedules = json_decode($this->merge_group($check_shipment->schedules_group_bookings, $check_shipment->schedules_group));

                    if ( count($schedules) > 0 ) {


                        if ( isset($args['mbl_num']) && $args['mbl_num']!==null ) {
                            $schedules[0]->etd = '';
                            $schedules[0]->eta = '';
                            $schedules[0]->is_confirmed = 1;
                        } else {
                            $schedules[0]->etd =$args['etd']!=='Invalid date' ? $args['etd'] : '';
                            $schedules[0]->eta = $args['eta']!=='Invalid date' ? $args['eta'] : '';
                            if ( $args['etd']!=='Invalid date' ) {
                                $schedules[0]->etd = Carbon::createFromFormat('Y-m-d', $schedules[0]->etd)->format('Y-m-d H:i:s');
                            }
                            if ( $args['eta']!=='Invalid date' ) {
                                $schedules[0]->eta = Carbon::createFromFormat('Y-m-d', $schedules[0]->eta)->format('Y-m-d H:i:s');
                            }
                            $schedules[0]->is_confirmed = 1;
                        }

                        $edited_shipment = Shipment::where('id', $args['id'])->update([
                            'schedules_group' => json_encode($schedules),
                            'schedules_group_bookings' => json_encode($schedules),
                            'suppliers_group_bookings' => json_encode($suppliers),
                            'suppliers_group' => json_encode($suppliers),
                            'containers_group_bookings' => json_encode($args['containers']),
                            'containers_group' => json_encode($args['containers']),
                            'suppliers_group_bookings' => json_encode($suppliers),
                            'mbl_num' => $args['mbl_num'],
                            'eta' => $schedules[0]->eta,
                            'etd' => $schedules[0]->etd,
                            'booking_confirmed' => 1,
                            'shipment_status' => 'Approved',
                            'booking_confirmed_at' => Carbon::now(),
                            'booking_number' => $args['booking_num'],
                            'vessel' => $args['vessel'],
                            'voyage_number' => $args['voyage_number'],
                            'customer_id' => $customer_id,
                            'customer_reference_number' => $args['customer_reference_number'],
                            'updated_at' => Carbon::now(),
                            'is_tracking_shipment' => ($args['mbl_num']=='' || $args['mbl_num']==null) ? 0 : 1
                        ]);
                    }
                }

                if ( isset($edited_shipment->id) ) {

                    if ( isset($args['purchase_orders']) && count($args['purchase_orders']) > 0) {
                        $jwtToken = CustomJWTGenerator::generateToken();
                        $res = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Accept'     => 'application/json',
                            'Authorization'  => 'Bearer ' . $jwtToken,
                        ])->post(getenv('PO_URL').'/api/po/purchase-orders/purchase-orders-shipments', [
                            'purchase_orders' => $args['purchase_orders'],
                            'shipment_id' => $edited_shipment->id
                        ]);

                        $response['status'] = 'ok';
                        $response['shipment_id'] = $edited_shipment->id;
                        $response['po_response'] = $res->json();
                        $response['shipment'] = $edited_shipment;
                    }

                } else {
                    $response['special_error'] = 'An unexpected error occured.';
                }
            } else {
                $response['special_error'] = 'An unexpected error occured.';
            }

            $response['shipment'] = [
                'mbl_num' => $args['mbl_num'],
                'booking_number' => $args['booking_num'],
                'vessel' => $args['vessel'],
                'voyage_number' => $args['voyage_number']
            ];
        }

    }

    /**
     * Edit Shipment
     *
     * @bodyParam id int required
     * @bodyParam eta string required Example: 2020-02-08
     * @bodyParam etd string required Example: 2020-01-24
     * @bodyParam date_id string required
     * @bodyParam mbl_num string required Example: YMLUE236243273
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editShipment(Request $request)
    {
        $response = ['status' => 'not ok'];

        $shipments = [];
        $newShipments = [];
        $customer_id = $this->getCurrentSelectedCustomer();

        $validator = Validator::make($request->all(), [
            'eta' => 'required',
            'etd' => 'required',
            'date_id' => 'required',
            //'mbl_num' => 'required|unique:shipments'
            'mbl_num' => 'required',
            'id' => ['required', new ValidateShipment],
        ]);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors(),
                'status' => 'not ok'
            ]
            ,401);
        } else {
            $args = $request->all();
            $carrier_name_label = Carrier::find(intval($args['carrier_id']));
            $carrier_name_label = isset($carrier_name_label->name) ? $carrier_name_label->name : '';
            //create shipment schedules

            $schedules = [[
                'id' => $args['date_id'],
                'eta' => $this->formatDate($args['eta']),
                'etd' => $this->formatDate($args['etd']),
                'location_from' => isset($args['location_from']) ? intval($args['location_from']) : 0,
                'location_to' => isset($args['location_to']) ? intval($args['location_to']) : 0,
                'mode' => isset($args['mode'])? $args['mode'] : '',
                'legs' => [],
                'type' => isset($args['type']) ? $args['type'] : '',
                'carrier_name' => [
                    'id' => (isset($args['carrier_id'])) ? intval($args['carrier_id']) : 0
                ],
                'carrier_name_label' => $carrier_name_label,
                'is_confirmed' => 1,
                'size_id' => null,
                'price' => null,
                'selling_size_id' => null,
                'selling_price' => null,
                'sell_rates' => [],
                'buy_rates' => []
            ]];

            $suppliers = [];
            if ( isset($args['purchase_orders']) && count($args['purchase_orders']) > 0 ) {
                foreach( $args['purchase_orders'] as $key => $purchase_order ) {
                    array_push($suppliers, [
                        'id' => $args['supplier_timestamps'][$key],
                        'hbl_copy' => null,
                        'packing_list' => null,
                        'commercial_documents' => null,
                        'commercial_invoice' => null,
                        'po_id' => '',
                        'po_num' => '',
                        'volume' => '',
                        'supplier_id' => $purchase_order['supplier_id'],
                        'cargo_ready_date' => '',
                        'kg' => '',
                        'cbm' => '',
                        'incoterm_id' => '',
                        'hbl_num' => '',
                        'product_description' => '',
                        'total_cartons' => '',
                        'bl_status' => 'Pending',
                        'ams_num' => '',
                        'containers' => [],
                        'isOpenAddPurchaseOrders' => false
                    ]);

                    $products = [];
                    //remove special fields
                    if ( count ($purchase_order['products']) > 0 ) {
                        foreach( $purchase_order['products'] as $product ) {
                            if ( !isset($product['special']) && !isset($product['addSpecial']) ) {
                                array_push($products, [
                                    'id' => $product['product_id'],
                                    'is_shipment' => 1,
                                    'ship_cartons' => intval($product['carton'])
                                ]);
                            }
                        }
                    }
                    $args['purchase_orders'][$key]['products'] = $products;
                }

            }

            if ( $customer_id !== 0 ) {

                $edited_shipment = Shipment::where('id', $args['id'])->update([
                    'schedules_group_bookings' => json_encode($schedules),
                    //'schedules_group' => json_encode($schedules),
                    'suppliers_group_bookings' => json_encode($suppliers),
                    'suppliers_group' => json_encode($suppliers),
                    'containers_group_bookings' => json_encode($args['containers']),
                    'containers_group' => json_encode($args['containers']),
                    'mbl_num' => $args['mbl_num'],
                    'eta' => $schedules[0]['eta'],
                    'etd' => $schedules[0]['etd'],
                    'carrier_id' => intval($args['carrier_id']),
                    'booking_number' => $args['booking_num'],
                    'vessel' => $args['vessel'],
                    'voyage_number' => $args['voyage_number'],
                    'customer_id' => $customer_id,
                    'is_tracking_shipment' => 1,
                    'booking_confirmed' => 1,
                    'terminal_id' => intval($args['terminal']),
                    'updated_at' => Carbon::now()
                ]);

                if ( isset($edited_shipment->id) ) {

                    if ( isset($args['purchase_orders']) && count($args['purchase_orders']) > 0) {
                        $jwtToken = CustomJWTGenerator::generateToken();
                        $res = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Accept'     => 'application/json',
                            'Authorization'  => 'Bearer ' . $jwtToken,
                        ])->post(getenv('PO_URL').'/api/po/purchase-orders/purchase-orders-shipments', [
                            'purchase_orders' => $args['purchase_orders'],
                            'shipment_id' => $edited_shipment->id
                        ]);

                        $response['status'] = 'ok';
                        $response['shipment_id'] = $edited_shipment->id;
                        $response['po_response'] = $res->json();
                        $response['shipment'] = $edited_shipment;
                    }

                } else {
                    $response['special_error'] = 'An unexpected error occured.';
                }
            } else {
                $response['special_error'] = 'An unexpected error occured.';
            }

            $response['shipment'] = [
                'schedules_group_bookings' => json_encode($schedules),
                'schedules_group' => json_encode($schedules),
                'mbl_num' => $args['mbl_num'],
                'booking_number' => $args['booking_num'],
                'vessel' => $args['vessel'],
                'voyage_number' => $args['voyage_number']
            ];


        }
        return response()->json($response);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Create Shipment
     */
    public function createShipment(Request $request)
    {
        $response = ['status' => 'not ok'];

        $shipments = [];
        $newShipments = [];
        $customer_id = $this->getCurrentSelectedCustomer();

        $validator = Validator::make($request->all(), [
            'date_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors(),
                'status' => 'not ok'
            ]
            ,401);
        } else {
            $args = $request->all();

            //create schedules new
            $schedules = [[
                'id' => $args['date_id'],
                'eta' => $this->formatDate($args['eta']),
                'etd' => $this->formatDate($args['etd']),
                'location_from' => $args['location_from'],
                'location_to' => $args['location_to'],
                'mode' => isset($args['mode']) ? $args['mode'] : '',
                'legs' => [],
                'type' => isset($args['type']) ? $args['type'] : '',
                'carrier_name' => [
                    'id' => 0
                ],
                'carrier_name_label' => '',
                'is_confirmed' => 1,
                'size_id' => null,
                'price' => null,
                'selling_size_id' => null,
                'selling_price' => null,
                'sell_rates' => [],
                'buy_rates' => []
            ]];

            $suppliers = [];
            if ( isset($args['purchase_orders']) && count($args['purchase_orders']) > 0 ) {
                foreach( $args['purchase_orders'] as $key => $purchase_order ) {
                    // Get sum of carton, volume and weights based on the product
                    $total_cartons = null;
                    $total_volume = null;
                    $total_weight = null;
                    if($purchase_order['layout'] == 'default') {
                        foreach($purchase_order['products'] as $key => $purchase_product) {
                            $total_cartons += isset($purchase_product['carton']) ? $purchase_product['carton'] : 0;
                            $total_volume += isset($purchase_product['volume']) ? $purchase_product['volume'] : 0;
                            $total_weight += isset($purchase_product['weight']) ? $purchase_product['weight'] : 0;
                        }
                    } else {
                        $total_weight = isset($purchase_order['total_weights']) ? $purchase_order['total_weights'] : 0;
                        $total_cartons = isset($purchase_order['total_cartons']) ? $purchase_order['total_cartons'] : 0;
                        $total_volume = isset($purchase_order['total_volumes']) ? $purchase_order['total_volumes'] : 0;
                    }

                    array_push($suppliers, [
                        'id' => strval(hexdec(uniqid())),
                        'hbl_copy' => null,
                        'packing_list' => null,
                        'commercial_documents' => null,
                        'commercial_invoice' => null,
                        'po_id' => $purchase_order['layout'] == 'default' ? !empty($purchase_order['purchase_order_id']) ? $purchase_order['purchase_order_id'] : '' : '',
                        'po_num' => !empty($purchase_order['purchase_order_number']) ? $purchase_order['purchase_order_number'] : '',
                        'volume' => '',
                        'supplier_id' => $purchase_order['supplier_id'],
                        'buyer_id' => $purchase_order['buyer_id'],
                        'cargo_ready_date' => !empty($purchase_order['cargo_ready_date']) ? $purchase_order['cargo_ready_date'] : '',
                        'kg' => $total_weight,
                        'cbm' => $total_volume,
                        'incoterm_id' => '',
                        'hbl_num' => '',
                        'product_description' => $args['commodities_info'],
                        'total_cartons' => $total_cartons,
                        'total_volumes' => !empty($purchase_order['total_volumes']) ? $purchase_order['total_volumes'] : '',
                        'total_weights' => !empty($purchase_order['total_weights']) ? $purchase_order['total_weights'] : '',
                        'bl_status' => 'Pending',
                        'ams_num' => '',
                        'containers' => [],
                        'isOpenAddPurchaseOrders' => false,
                    ]);

                    $products = [];
                    //remove special fields
                    if ( count ($purchase_order['products']) > 0 ) {
                        foreach( $purchase_order['products'] as $product ) {
                            if ( !isset($product['special']) && !isset($product['addSpecial']) ) {
                                array_push($products, [
                                    'id' => $product['product_id'],
                                    'is_shipment' => 1,
                                    'ship_cartons' => intval($product['carton'])
                                ]);
                            }
                        }
                    }
                    $args['purchase_orders'][$key]['products'] = $products;
                }
            }

            $containers = [];
            if ( isset($args['containerInfo']) && count($args['containerInfo']) > 0 ) {
                foreach( $args['containerInfo'] as $container ) {
                    $container_uniqid = strval(hexdec(uniqid()));
                    array_push($containers,[
                        "id" => $container_uniqid,
                        "container_num" => $container['container_details']['number'],
                        "size" => $container['container_details']['equipment_length'],
                        "cbm" => null,
                        "kg" => $container['container_details']['weight_in_lbs'],
                        "cartons" => null,
                        "seal_num" => $container['container_details']['seal_number']
                    ]);

                }
            }

            if ( $customer_id !== 0 ) {

                $created_shipment = Shipment::create([
                    'schedules_group_bookings' => json_encode($schedules),
                    'schedules_group' => json_encode($schedules),
                    'suppliers_group_bookings' => json_encode($suppliers),
                    'suppliers_group' => json_encode($suppliers),
                    'containers_group_bookings' => json_encode($containers),
                    'containers_group' => json_encode($containers),
                    'mbl_num' => $args['mbl_num'],
                    'mode' => '',
                    'eta' => $this->formatDate($args['eta']),
                    'etd' => $this->formatDate($args['etd']),
                    'booking_number' => $args['booking_num'],
                    'customer_reference_number' => $args['customer_reference_number'],
                    'customer_id' => $customer_id,
                    'is_tracking_shipment' => 1,
                    'booking_confirmed' => 1,
                    'is_shipment_tracking_create' => 1,
                ]);

                if ( isset($created_shipment->id) ) {
                    $response['status'] = 'ok';
                    $response['shipment_id'] = $created_shipment->id;
                    $response['shipment'] = $created_shipment;
                    if ( isset($args['purchase_orders']) && count($args['purchase_orders']) > 0) {

                        try {
                            $jwtToken = CustomJWTGenerator::generateToken();
                            $res = Http::withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept'     => 'application/json',
                                'Authorization'  => 'Bearer ' . $jwtToken,
                            ])->post(getenv('PO_URL').'/api/po/purchase-orders/purchase-orders-shipments', [
                                'purchase_orders' => $args['purchase_orders'],
                                'shipment_id' => $created_shipment->id
                            ]);
                        } catch(Exception $e) {

                        }
                    }

                } else {
                    $response['special_error'] = 'An unexpected error occured.';
                }
            } else {
                $response['special_error'] = 'An unexpected error occured.';
            }

        }
        return response()->json($response);

    }

    public function createBookingShipment(Request $request)
    {
        $response = ['status' => 'not ok'];
        $submitPurchaseOrders = [];
        $submit_token = null;

        $finalResponse = DB::transaction(function () use($request, $submit_token, $submitPurchaseOrders, $response) {
            $loggedInUserId = $this->getCurrentSelectedCustomer();
            //for manual
            $manualPurchaseOrders = [];

            //for automatic
            $autoPurchaseOrders = [];

            $validator = Validator::make($request->all(), [
                'location_from_id' => 'required',
                'location_to_id' => 'required',
                'role' => 'required',
                'shipper_id' => 'required',
                'consignee_id' => 'required',
                'mode' => 'required',
                'type' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'errors' => $validator->errors(),
                        'status' => 'not ok'
                    ]
                    ,401);
            } else {
                $args = $request->all();
                $args['is_review'] = is_bool($args['is_review']) ? $args['is_review'] : filter_var($args['is_review'], FILTER_VALIDATE_BOOLEAN);
                $incoterm = $this->getIncotermByName($args['inco_term']);
                $incotermId = $incoterm ? $incoterm->id : null;
                //build our schedule
                $schedules = [[
                    'id' => $args['schedule_id'],
                    'eta' => '',
                    'etd' => '',
                    'location_from' => intval($args['location_from_id']),
                    'location_to' => intval($args['location_to_id']),
                    'mode' => $args['mode'],
                    'legs' => [],
                    'type' => $args['type'],
                    'carrier_name' => [
                        'id' => !empty($args['carrier_id']) ? intval($args['carrier_id']) : null
                    ],
                    'carrier_name_label' => '',
                    'is_confirmed' => 0,
                    'size_id' => null,
                    'price' => null,
                    'selling_size_id' => null,
                    'selling_price' => null,
                    'sell_rates' => [],
                    'buy_rates' => []
                ]];

                $shipperConsigneeId = $this->getShipperAndBuyerId($args);
                $shipper_id = $shipperConsigneeId['shipperId'];
                $consignee_id = $shipperConsigneeId['consigneeId'];
                $customer_id = $this->getCustomerId($args);
                $shiflOfficeOriginFromId = $this->getShiflOfficeFromId($args['location_from_id']);
                $shiflOfficeOriginToId = $this->getShiflOfficeToId($args['location_to_id']);
                $shiflOfficeUser = $this->getCustomerShiflOfficeUser($customer_id, $shiflOfficeOriginFromId);

                $created_shipment = Shipment::create([
                    'schedules_group_bookings' => json_encode($schedules),
                    'schedules_group' => json_encode($schedules),
                    'suppliers_group_bookings' => json_encode([]),
                    'suppliers_group' => json_encode([]),
                    'containers_group_bookings' => json_encode([]),
                    'containers_group' => json_encode([]),
                    'customer_id' => $customer_id,
                    'is_tracking_shipment' => 0,
                    'booking_confirmed' => 0,
                    'final_address' => $args['final_address'],
                    'customer_reference_number' => $args['customer_reference_number'],
                    'was_created_in_portal' => 1,
                    'is_draft' => $args['is_draft'],
                    'shifl_office_origin_from_id' => $shiflOfficeOriginFromId,
                    'shifl_office_origin_to_id' => $shiflOfficeOriginToId,
                    'import_name_id' => isset($args['import_name_id']) ? $args['import_name_id'] : '',
                    'custom_notes' => isset($args['special_instructions']) ? $args['special_instructions'] : '',
                    'created_by' => $loggedInUserId
                ]);

                if ( isset($created_shipment->id) ) {

                    //update manually the eta,etd
                    \DB::table('shipments')->where('id', $created_shipment->id)
                        ->update(['eta' => null, 'etd' => null]);

                    if (isset($args['purchase_orders'])) {
                        //get purchase orders
                        $purchaseOrders = $args['purchase_orders'];
                        //if purchase orders length is greater than 0
                        if (count($purchaseOrders) > 0) {
                            foreach ($purchaseOrders as $purchaseOrder) {
                                //assign them to their respective array containers
                                if (isset($purchaseOrder['layout'])) {
                                    if ($purchaseOrder['layout'] === 'default') {
                                        array_push($autoPurchaseOrders, $purchaseOrder);
                                    } else {
                                        array_push($manualPurchaseOrders, $purchaseOrder);
                                    }
                                }
                            }
                        }
                    }

                    $autoPurchaseOrders = $this->buildAutoPurchaseOrders($autoPurchaseOrders);
                    $suppliers = $this->buildSuppliersData($autoPurchaseOrders, $manualPurchaseOrders, $shipper_id, $args['role'], $incotermId, $consignee_id, $args, $customer_id);

                    $manualPOSOData = [];
                    if(count($suppliers) > 0){
                        $manualPOSOData = collect($suppliers)->filter(function ($item) {
                            return $item['po_num'] != '' && $item['po_num'] != null;
                        });
                    }

                    $created_shipment->suppliers_group_bookings = json_encode($suppliers);
                    $created_shipment->add_manual_po_so_data = json_encode($manualPOSOData);
                    $created_shipment->suppliers_group = json_encode($suppliers);
                    $created_shipment->save();

                    //if there are containers
                    $container_metas = [];
                    if ( isset($args['containers'])) {
                        $get_containers = $args['containers'];
                        $containers = [];

                        foreach( $get_containers as $key => $get_container ) {
                            if ( $get_container['checked'] && $get_container['checked'] == 'true' ) {
                                $container_uniqid = strval(hexdec(uniqid()));
                                array_push($containers,[
                                    "id" => $container_uniqid,
                                    "container_num" => '',
                                    "size" => intval($get_container['size_id']),
                                    "cbm" => null,
                                    "kg" => null,
                                    "cartons" => intval($get_container['how_many']),
                                    "seal_num" => null
                                ]);

                                array_push($container_metas,[
                                    "unique_id" => $container_uniqid,
                                    "container_num" => '',
                                    "size" => intval($get_container['size_id']),
                                    "cbm" => null,
                                    "kg" => null,
                                    "cartons" => intval($get_container['how_many']),
                                    "how_many" => $get_container['how_many']
                                ]);
                            }
                        }

                        \DB::table('shipments')->where('id', $created_shipment->id)
                            ->update([
                                'containers_group_bookings' => json_encode($containers),
                                'containers_group' => json_encode($containers)
                            ]);

                        //s
                        $getContainers = json_decode(json_encode($containers));
                        $setContainers = [];
                        $item = $created_shipment;

                        if ($getContainers != '') {
                            if (count($getContainers) > 0) {
                                $selectedContainers = [];
                                //map all current container id
                                foreach ($getContainers as $getContainer) {
                                    // array_push($selectedSuppliers, $getSupplier->supplier_id);
                                    array_push($selectedContainers, $getContainer->id);
                                }

                                if (count($selectedContainers) > 0) {

                                    //delete containers data that not are not listed
                                    \DB::table('containers')
                                        //->whereNotIn('supplier_id',$selectedSuppliers)
                                        ->whereNotIn('unique_id', $selectedContainers)
                                        ->where('shipment_id', $item->id)
                                        ->delete();
                                }

                                foreach ($getContainers as $key => $getContainer) {
                                    $checkContainer = \DB::table('containers')
                                        ->where('unique_id', $getContainer->id)
                                        ->where('shipment_id', $item->id)
                                        //->where('shipment_id',$item->id)
                                        //->where('supplier_id',$getContainer->supplier_id)
                                        ->first();

                                    if (!isset($checkContainer->id)) {
                                        array_push($setContainers, ['shipment_id' => $item->id, 'unique_id' => $getContainer->id, 'size' => intval($getContainer->size), 'cbm' => intval($getContainer->cbm), 'kg' => intval($getContainer->kg), 'cartons' => intval($getContainer->cartons), 'container_num' => $getContainer->container_num, 'seal_num' => isset($getContainer->seal_num) ? $getContainer->seal_num : null]);
                                    } else {
                                        \DB::table('containers')
                                            ->where('id', $checkContainer->id)
                                            ->update(['shipment_id' => $item->id, 'size' => intval($getContainer->size), 'cbm' => intval($getContainer->cbm), 'kg' => intval($getContainer->kg), 'cartons' => intval($getContainer->cartons), 'container_num' => $getContainer->container_num, 'seal_num' => isset($getContainer->seal_num) ? $getContainer->seal_num : null]);
                                    }
                                }
                            } else {
                                //delete containers data that not are not listed
                                \DB::table('containers')
                                    ->where('shipment_id', $item->id)
                                    ->delete();
                            }
                        }

                        if (isset($setContainers) && count($setContainers) > 0) {
                            \DB::table('containers')->insert($setContainers);
                        }
                    }

                    $forwarders = (isset($args['forwarders'])) ? $args['forwarders'] : json_encode([]);
                    if (isset($args['is_review']) && $args['is_review'] === true) {
                        $forwarders = json_encode([['name' => $shiflOfficeUser->name, 'email' => $shiflOfficeUser->email]]);
                    }

                    $shipmentMetaShipperId = $shipper_id;
                    $shipmentMetaConsigneeId = $consignee_id;
                    if(empty($shipmentMetaShipperId)) {
                        $shipmentMetaShipperId = $this->getShipperId($shipmentMetaConsigneeId, $customer_id);
                    } elseif (empty($shipmentMetaConsigneeId)) {
                        $shipmentMetaConsigneeId = $this->getConsigneeId($shipmentMetaShipperId, $customer_id);
                    }
                    //create shipment meta too
                    \DB::table('shipment_metas')->insert([
                        'consignee_id' => $shipmentMetaConsigneeId,
                        'shipper_id' => $shipmentMetaShipperId,
                        'shipper_details_info' => $args['shipper_details_info'],
                        'consignee_details_info' => $args['consignee_details_info'],
                        'commodities_info' => $args['commodities_info'],
                        'location_from' => $args['location_from_id'],
                        'location_to' => $args['location_to_id'],
                        'mode' => $args['mode'],
                        'is_review' => $args['is_review'],
                        'type' => $args['type'],
                        'shipment_id' => $created_shipment->id,
                        'incoterm' => $args['inco_term'],
                        'notify_details_info' => $args['notify_details_info'],
                        'notify_party' => $args['notify_party'],
                        'containers' => json_encode($container_metas),
                        'forwarders' => $forwarders,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'role' => $args['role'],
                        'marks' => isset($args['marks']) ? $args['marks']  : '',
                        'special_instructions' => isset($args['special_instructions']) ? $args['special_instructions'] : ''
                    ]);


                    //make sure to save auto purchase orders
                    if ( count($autoPurchaseOrders) > 0 ) {

                        $multi_purchase_orders = [];
                        foreach ($autoPurchaseOrders as $autoPurchaseOrder ) {
                            array_push($multi_purchase_orders, [
                                'purchaseOrders' => [$autoPurchaseOrder],
                                'supplierId' => intval($autoPurchaseOrder['supplier_id'])
                            ]);
                        }
                    }

                    $content['final_purchase_orders'] = [];

                    try {
                        $jwtToken = CustomJWTGenerator::generateToken();
                        $res = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Accept'     => 'application/json',
                            'Authorization'  => 'Bearer ' . $jwtToken,
                        ])->post(getenv('PO_URL').'/api/po/purchase-orders/purchase-orders-shipments', [
                            'purchase_orders' => $autoPurchaseOrders,
                            'shipment_id' => $created_shipment->id
                        ]);
                    } catch(Exception $e) {
                        Log::info($e->getMessage());
                    }

                    $shipment = Shipment::where('id', $created_shipment->id)->first();

                    //only send to forwarders if it's not draft
                    if ($args['is_draft'] == 0 && $shipment->is_draft == 0) {
                        $content = $this->getEmailContentData($args, $shipment, $shipmentMetaConsigneeId, $shipmentMetaShipperId);
                        $forwarders = is_array($forwarders) ? $forwarders: json_decode($forwarders);
                        $forwarderEmails = $this->getForwarderEmails($forwarders);
                        /* customer create booking */
                        if($this->getCurrentSelectedCustomer() == $this->getCustomerId($args)) {
                            if(isset($args['is_review']) && $args['is_review'] === true) {
                                $this->sendShipmentBookingEmailsForCustomerCreateBookingWithShifl($shipment, $content, $shipmentMetaShipperId, $shipmentMetaConsigneeId, $forwarderEmails, $customer_id, $forwarders);
                            } else {
                                $this->sendShipmentBookingEmailsForCustomerCreateBookingWithOtherForwarder($shipment, $content, $shipmentMetaShipperId, $shipmentMetaConsigneeId, $forwarderEmails, $customer_id, $forwarders);
                            }
                        } else {
                            /* other party create booking */
                            if(isset($args['is_review']) && $args['is_review'] === true) {
                                $this->sendShipmentBookingEmailsForOtherPartyCreateBookingWithShifl($shipment, $content, $shipmentMetaShipperId, $shipmentMetaConsigneeId, $forwarderEmails, $customer_id, $forwarders);
                            } else {
                                $this->sendShipmentBookingEmailsForOtherPartyCreateBookingWithOtherForwarder($shipment, $content, $shipmentMetaShipperId, $shipmentMetaConsigneeId, $forwarderEmails, $customer_id, $forwarders);
                            }
                        }
                    }

                    $response['status'] = 'ok';
                    $response['shipment'] = $created_shipment;
                }
            }
            return $response;
        });

        return response()->json($finalResponse);

    }

    public function updateBookingShipment(Request $request) {
        $validator = Validator::make($request->all(), [
            'shipment_id' => 'required',
            'location_from_id' => 'required',
            'location_to_id' => 'required',
            'role' => 'required',
            'shipper_id' => 'required',
            'consignee_id' => 'required',
            'mode' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors(),
                    'status' => 'Validations Failed'
                ]
                ,401);
        }

        $requestData = $request->all();
        $shipmentId = $requestData['shipment_id'];
        $incoterm = $this->getIncotermByName($requestData['inco_term']);
        $incotermId = $incoterm ? $incoterm->id : null;
        $manualPurchaseOrders = [];
        $autoPurchaseOrders = [];

        $requestData['is_review'] = is_bool($requestData['is_review']) ? $requestData['is_review'] : filter_var($requestData['is_review'], FILTER_VALIDATE_BOOLEAN);
        $shipment = Shipment::find($shipmentId);
        $schedulesData = json_decode($shipment->schedules_group_bookings, true);

        $schedules = $this->buildScheduleBookingFromRequest($requestData, $schedulesData);

        $shipperConsigneeId = $this->getShipperAndBuyerId($requestData);
        $shipperId = $shipperConsigneeId['shipperId'];
        $consigneeId = $shipperConsigneeId['consigneeId'];

        $customerId = $this->getCustomerId($requestData);
        $containers = $this->buildContainersData($requestData['containers']);
        $containersMeta = $this->buildContainersMetas($requestData['containers']);
        $this->updateContainerDataInTable($containers, $shipmentId);

        if (isset($requestData['purchase_orders'])) {
            //get purchase orders
            $purchaseOrders = $requestData['purchase_orders'];
            //if purchase orders length is greater than 0
            if (count($purchaseOrders) > 0) {
                foreach ($purchaseOrders as $purchaseOrder) {
                    //assign them to their respective array containers
                    if (isset($purchaseOrder['layout'])) {
                        if ($purchaseOrder['layout'] === 'default') {
                            array_push($autoPurchaseOrders, $purchaseOrder);
                        } else {
                            array_push($manualPurchaseOrders, $purchaseOrder);
                        }
                    }
                }
            }
        }

        $autoPurchaseOrders = $this->buildAutoPurchaseOrders($autoPurchaseOrders);
        $suppliers = $this->buildSuppliersData($autoPurchaseOrders, $manualPurchaseOrders, $shipperId, $requestData['role'], $incotermId, $consigneeId, $requestData, $customerId);
        $manualPOSOData = [];
        if(count($suppliers) > 0){
            $manualPOSOData = collect($suppliers)->filter(function ($item) {
                return $item['po_num'] != '' && $item['po_num'] != null;
            });
        }

        $shiflOfficeOriginFromId = $this->getShiflOfficeFromId($requestData['location_from_id']);
        $shiflOfficeOriginToId = $this->getShiflOfficeToId($requestData['location_to_id']);
        $shiflOfficeUser = $this->getCustomerShiflOfficeUser($customerId, $shiflOfficeOriginFromId);

        $shipmentData = [
            'suppliers_group' => json_encode($suppliers),
            'suppliers_group_bookings' => json_encode($suppliers),
            'add_manual_po_so_data' => json_encode($manualPOSOData),
            'customer_id' => $customerId,
            'final_address' => $requestData['final_address'],
            'customer_reference_number' => $requestData['customer_reference_number'],
            'is_draft' => $requestData['is_draft'],
            'containers_group_bookings' => json_encode($containers),
            'containers_group' => json_encode($containers),
            'shifl_office_origin_from_id' => $shiflOfficeOriginFromId,
            'shifl_office_origin_to_id' => $shiflOfficeOriginToId,
            'schedules_group' => json_encode($schedules),
            'schedules_group_bookings' => json_encode($schedules),
            'import_name_id' => isset($requestData['import_name_id']) ? $requestData['import_name_id'] : '',
            'custom_notes' => isset($requestData['special_instructions']) ? $requestData['special_instructions'] : '',
        ];

        \DB::table('shipments')->where('id', $shipmentId)
            ->update($shipmentData);

        $forwarders = (isset($requestData['forwarders'])) ? $requestData['forwarders'] : json_encode([]);
        if(isset($requestData['is_review']) && $requestData['is_review'] === true) {
            $forwarders = json_encode([['name' => $shiflOfficeUser->name, 'email' => $shiflOfficeUser->email]]);
        }

        $shipmentMetaShipperId = $shipperId;
        $shipmentMetaConsigneeId = $consigneeId;
        if(empty($shipmentMetaShipperId)) {
            $shipmentMetaShipperId = $this->getShipperId($shipmentMetaConsigneeId, $customerId);
        } elseif (empty($shipmentMetaConsigneeId)) {
            $shipmentMetaConsigneeId = $this->getConsigneeId($shipmentMetaShipperId, $customerId);
        }
        //update shipment meta data
        $shipmentMetaData = [
            'consignee_id' => $shipmentMetaConsigneeId,
            'shipper_id' => $shipmentMetaShipperId,
            'shipper_details_info' => $requestData['shipper_details_info'],
            'consignee_details_info' => $requestData['consignee_details_info'],
            'commodities_info' => $requestData['commodities_info'],
            'location_from' => $requestData['location_from_id'],
            'location_to' => $requestData['location_to_id'],
            'mode' => $requestData['mode'],
            'is_review' => $requestData['is_review'],
            'type' => $requestData['type'],
            'shipment_id' => $shipmentId,
            'incoterm' => $requestData['inco_term'],
            'notify_details_info' => $requestData['notify_details_info'],
            'notify_party' => $requestData['notify_party'],
            'containers' => json_encode($containersMeta),
            'forwarders' => $forwarders,
            'updated_at' => Carbon::now(),
            'role' => $requestData['role'],
            'marks' => $requestData['marks'],
            'special_instructions' => $requestData['special_instructions']
        ];

        \DB::table('shipment_metas')->where('shipment_id', $shipmentId)
            ->update($shipmentMetaData);

        $this->insertManualOrderDocuments($shipmentId, $manualPurchaseOrders, $requestData['role'], $shipperId, $consigneeId);

        $content['final_purchase_orders'] = [];

        try {
            $jwtToken = CustomJWTGenerator::generateToken();
            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept'     => 'application/json',
                'Authorization'  => 'Bearer ' . $jwtToken,
            ])->post(getenv('PO_URL').'/api/po/purchase-orders/purchase-orders-shipments', [
                'purchase_orders' => $autoPurchaseOrders,
                'shipment_id' => $shipmentId
            ]);
        } catch(Exception $e) {
            Log::info($e->getMessage());
        }

        $shipment = Shipment::where('id', $shipmentId)->first();

        //only send to forwarders if it's not draft
        if ( $requestData['is_draft'] == 0 && $shipment->is_draft == 0) {
            $content = $this->getEmailContentData($requestData, $shipment, $shipmentMetaConsigneeId, $shipmentMetaShipperId);
            $forwarders = is_array($forwarders) ? $forwarders: json_decode($forwarders);
            $forwarderEmails = $this->getForwarderEmails($forwarders);

            if($this->getCurrentSelectedCustomer() == $this->getCustomerId($requestData)) {
                if(isset($requestData['is_review']) && $requestData['is_review'] === true) {
                    $this->sendShipmentBookingEmailsForCustomerCreateBookingWithShifl($shipment, $content, $shipmentMetaShipperId, $shipmentMetaConsigneeId, $forwarderEmails, $customerId, $forwarders);
                } else {
                    $this->sendShipmentBookingEmailsForCustomerCreateBookingWithOtherForwarder($shipment, $content, $shipmentMetaShipperId, $shipmentMetaConsigneeId, $forwarderEmails, $customerId, $forwarders);
                }
            } else {
                if(isset($requestData['is_review']) && $requestData['is_review'] === true) {
                    $this->sendShipmentBookingEmailsForOtherPartyCreateBookingWithShifl($shipment, $content, $shipmentMetaShipperId, $shipmentMetaConsigneeId, $forwarderEmails, $customerId, $forwarders);
                } else {
                    $this->sendShipmentBookingEmailsForOtherPartyCreateBookingWithOtherForwarder($shipment, $shipmentMetaShipperId, $shipperId, $shipmentMetaConsigneeId, $forwarderEmails, $customerId, $forwarders);
                }
            }
        }

        $response['status'] = 'ok';
        $response['shipment'] = $shipment;

        return response()->json($response);

    }

    private function getEmailContentData($requestData, $shipment, $consigneeId, $shipperId) {
        $content['final_purchase_orders'] = [];
        try {
            $jwtToken = CustomJWTGenerator::generateToken();
            $resSecond = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept'     => 'application/json',
                'Authorization'  => 'Bearer ' . $jwtToken,
            ])->get(getenv('PO_URL').'/api/po/purchase-orders/purchase-orders-shipments-mail/'.$shipment->id);

            $content['final_purchase_orders'] = json_decode(json_encode($resSecond->json()), true);
        } catch(Exception $e) {
            Log::info($e->getMessage());
        }
        if (isset($content['final_purchase_orders']['items'])) {
            $content['final_purchase_orders'] = $content['final_purchase_orders']['items'];
        }

        //set email values
        $content['shipment'] = $shipment;
        $content['location_from_name'] = '';
        $content['location_to_name'] = '';

        $content['role'] = $requestData['role'];

        $findSupplier = Supplier::find($shipperId);
        $content['shipper_name'] = '';
        $content['shipper_address'] = '';
        if(isset($findSupplier->id)) {
            $content['shipper_name'] = $findSupplier->company_name;
            $content['shipper_address'] = $findSupplier->address;
        }

        $findBuyer = Buyer::find($consigneeId);
        $content['consignee_name'] = '';
        $content['consignee_address'] = '';

        if(isset($findBuyer->id)) {
            $content['consignee_name'] = $findBuyer->company_name;
            $content['consignee_address'] = $findBuyer->address;
        }

        //assign locations
        try {
            $content['location_from_name'] = TerminalRegion::find($requestData['location_from_id'])->name;
            $content['location_to_name'] = TerminalRegion::find($requestData['location_to_id'])->name;
        }catch(Exception $e) {
            Log::info($e->getMessage());
        }

        //commodities
        $content['commodities_info'] = $requestData['commodities_info'];
        //marks
        $content['marks'] = $requestData['marks'];

        //special instructions
        $content['special_instructions'] = $requestData['special_instructions'];

        //mode
        $content['mode'] = $requestData['mode'];

        //types
        $content['type'] =$requestData['type'];

        $content['inco_term'] = $requestData['inco_term'];
        $content['notify_details_info'] = $requestData['notify_details_info'];
        $content['total_cartons'] = $requestData['total_cartons'];
        $content['total_volumes'] = $requestData['total_volume'];
        $content['total_weights'] = $requestData['total_weight'];

        return $content;
    }

    private function getShipperAndBuyerId($request){
        $shipperId = null;
        $buyerId = null;
        $loggedCustomerId = $this->getCurrentSelectedCustomer();
        if($request['inco_term'] === 'FOB' || $request['inco_term'] === 'EXW') {
            // if selected role is consignee then received connected customer id in consignee_id else received Buyer id in consignee_id
            if($request['role'] === 'consignee') {
                $shipperId = $request['shipper_id'];
            } else {
                $buyer = \App\Buyer::find($request['consignee_id']);
                if (isset($buyer->id) && $buyer->connected_customer) {
                    $customerId = $buyer->connected_customer;
                    $customerSuppliers = CustomerSupplier::where('customer_id', $customerId)->get();
                    foreach ($customerSuppliers as $customerSupplier) {
                        $supplier = Supplier::find($customerSupplier->supplier_id);
                        if ($supplier && $supplier->connected_customer == $loggedCustomerId) {
                            $shipperId = $supplier->id;
                            break;
                        }
                    }
                    if(empty($shipperId)) {
                        $supplier = $this->createSupplierAndMappingFromCustomer($loggedCustomerId, $customerId);
                        $shipperId = $supplier->id;
                    }
                } else {
                    $buyerId = $request['consignee_id'];
                }
            }
        } else {
            // if selected role is shipper then received connected customer id in shipper_id else received Supplier id in shipper_id
            if($request['role'] === 'shipper') {
                $buyerId = $request['consignee_id'];
            } else {
                $supplier = \App\Supplier::find($request['shipper_id']);
                if (isset($supplier->id) && $supplier->connected_customer) {
                    $customerId = $supplier->connected_customer;
                    $customerBuyers = CustomerBuyer::where('customer_id', $customerId)->get();
                    foreach ($customerBuyers as $customerBuyer) {
                        $buyer = Buyer::find($customerBuyer->buyer_id);
                        if ($buyer && $buyer->connected_customer == $loggedCustomerId) {
                            $buyerId = $buyer->id;
                            break;
                        }
                    }
                    if(empty($buyerId)) {
                        $buyer = $this->createBuyerAndMappingFromCustomer($loggedCustomerId, $customerId);
                        $buyerId = $buyer->id;
                    }
                } else {
                    $shipperId = $request['shipper_id'];
                }
            }
        }
        return ['shipperId' => $shipperId, 'consigneeId' => $buyerId];
    }

    private function getShipperId($consigneeId, $shipmentCustomerId) {
        $shipperId = null;
        $loggedInCustomerId = $this->getCurrentSelectedCustomer();
        $buyer = \App\Buyer::find($consigneeId);
        if (isset($buyer->id) && $buyer->connected_customer) {
            $customerId = $buyer->connected_customer;
            $customerSuppliers = CustomerSupplier::where('customer_id', $customerId)->get();
            foreach ($customerSuppliers as $customerSupplier) {
                $supplier = Supplier::find($customerSupplier->supplier_id);
                if ($supplier && $supplier->connected_customer == $shipmentCustomerId) {
                    $shipperId = $supplier->id;
                    break;
                }
            }
            if (empty($shipperId)) {
                $supplier = $this->createSupplierAndMappingFromCustomer($loggedInCustomerId, $customerId);
                $shipperId = $supplier->id;
            }
        } else {
            $supplier = $this->createSupplierAndMappingFromCustomer($loggedInCustomerId, $shipmentCustomerId);
            $shipperId = !empty($supplier) ? $supplier->id : null;
        }
        return $shipperId;
    }

    private function getConsigneeId($shipperId, $shipmentCustomerId) {
        $buyerId = null;
        $loggedInCustomerId = $this->getCurrentSelectedCustomer();
        $supplier = \App\Supplier::find($shipperId);
        if (isset($supplier->id) && $supplier->connected_customer) {
            $customerId = $supplier->connected_customer;
            $customerBuyers = CustomerBuyer::where('customer_id', $customerId)->get();
            foreach ($customerBuyers as $customerBuyer) {
                $buyer = Buyer::find($customerBuyer->buyer_id);
                if ($buyer && $buyer->connected_customer == $shipmentCustomerId) {
                    $buyerId = $buyer->id;
                    break;
                }
            }
            if (empty($buyerId)) {
                $buyer = $this->createBuyerAndMappingFromCustomer($loggedInCustomerId, $customerId);
                $buyerId = $buyer->id;
            }
        } else {
            $buyer = $this->createBuyerAndMappingFromCustomer($loggedInCustomerId, $shipmentCustomerId);
            $buyerId = !empty($buyer) ? $buyer->id : null;
        }
        return $buyerId;
    }

    private function createSupplierAndMappingFromCustomer($loggedCustomerId, $customerId) {
        if(intval($loggedCustomerId) !== intval($customerId)){
            $customer = Customer::find($loggedCustomerId);
            $supplierData = [
                'company_name' => $customer->company_name,
                'address' => $customer->address,
                'phone' => $customer->phone,
                'emails' => json_encode($customer->emails),
                'connected_customer' => $loggedCustomerId,
                'connection_generated' => 1
            ];
            $supplier = Supplier::create($supplierData);
            CustomerSupplier::create([
                'customer_id' => $customerId,
                'supplier_id' => $supplier->id
            ]);
            return $supplier;
        } else {
            return NULL;
        }
    }

    private function createBuyerAndMappingFromCustomer($loggedCustomerId, $customerId) {
        if(intval($loggedCustomerId) !== intval($customerId)) {
            $customer = Customer::find($loggedCustomerId);
            $buyerData = [
                'company_name' => $customer->company_name,
                'address' => $customer->address,
                'phone' => $customer->phone,
                'emails' => json_encode($customer->emails),
                'connected_customer' => $loggedCustomerId,
                'connection_generated' => 1
            ];
            $buyer = Buyer::create($buyerData);
            CustomerBuyer::create([
                'customer_id' => $customerId,
                'buyer_id' => $buyer->id
            ]);
            return $buyer;
        } else {
            return NULL;
        }
    }

    private function getForwarderEmails($forwarders) {
        $forwarderEmails = [];
            if(is_array($forwarders)) {
                foreach($forwarders as $forwarder) {
                    if(isset($forwarder->email))
                        array_push($forwarderEmails, $forwarder->email);
                }
        }
        return $forwarderEmails;
    }

    private function getShipperCustomer($shipperId) {
        $shipperCustomer = null;
        $supplier = \App\Supplier::find($shipperId);
        if (isset($supplier->id)) {
            $shipperCustomer =  \App\Customer::find($supplier->connected_customer);
        }
        return $shipperCustomer;
    }

    private function getConsigneeCustomer($consigneeId) {
        $consigneeCustomer = null;
        $buyer = \App\Buyer::find($consigneeId);
        if (isset($buyer->id)) {
            $consigneeCustomer =  \App\Customer::find($buyer->connected_customer);
        }
        return $consigneeCustomer;
    }

    private function sendShipmentBookingEmailsForCustomerCreateBookingWithShifl($shipment, $content, $shipperId, $consigneeId, $forwarderEmails, $customerId, $forwarders) {
        $loggedInCustomerId = $this->getCurrentSelectedCustomer();
        $customer =  \App\Customer::find($loggedInCustomerId);
        $companyName = $customer->company_name;
        $shipperCustomer = $this->getShipperCustomer($shipperId);
        $shipperCustomerEmails = !empty($shipperCustomer) && !empty($shipperCustomer->emails) ? $shipperCustomer->emails : [];
        // If customer is not connected then get emails from the contact
        $shipperCustomerEmails = count($shipperCustomerEmails) > 0 ? $shipperCustomerEmails : $this->getSupplierEmails($shipperId);

        $consigneeCustomer = $this->getConsigneeCustomer($consigneeId);
        $consigneeCustomerEmails = $emails = !empty($consigneeCustomer) && !empty($consigneeCustomer->emails) ? $consigneeCustomer->emails : [];
        // If customer is not connected then get emails from the contact
        $consigneeCustomerEmails = count($consigneeCustomerEmails) > 0 ? $consigneeCustomerEmails : $this->getBuyerEmails($consigneeId);
        //if does not have connected customer then check with mode if mode is shipper then consider shipper is the customer
        $isShipperHaveCustomer = isset($shipperCustomer->id) && $shipperCustomer->id == $customerId;
        if($isShipperHaveCustomer) {
            $shipperSubject = "Booking Request Received";
            $consigneeSubject = "Booking Request Received";
            $shipperDescription = "We have received your booking request. Our team will review your request and get back with a quote. Below are the details of your request:";
            $consigneeDescription = "We have received a booking request where you’ve been selected as a party. Please review and confirm the CRD and the booking details shown below:";
        } else {
            $shipperSubject = "Booking Request Received";
            $consigneeSubject = "Booking Request Received";
            $shipperDescription = "We have received a booking request where you’ve been selected as a party. Please review and confirm the CRD and the booking details shown below:";
            $consigneeDescription = "We have received your booking request. Our team will review your request and get back with a quote. Below are the details of your request";
        }

        $content['role'] = 'shipper';
        $content['email_subject'] = $shipperSubject;
        $content['email_description'] = $shipperDescription;
        $content['email_to'] = $shipperCustomerEmails;
        $content['email_cc'] = ['levan@shifl.com'];
        $content['forwarders'] = $forwarders;
        $content['customer_reference_number'] = isset($shipment->customer_reference_number) ? $shipment->customer_reference_number : '';
        event(new SendForwarderEmailEvent($shipment, $content));

        $content['role'] = 'consignee';
        $content['email_subject'] = $consigneeSubject;
        $content['email_description'] = $consigneeDescription;
        $content['email_to'] = $consigneeCustomerEmails;
        $content['email_cc'] = ['levan@shifl.com'];
        $content['forwarders'] = $forwarders;
        $content['customer_reference_number'] = isset($shipment->customer_reference_number) ? $shipment->customer_reference_number : '';
        event(new SendForwarderEmailEvent($shipment, $content));

        foreach($forwarderEmails as $forwarderEmail) {
            $content['email_subject'] = 'New Booking Request';
            $content['email_description'] = $companyName . ' has submitted a booking request. Below are the details of their request:';
            $content['email_to'] = [$forwarderEmail];
            $content['email_cc'] = ['levan@shifl.com', 'teams@shifl.com'];
            /* If office from is shifl USA office then add cc email; 1 = Shifl USA office id */
            if($shipment->shifl_office_origin_from_id == 1) {
                array_push($content['email_cc'], 'booking@shifl.com');
            }
            $content['customer_reference_number'] = '';
            $content['forwarders'] = [];
            $content['notify_info'] = $content['notify_details_info'];
            event(new SendForwarderEmailEvent($shipment, $content));
        }
    }

    private function sendShipmentBookingEmailsForOtherPartyCreateBookingWithShifl($shipment, $content, $shipperId, $consigneeId, $forwarderEmails, $customerId, $forwarders) {
        $loggedInCustomerId = $this->getCurrentSelectedCustomer();
        $customer =  \App\Customer::find($loggedInCustomerId);
        $companyName = $customer->company_name;
        $shipperCustomer = $this->getShipperCustomer($shipperId);
        $shipperCustomerEmails = !empty($shipperCustomer) && !empty($shipperCustomer->emails) ? $shipperCustomer->emails : [];
        // If customer is not connected then get emails from the contact
        $shipperCustomerEmails = count($shipperCustomerEmails) > 0 ? $shipperCustomerEmails : $this->getSupplierEmails($shipperId);

        $consigneeCustomer = $this->getConsigneeCustomer($consigneeId);
        $consigneeCustomerEmails = $emails = !empty($consigneeCustomer) && !empty($consigneeCustomer->emails) ? $consigneeCustomer->emails : [];
        // If customer is not connected then get emails from the contact
        $consigneeCustomerEmails = count($consigneeCustomerEmails) > 0 ? $consigneeCustomerEmails : $this->getBuyerEmails($consigneeId);
        //if does not have connected customer then check with mode if mode is shipper then consider shipper is the customer
        $isShipperHaveCustomer = isset($shipperCustomer->id) && $shipperCustomer->id == $customerId;
        if($isShipperHaveCustomer) {
            $shipperSubject = "Booking Request Received";
            $consigneeSubject = "Booking Request Received";
            $shipperDescription = "We have received a booking request on your behalf from $companyName Our team will review your request and get back with a quote. Please review the booking details shown below:";
            $consigneeDescription = "We have received your booking request. Our team will review your request and inform the concerned party with a quote. Below are the details of your request:";
        } else {
            $shipperSubject = "Booking Request Received";
            $consigneeSubject = "Booking Request Received";
            $shipperDescription = "We have received your booking request. Our team will review your request and inform the concerned party with a quote. Below are the details of your request:";
            $consigneeDescription = "We have received a booking request on your behalf from $companyName Our team will review your request and get back with a quote. Please review the booking details shown below:";
        }

        $content['role'] = 'shipper';
        $content['email_subject'] = $shipperSubject;
        $content['email_description'] = $shipperDescription;
        $content['email_to'] = $shipperCustomerEmails;
        $content['email_cc'] = ['levan@shifl.com'];
        $content['customer_reference_number'] = isset($shipment->customer_reference_number) ? $shipment->customer_reference_number : '';
        $content['forwarders'] = $forwarders;
        event(new SendForwarderEmailEvent($shipment, $content));

        $content['role'] = 'consignee';
        $content['email_subject'] = $consigneeSubject;
        $content['email_description'] = $consigneeDescription;
        $content['email_to'] = $consigneeCustomerEmails;
        $content['email_cc'] = ['levan@shifl.com'];
        $content['customer_reference_number'] = isset($shipment->customer_reference_number) ? $shipment->customer_reference_number : '';
        $content['forwarders'] = $forwarders;
        event(new SendForwarderEmailEvent($shipment, $content));

        foreach($forwarderEmails as $forwarderEmail) {
            $content['email_subject'] = 'New Booking Request';
            $content['email_description'] = $companyName . ' has submitted a booking request. Below are the details of their request:';
            $content['email_to'] = [$forwarderEmail];
            $content['email_cc'] = ['levan@shifl.com', 'teams@shifl.com'];
            /* If office from is shifl USA office then add cc email; 1 = Shifl USA office id */
            if($shipment->shifl_office_origin_from_id == 1) {
                array_push($content['email_cc'], 'booking@shifl.com');
            }
            $content['customer_reference_number'] = '';
            $content['forwarders'] = [];
            $content['notify_info'] = $content['notify_details_info'];
            event(new SendForwarderEmailEvent($shipment, $content));
        }
    }

    private function sendShipmentBookingEmailsForCustomerCreateBookingWithOtherForwarder($shipment, $content, $shipperId, $consigneeId, $forwarderEmails, $customerId, $forwarders) {
        $loggedInCustomerId = $this->getCurrentSelectedCustomer();
        $customer =  \App\Customer::find($loggedInCustomerId);
        $shipmentCustomer = \App\Customer::find($customerId);
        $companyName = $customer->company_name;
        $shipperCustomer = $this->getShipperCustomer($shipperId);
        $shipperCustomerEmails = !empty($shipperCustomer) && !empty($shipperCustomer->emails) ? $shipperCustomer->emails : [];
        // If customer is not connected then get emails from the contact
        $shipperCustomerEmails = count($shipperCustomerEmails) > 0 ? $shipperCustomerEmails : $this->getSupplierEmails($shipperId);

        $consigneeCustomer = $this->getConsigneeCustomer($consigneeId);
        $consigneeCustomerEmails = $emails = !empty($consigneeCustomer) && !empty($consigneeCustomer->emails) ? $consigneeCustomer->emails : [];
        // If customer is not connected then get emails from the contact
        $consigneeCustomerEmails = count($consigneeCustomerEmails) > 0 ? $consigneeCustomerEmails : $this->getBuyerEmails($consigneeId);
        //if does not have connected customer then check with mode if mode is shipper then consider shipper is the customer
        $isShipperHaveCustomer = isset($shipperCustomer->id) && $shipperCustomer->id == $customerId;
        if($isShipperHaveCustomer) {
            $shipperSubject = "Booking Request Sent";
            $consigneeSubject = "Booking Request Sent";
            $shipperDescription = "Your booking request has been sent to the selected forwarder(s). Below are the details of your request:";
            $consigneeDescription = "A booking request has been sent to the selected forwarder(s) where you’ve been selected as a party. Please review and confirm the CRD and the booking details shown below:";
        } else {
            $shipperSubject = "Booking Request Sent";
            $consigneeSubject = "Booking Request Sent";
            $shipperDescription = "A booking request has been sent to the selected forwarder(s) where you’ve been selected as a party. Please review and confirm the CRD and the booking details shown below:";
            $consigneeDescription = "Your booking request has been sent to the selected forwarder(s). Below are the details of your request:";
        }

        $content['role'] = 'shipper';
        $content['email_subject'] = $shipperSubject;
        $content['email_description'] = $shipperDescription;
        $content['email_to'] = $shipperCustomerEmails;
        $content['email_cc'] = ['levan@shifl.com'];
        $content['customer_reference_number'] = isset($shipment->customer_reference_number) ? $shipment->customer_reference_number : '';
        $content['forwarders'] = $forwarders;
        event(new SendForwarderEmailEvent($shipment, $content));

        $content['role'] = 'consignee';
        $content['email_subject'] = $consigneeSubject;
        $content['email_description'] = $consigneeDescription;
        $content['email_to'] = $consigneeCustomerEmails;
        $content['email_cc'] = ['levan@shifl.com'];
        $content['customer_reference_number'] = isset($shipment->customer_reference_number) ? $shipment->customer_reference_number : '';
        $content['forwarders'] = $forwarders;
        event(new SendForwarderEmailEvent($shipment, $content));

        $companyEmail = $shipmentCustomer->emails;
        $companyEmail = is_array($companyEmail) && count($companyEmail) > 0 ? $companyEmail[0]['email'] : '';
        foreach($forwarderEmails as $forwarderEmail) {
            $content['email_subject'] = 'You’ve received a new Booking Request';
            $content['email_description'] = $companyName . ' has submitted a booking request to you. Below are the details of their request: ';
            $content['email_to'] = [$forwarderEmail];
            $content['email_cc'] = ['levan@shifl.com'];
            $content['customer_reference_number'] = '';
            $content['forwarders'] = [];
            $content['notify_info'] = $content['notify_details_info'];
            $content['company_name'] = ucwords($shipmentCustomer->company_name);
            $content['company_phone'] = $shipmentCustomer->phone;
            $content['company_email'] = $companyEmail;
            event(new SendForwarderEmailEvent($shipment, $content));
        }
    }

    private function sendShipmentBookingEmailsForOtherPartyCreateBookingWithOtherForwarder($shipment, $content, $shipperId, $consigneeId, $forwarderEmails, $customerId, $forwarders) {
        $loggedInCustomerId = $this->getCurrentSelectedCustomer();
        $customer =  \App\Customer::find($loggedInCustomerId);
        $companyName = $customer->company_name;
        $shipperCustomer = $this->getShipperCustomer($shipperId);
        $shipmentCustomer = \App\Customer::find($customerId);
        $shipperCustomerEmails = !empty($shipperCustomer) && !empty($shipperCustomer->emails) ? $shipperCustomer->emails : [];
        // If customer is not connected then get emails from the contact
        $shipperCustomerEmails = count($shipperCustomerEmails) > 0 ? $shipperCustomerEmails : $this->getSupplierEmails($shipperId);

        $consigneeCustomer = $this->getConsigneeCustomer($consigneeId);
        $consigneeCustomerEmails = $emails = !empty($consigneeCustomer) && !empty($consigneeCustomer->emails) ? $consigneeCustomer->emails : [];
        // If customer is not connected then get emails from the contact
        $consigneeCustomerEmails = count($consigneeCustomerEmails) > 0 ? $consigneeCustomerEmails : $this->getBuyerEmails($consigneeId);
        //if does not have connected customer then check with mode if mode is shipper then consider shipper is the customer
        $isShipperHaveCustomer = isset($shipperCustomer->id) && $shipperCustomer->id == $customerId;
        if($isShipperHaveCustomer) {
            $shipperSubject = "Booking Request Sent";
            $consigneeSubject = "Booking Request Sent";
            $shipperDescription = "A booking request has been sent to the selected forwarder(s) on your behalf by $companyName Please review the booking details shown below:";
            $consigneeDescription = "Your booking request has been sent to the selected forwarder(s). Below are the details of your request:";
        } else {
            $shipperSubject = "Booking Request Sent";
            $consigneeSubject = "Booking Request Sent";
            $shipperDescription = "Your booking request has been sent to the selected forwarder(s). Below are the details of your request:";
            $consigneeDescription = "A booking request has been sent to the selected forwarder(s) on your behalf by $companyName Please review the booking details shown below:";
        }

        $content['role'] = 'shipper';
        $content['email_subject'] = $shipperSubject;
        $content['email_description'] = $shipperDescription;
        $content['email_to'] = $shipperCustomerEmails;
        $content['email_cc'] = ['levan@shifl.com'];
        $content['customer_reference_number'] = isset($shipment->customer_reference_number) ? $shipment->customer_reference_number : '';
        $content['forwarders'] = $forwarders;
        event(new SendForwarderEmailEvent($shipment, $content));

        $content['role'] = 'consignee';
        $content['email_subject'] = $consigneeSubject;
        $content['email_description'] = $consigneeDescription;
        $content['email_to'] = $consigneeCustomerEmails;
        $content['email_cc'] = ['levan@shifl.com'];
        $content['customer_reference_number'] = isset($shipment->customer_reference_number) ? $shipment->customer_reference_number : '';
        $content['forwarders'] = $forwarders;
        event(new SendForwarderEmailEvent($shipment, $content));

        $companyEmail = $shipmentCustomer->emails;
        $companyEmail = is_array($companyEmail) && count($companyEmail) > 0 ? $companyEmail[0]['email'] : '';
        foreach($forwarderEmails as $forwarderEmail) {
            $content['email_subject'] = 'You’ve received a new Booking Request';
            $content['email_description'] = $companyName . ' has submitted a booking request to you. Below are the details of their request: ';
            $content['email_to'] = [$forwarderEmail];
            $content['email_cc'] = ['levan@shifl.com'];
            $content['customer_reference_number'] = '';
            $content['forwarders'] = [];
            $content['notify_info'] = $content['notify_details_info'];
            $content['company_name'] = ucwords($shipmentCustomer->company_name);
            $content['company_phone'] = $shipmentCustomer->phone;
            $content['company_email'] = $companyEmail;
            event(new SendForwarderEmailEvent($shipment, $content));
        }
    }

    private function getBuyerEmails($buyerId) {
        $emails = [];
        $buyer = \App\Buyer::find($buyerId);
        if (isset($buyer->id)) {
            $emails = $buyer->emails ? json_decode($buyer->emails) : [];
        }
        return $emails;
    }

    private function getSupplierEmails($supplierId) {
        $emails = [];
        $supplier = \App\Supplier::find($supplierId);
        if (isset($supplier->id)) {
            $emails = $supplier->emails ? json_decode($supplier->emails) : [];
        }
        return $emails;
    }

    private function getCustomerShiflOfficeUser($customerId, $officeId) {
        $shiflOfficeUser = null;
        $customer = Customer::find($customerId);
        if(isset($customer->id)) {
            $customerOfficeManagers = json_decode($customer->offices_managers);
            foreach ($customerOfficeManagers as $customerOfficeManager) {
                if($customerOfficeManager->office_id == $officeId) {
                    $officeManagerId = $customerOfficeManager->manager_id;
                    $shiflOfficeUser = User::find($officeManagerId);
                }
            }
        }
        return $shiflOfficeUser;
    }

    private function getShiflOfficeFromId($locationFromId) {
        $defaultShiflOfficeId = 1;
        if(empty($locationFromId)) return $defaultShiflOfficeId;
        $shiflOfficeOriginFrom = DB::table('shifl_offices_countries')
            ->select('shifl_office_id')
            ->whereRaw("`country_id` = (SELECT `country_id` FROM `terminal_regions` WHERE `id` = ".intval($locationFromId)." LIMIT 1)")
            ->first();

        return $shiflOfficeOriginFrom ? $shiflOfficeOriginFrom->shifl_office_id : $defaultShiflOfficeId;
    }

    private function getShiflOfficeToId($locationToId) {
        $defaultShiflOfficeId = 1;
        if(empty($locationToId)) return $defaultShiflOfficeId;
        $shiflOfficeOriginTo = DB::table('shifl_offices_countries')
            ->select('shifl_office_id')
            ->whereRaw("`country_id` = (SELECT `country_id` FROM `terminal_regions` WHERE `id` = ".intval($locationToId)." LIMIT 1)")
            ->first();

        return $shiflOfficeOriginTo ? $shiflOfficeOriginTo->shifl_office_id : $defaultShiflOfficeId;
    }

    private function updateContainerDataInTable ($containers, $shipmentId) {
        $setContainers = [];
        if (is_array($containers)) {
            $selectedContainers = [];
            //map all current container id
            foreach ($containers as $getContainer) {
                array_push($selectedContainers, $getContainer['id']);
            }

            if (count($selectedContainers) > 0) {
                //delete containers data that not are not listed
                \DB::table('containers')
                    ->whereNotIn('unique_id', $selectedContainers)
                    ->where('shipment_id', $shipmentId)
                    ->delete();
            }

            foreach ($containers as $key => $getContainer) {
                $checkContainer = \DB::table('containers')
                    ->where('unique_id', $getContainer['id'])
                    ->where('shipment_id', $shipmentId)
                    ->first();

                if (!isset($checkContainer->id)) {
                    array_push($setContainers, ['shipment_id' => $shipmentId, 'unique_id' => $getContainer['id'], 'size' => intval($getContainer['size']), 'cbm' => intval($getContainer['cbm']), 'kg' => intval($getContainer['kg']), 'cartons' => intval($getContainer['cartons']), 'container_num' => $getContainer['container_num'], 'seal_num' => isset($getContainer['seal_num']) ? $getContainer['seal_num'] : null]);
                } else {
                    \DB::table('containers')
                        ->where('id', $checkContainer->id)
                        ->update(['shipment_id' => $shipmentId, 'size' => intval($getContainer['size_id']), 'cbm' => intval($getContainer['cbm']), 'kg' => intval($getContainer['kg']), 'cartons' => intval($getContainer['cartons']), 'container_num' => $getContainer['container_num'], 'seal_num' => isset($getContainer['seal_num']) ? $getContainer['seal_num'] : null]);
                }
            }
        } else {
            //delete containers data that not are not listed
            \DB::table('containers')
                ->where('shipment_id', $shipmentId)
                ->delete();
        }
        if (isset($setContainers) && count($setContainers) > 0) {
            \DB::table('containers')->insert($setContainers);
        }
    }

    private function insertManualOrderDocuments($shipmentId, $manualPurchaseOrders, $role, $shipperId, $consigneeId) {
        $manualSupplierId = '';
        $manualBuyerId = '';
        if ($role === 'consignee') {
            $manualSupplierId = $shipperId;
            $manualBuyerId = '';
        } else {
            $manualSupplierId = '';
            $manualBuyerId = $consigneeId;
        }

        if (count($manualPurchaseOrders) > 0) {
            $suppliers = [];
            foreach ($manualPurchaseOrders as $manualPurchaseOrder) {
                if (isset($manualPurchaseOrder['documents'])) {
                    $document_infos = $manualPurchaseOrder['documents'];
                    if (count($document_infos) > 0) {
                        //submit now the documents
                        foreach ($document_infos as $key => $document_info) {
                            if(!(isset($document_info['id']) && !empty($document_info['id']))) {
                                $data = [
                                    'name' => $document_info['name'],
                                    'type' => $document_info['type'],
                                    'shipment_id' => $shipmentId,
                                    'is_added_by_customer' => 1,
                                    'path' => '',
                                    'supplier_ids' => [$manualSupplierId],
                                    'file' => $document_info['file'],
                                    'has_file' => true
                                ];
                                //insert document through event
                                event(new InsertDocumentEvent($data));
                            } else {
                                // update document type
                                $docId = $document_info['id'];
                                \DB::table('documents')
                                    ->where('id', $docId)
                                    ->update(['type' => $document_info['type']]);
                            }
                        }
                    }
                }
            }
        }
    }

    private function buildSuppliersData($autoPurchaseOrders, $manualPurchaseOrders, $shipperId, $role, $inconTermId, $consigneeId, $requestData, $customerId) {
        $suppliers = [];
        $supplierShipperId = !empty($shipperId) ? intval($shipperId) : null;
        $supplierBuyerId = !empty($consigneeId) ? intval($consigneeId) : null;

        if (count($autoPurchaseOrders) > 0 ) {
            try {
                foreach ($autoPurchaseOrders as $autoPurchaseOrder) {
                    if($role === 'shipper' ) {
                        array_push($suppliers,
                            [
                                "id"=> strval(hexdec(uniqid())),
                                "hbl_copy"=> null,
                                "packing_list"=> null,
                                "commercial_documents"=> null,
                                "commercial_invoice"=> null,
                                "po_id"=> intval($autoPurchaseOrder['purchase_order_id']),
                                "po_num"=> null,
                                "volume"=> "",
                                "supplier_id"=> $supplierShipperId,
                                "buyer_id"=> $supplierBuyerId,
                                "cargo_ready_date" => isset($autoPurchaseOrder['cargo_ready_date']) ? $autoPurchaseOrder['cargo_ready_date']  : '',
                                "kg" => isset($requestData['total_weight']) ? $requestData['total_weight'] : '',
                                "cbm" => isset($requestData['total_volume']) ? $requestData['total_volume'] : '',
                                "incoterm_id"=> intval($inconTermId),
                                "hbl_num"=> "",
                                "product_description"=> isset($requestData['commodities_info']) ? $requestData['commodities_info'] : '',
                                "total_cartons" => isset($requestData['total_cartons']) ? $requestData['total_cartons'] : '',
                                "bl_status"=> "Pending",
                                "ams_num"=> "",
                                "containers"=> [],
                                "customerHasPOs"=> true,
                                "isOpenPurchaseOrdersItemsModal"=> false,
                                "customerHasSOs"=> 1,
                                "obl_filled"=> false,
                                "commercial_documents_filled"=> true,
                                "name"=> "",
                                'marks' => isset($requestData['marks']) ? $requestData['marks'] : "",
                                "bol_shipper_address" => isset($requestData['shipper_details_info']) ? $requestData['shipper_details_info']  : '',
                                "bol_consignee_address" => isset($requestData['consignee_details_info']) ? $requestData['consignee_details_info']  : '',
                                "bol_notify_address" => isset($requestData['notify_details_info']) ? $requestData['notify_details_info']  : '',
                                "bol_notify_party" => isset($requestData['notify_party']) ? $requestData['notify_party'] : '',
                            ]);
                    } else {
                        array_push($suppliers, [
                            "id" => strval(hexdec(uniqid())),
                            "hbl_copy" => null,
                            "packing_list" => null,
                            "commercial_documents" => null,
                            "commercial_invoice" => null,
                            "po_id" => intval($autoPurchaseOrder['purchase_order_id']),
                            "cargo_ready_date" => isset($autoPurchaseOrder['cargo_ready_date']) ? $autoPurchaseOrder['cargo_ready_date']  : '',
                            "po_num" => '',
                            "volume" => '',
                            "supplier_id"=> $supplierShipperId,
                            "buyer_id"=> $supplierBuyerId,
                            "kg" => isset($requestData['total_weight']) ? $requestData['total_weight'] : '',
                            "cbm" => isset($requestData['total_volume']) ? $requestData['total_volume'] : '',
                            "incoterm_id" => intval($inconTermId),
                            "hbl_num" => '',
                            "product_description" => isset($requestData['commodities_info']) ? $requestData['commodities_info'] : '',
                            "bol_shipper_address" => isset($requestData['shipper_details_info']) ? $requestData['shipper_details_info']  : '',
                            "bol_shipper_phone_number" => '',
                            "bol_consignee_address" => isset($requestData['consignee_details_info']) ? $requestData['consignee_details_info']  : '',
                            "bol_consignee_phone_number" => '',
                            "bol_notify_party" => isset($requestData['notify_party']) ? $requestData['notify_party'] : '',
                            "bol_notify_address" => isset($requestData['notify_details_info']) ? $requestData['notify_details_info']  : '',
                            "bol_notify_phone_number" => '',
                            "total_cartons" => isset($requestData['total_cartons']) ? $requestData['total_cartons'] : '',
                            "bl_status" => "Pending",
                            "ams_num" => '',
                            "containers" => [],
                            'marks' => isset($requestData['marks']) ? $requestData['marks'] : "",
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        }

        if(is_array($manualPurchaseOrders) && count($manualPurchaseOrders) > 0) {
            $suppliers = [];
            foreach($manualPurchaseOrders as $manualPurchaseOrder) {
                array_push($suppliers, [
                    "id" => strval($manualPurchaseOrder['id']),
                    "hbl_copy" => null,
                    "packing_list" => null,
                    "commercial_documents" => null,
                    "commercial_invoice" => null,
                    "po_id" => '',
                    "cargo_ready_date" => isset($manualPurchaseOrder['cargo_ready_date']) ? $manualPurchaseOrder['cargo_ready_date']  : '',
                    "po_num" => $manualPurchaseOrder['purchase_order_number'],
                    "volume" => floatval($manualPurchaseOrder['total_volumes']),
                    "supplier_id" => $supplierShipperId,
                    "buyer_id" => $supplierBuyerId,
                    "kg" => isset($manualPurchaseOrder['total_weights']) ? floatval($manualPurchaseOrder['total_weights']) : 0,
                    "cbm" => isset($manualPurchaseOrder['total_volumes']) ? floatval($manualPurchaseOrder['total_volumes']) : 0,
                    "incoterm_id" => intval($inconTermId),
                    "hbl_num" => '',
                    "product_description" => isset($requestData['commodities_info']) ? $requestData['commodities_info'] : '',
                    "bol_shipper_address" => isset($requestData['shipper_details_info']) ? $requestData['shipper_details_info']  : '',
                    "bol_shipper_phone_number" => '',
                    "bol_consignee_address" => isset($requestData['consignee_details_info']) ? $requestData['consignee_details_info']  : '',
                    "bol_consignee_phone_number" => '',
                    "bol_notify_party" => isset($requestData['notify_party']) ? $requestData['notify_party'] : '',
                    "bol_notify_address" => isset($requestData['notify_details_info']) ? $requestData['notify_details_info']  : '',
                    "bol_notify_phone_number" => '',
                    "total_cartons" => isset($manualPurchaseOrder['total_cartons']) ? intval($manualPurchaseOrder['total_cartons']) : 0,
                    "bl_status" => "Pending",
                    "ams_num" => '',
                    "containers" => [],
                    'marks' => isset($requestData['marks']) ? $requestData['marks'] : "",
                ]);
            }
        }
        /* If purchase order is not available then add default supplier */
        if(count($autoPurchaseOrders) == 0 && count($manualPurchaseOrders) == 0) {
            $supplier = [
                "id"=> strval(hexdec(uniqid())),
                "hbl_copy"=> null,
                "packing_list"=> null,
                "commercial_documents"=> null,
                "commercial_invoice"=> null,
                "po_id"=> '',
                "po_num"=> null,
                "volume"=> "",
                "supplier_id"=> $supplierShipperId,
                "buyer_id"=> $supplierBuyerId,
                "cargo_ready_date" => '',
                "kg" => isset($requestData['total_weight']) ? $requestData['total_weight']  : '',
                "cbm" => isset($requestData['total_volume']) ? $requestData['total_volume']  : '',
                "incoterm_id"=> intval($inconTermId),
                "hbl_num"=> "",
                "product_description"=> isset($requestData['commodities_info']) ? $requestData['commodities_info'] : '',
                "total_cartons" => isset($requestData['total_cartons']) ? $requestData['total_cartons']  : '',
                "bl_status"=> "Pending",
                "ams_num"=> "",
                "containers"=> [],
                "customerHasPOs"=> true,
                "isOpenPurchaseOrdersItemsModal"=> false,
                "customerHasSOs"=> 1,
                "obl_filled"=> false,
                "commercial_documents_filled"=> true,
                "name"=> "",
                'marks' => isset($requestData['marks']) ? $requestData['marks']  : '',
                'bol_consignee_address' => isset($requestData['consignee_details_info']) ? $requestData['consignee_details_info']  : '',
                'bol_shipper_address' => isset($requestData['shipper_details_info']) ? $requestData['shipper_details_info']  : '',
                'bol_notify_address' => isset($requestData['notify_details_info']) ? $requestData['notify_details_info']  : '',
                "bol_notify_party" => isset($requestData['notify_party']) ? $requestData['notify_party'] : '',
            ];

            array_push($suppliers, $supplier);
        }
        return $suppliers;
    }

    private function buildAutoPurchaseOrders($autoPurchaseOrders) {
        if (count($autoPurchaseOrders) > 0) {
            try {
                //create hbl section
                foreach ($autoPurchaseOrders as $key => $autoPurchaseOrder) {
                    $autoPurchaseOrders[$key]['purchase_order_id'] = intval($autoPurchaseOrder['purchase_order_id']);
                    $autoPurchaseOrders[$key]['supplier_id'] = empty($autoPurchaseOrder['supplier_id']) || $autoPurchaseOrder['supplier_id'] == 'null' ? null : intval($autoPurchaseOrder['supplier_id']);
                    $autoPurchaseOrders[$key]['buyer_id'] = empty($autoPurchaseOrder['buyer_id']) || $autoPurchaseOrder['buyer_id'] == 'null' ? null : intval($autoPurchaseOrder['buyer_id']) ;
                    if (isset($autoPurchaseOrder['products'])) {
                        foreach ($autoPurchaseOrder['products'] as $keySecond => $product) {
                            if (isset($product['id']) && $product['id'] !== null) {
                                $autoPurchaseOrders[$key]['products'][$keySecond]['is_shipment'] = true;
                                $autoPurchaseOrders[$key]['products'][$keySecond]['amount'] = floatval($product['amount']);
                                $autoPurchaseOrders[$key]['products'][$keySecond]['carton'] = intval($product['carton']);
                                $autoPurchaseOrders[$key]['products'][$keySecond]['id'] = intval($product['id']);
                                $autoPurchaseOrders[$key]['products'][$keySecond]['product_id'] = intval($product['product_id']);
                                $autoPurchaseOrders[$key]['products'][$keySecond]['ship_cartons'] = intval($product['ship_cartons']);
                                $autoPurchaseOrders[$key]['products'][$keySecond]['unit'] = intval($product['unit']);
                                $autoPurchaseOrders[$key]['products'][$keySecond]['unit_price'] = floatval($product['unit_price']);
                                $autoPurchaseOrders[$key]['products'][$keySecond]['units_per_carton'] = isset($product['units_per_carton']) ? intval($product['units_per_carton']) : null;;
                                $autoPurchaseOrders[$key]['products'][$keySecond]['unship_cartons'] = intval($product['unship_cartons']);
                                $autoPurchaseOrders[$key]['products'][$keySecond]['volume'] = floatval($product['volume']);
                                $autoPurchaseOrders[$key]['products'][$keySecond]['weight'] = floatval($product['weight']);
                            } else {
                                unset($autoPurchaseOrders[$key]['products'][$keySecond]);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
        }
        return $autoPurchaseOrders;
    }

    private function buildContainersData($containers) {
        $finalContainers = [];
        if(is_array($containers)) {
            foreach ($containers as $key => $container) {
                if ($container['checked'] && $container['checked'] == 'true') {
                    $containerUniqueId = strval(hexdec(uniqid()));
                    array_push($finalContainers, [
                        "id" => $containerUniqueId,
                        "container_num" => '',
                        "size" => intval($container['size_id']),
                        "cbm" => null,
                        "kg" => null,
                        "cartons" => intval($container['how_many']),
                        "seal_num" => null
                    ]);
                }
            }
        }
        return $finalContainers;
    }

    public function buildContainersMetas($containers) {
        $containerMetas = [];
        if(is_array($containers)) {
            foreach($containers as $key => $container) {
                if ($container['checked'] && $container['checked'] == 'true') {
                    $containerUniqueId = strval(hexdec(uniqid()));
                    array_push($containerMetas, [
                        "unique_id" => $containerUniqueId,
                        "container_num" => '',
                        "size" => $container['size_id'],
                        "cbm" => null,
                        "kg" => null,
                        "cartons" => intval($container['how_many']),
                        "how_many" => $container['how_many']
                    ]);
                }
            }
        }
        return $containerMetas;
    }

    private function getCustomerId($request) {
        $customerId = $this->getCurrentSelectedCustomer();
        // if inco_term are "FOB", "EXW" then make Consignee is customer otherwise Shipper is customer
        // Note: if inco_term "FOB", "EXW" then Consignee is responsible for the pay ocean freight otherwise shipper pay for that
        if($request['inco_term'] === 'FOB' || $request['inco_term'] === 'EXW') {
            // if selected role is consignee then received connected customer id in consignee_id else received Buyer id in consignee_id
            if($request['role'] === 'consignee') {
                $customerId = $request['consignee_id'];
            } else {
                $buyer = \App\Buyer::find($request['consignee_id']);
                if (isset($buyer->id) && $buyer->connected_customer) {
                    $customerId = $buyer->connected_customer;
                } else {
                    $customerId = $request['shipper_id'];
                }
            }
        } else {
            // if selected role is shipper then received connected customer id in shipper_id else received Supplier id in shipper_id
            if($request['role'] === 'shipper') {
                $customerId = $request['shipper_id'];
            } else {
                $supplier = \App\Supplier::find($request['shipper_id']);
                if (isset($supplier->id) && $supplier->connected_customer) {
                    $customerId = $supplier->connected_customer;
                } else {
                    $customerId = $request['consignee_id'];
                }
            }
        }
        return $customerId;
    }

    private function buildScheduleBookingFromRequest($request, $oldSchedules) {
        $schedules = [];
        foreach ($oldSchedules as $schedule) {
            $schedule['location_from'] = intval($request['location_from_id']);
            $schedule['location_to'] = intval($request['location_to_id']);
            $schedule['mode'] =  $request['mode'];
            $schedule['type'] = $request['type'];
            array_push($schedules, $schedule);
        }
        return $schedules;
    }

    private function getIncotermByName($name) {
        $name = $name === 'EXW' ? 'Exworks' : $name;
        return Incoterm::where('name', $name)->select('id')->first();
    }

    private function getShippingBookingDocumentTypes() {
        return [
            [
                "id" => 0,
                "name" => 'Commercial Invoice'
            ],
            [
                "id" => 1,
                "name" => 'Packing List'
            ],
            [
                "id" => 2,
                "name" => 'Invoice and Packing List'
            ],
            [
                "id" => 3,
                "name" => 'OBL Document'
            ],
            [
                "id" => 4,
                "name" => 'Other Commercial Docs'
            ],
            [
                "id" => 5,
                "name" => 'Delivery Order'
            ],
            [
                "id" => 6,
                "name" => 'Other'
            ],
        ];
    }

    /**
     *
     * UnSnoozeShipment
     *
     *
     * @bodyParam shipment_id int required
     *
     * @response status=200 {
     *      "status": "ok"
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function unSnoozeShipment(Request $request)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();

        $validator = Validator::make($request->all(), [
            'shipment_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors(),
                'status' => 'not ok'
            ]
            ,401);
        } else {

            $args = $request->all();
            $find_shipment = Shipment::where('id', $args['shipment_id'])
                                     ->whereHas('customer', function($q) use ($customers) {
                                        $q->whereIn('id', $customers);
                                     })->first();


            if ( isset($find_shipment->id)) {
                \DB::table('shipments')->where([
                    'id' => $args['shipment_id']
                ])->update([
                    'snooze_date_at' => null
                ]);

                return response()->json([
                    'status' => 'ok'
                ]);
            } else {
                return response()->json([
                    'errors' => [
                        'shipment_id' => ['Please make sure that shipment exists or you are associated on that shipment.']
                    ],
                    'status' => 'not ok'
                ], 401);
            }
        }
    }

    private function autoFilledToDos(&$suppliers, &$shipment, $flag) {

        if ( $flag === 0) {
            $shipment->obl_filled = true;
        }
        $shipment->commercial_documents_filled = true;

        if (count($suppliers) > 0) {
            foreach ($suppliers as $key => $supplier) {
                if ( $flag === 0 ) {
                    $suppliers[$key]->obl_filled = true;
                }
                $suppliers[$key]->commercial_documents_filled = true;
            }
        }
    }

    /**
     * Snooze Shipment
     *
     * @bodyParam shipment_id int required. Example: 2
     * @bodyParam snooze_date string required. Example: 2022-08-29T13:49:22
     *
     * @response status=200 {
     *      "status": "ok",
     *      "snooze_date_at": "2022-08-29",
     *      "pass": "2022-08-29T13:16:58"
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function snoozeShipment(Request $request)
    {

        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();

        $validator = Validator::make($request->all(), [
            'shipment_id' => 'required',
            'snooze_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors(),
                'status' => 'not ok'
            ]
            ,401);
        } else {

            $args = $request->all();
            $find_shipment = Shipment::where('id', $args['shipment_id'])
                                     ->whereHas('customer', function($q) use ($customers) {
                                        $q->whereIn('id', $customers);
                                     })->first();


            if ( isset($find_shipment->id)) {
                \DB::table('shipments')->where([
                    'id' => $args['shipment_id']
                ])->update([
                    'snooze_date_at' => Carbon::parse($args['snooze_date'])->format('Y-m-d')
                ]);

                return response()->json([
                    'status' => 'ok',
                    'snooze_date_at' => Carbon::parse($args['snooze_date'])->format('Y-m-d'),
                    'pass' => $args['snooze_date']
                ]);
            } else {
                return response()->json([
                    'errors' => [
                        'shipment_id' => ['Please make sure that shipment exists or you are associated on that shipment.']
                    ],
                    'status' => 'not ok'
                ], 401);
            }
        }
    }

    /**
     * Find By Reference Number
     *
     * @queryParam po_number int required. Example: 483324
     *
     * @response status=200 {
     *      "status": "ok"
     * }
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function findByReferenceNumber(Request $request) {

        $validator = Validator::make($request->all(), [
            'po_number' => ['required',new CheckShipmentByReference],
        ]);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors(),
                'status' => 'not ok'
            ]
            ,401);
        } else {
            return response()->json([
                'status' => 'ok'
            ]);
        }
    }

    public function indexDraft(Request $request, $params = [])
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();
        $newShipments = [];

        if (count($customers) > 0) {
            $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
            $shipments = Shipment::whereIn('created_by', $customers)
            ->where(function ($qq) {
                $qq->whereNull('snooze_date_at');
            })
            ->where('is_draft', 1)
            ->where(function($q) use ($params){
                if(!empty($params['q'])){
                    $q->where('shifl_ref','LIKE', '%'.$params['q'].'%');
                }
            })
            ->get();

            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;

                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    $getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    $shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;

                    $shipments[$key]['schedules_group'] = $findShipment['schedules_group_bookings'];
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];

                    $shipments[$key]['schedule_has_pricing'] = true;
                    $shipments[$key]['shipment_metas'] = $findShipment->shipmentMetas;
                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);

                        //process schedules and others
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);

                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);

                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);
                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    }
                    $shipments[$key]['suppliers_name'] = $suppliers_name;
                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);
                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];

                    // new statuses
                    $shipments[$key]['status_v2'] = $shipment->getStatusV2();


                    // check if tracking shipments
                    $isSpecial = false;
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);

                    if ($shipments[$key]['booking_confirmed']==1) {
                        $should_push = false;
                    }
                    //
                    if ($should_push) {

                        // current day minus eta
                        $shipments[$key]['tab_status'] = 'draft';
                        //$diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = 100000;
                        $proceed = false;

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {
                            /*
                            if ($diff_days<20) {
                                $proceed = true;
                            }*/
                            $proceed = true;

                        } else {
                            if (strpos($shipments[$key]['status_v2'], 'In transit')) {
                                $proceed = false;
                            }
                        }

                        if ( $proceed ){
                            if (isset($shipments[$key]['schedules_group_bookings']) && $shipments[$key]['schedules_group_bookings']!=='' && $shipments[$key]['schedules_group_bookings']!==null) {

                                $schedules = json_decode($shipments[$key]['schedules_group_bookings']);
                                if (count($schedules)>0) {

                                    foreach($schedules as $keySecond => $schedule) {
                                        $hasUnset = false;
                                    }

                                    if (count($schedules)>0) {
                                        $shipments[$key]['schedules_group'] = json_encode($schedules);

                                        $shipments[$key]['snooze_date_at_readable'] = Carbon::parse($shipment->snooze_date_at)->format('F d, Y');

                                        array_push($newShipments, $shipments[$key]);
                                        /*
                                        if ($diff_days<20){
                                            array_push($newShipments, $shipments[$key]);
                                        }*/
                                    }

                                }
                            }

                        }

                    }
                    //e
                }
            }
        }


        if (is_null($request->per_page)) {
            //return StandardResource::collection((new Collection($newShipments)));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
        }
        return abort(404);
    }
    /**
     *
     * Snooze Shipment v2
     *
     * @queryParam per_page int
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|void
     */
    public function indexSnooze(Request $request)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();
        $shipments = [];
        $newShipments = [];


        if (count($customers) > 0) {
            $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
            $buyerIds = Buyer::where('connected_customer', $customers[0])->pluck('id');
            $shipperIds = Supplier::where('connected_customer', $customers[0])->pluck('id');

            $shipments = Shipment::where(function($q) use ($customers, $buyerIds, $shipperIds) {
                $q->whereHas('customer', function($qq) use ($customers){
                    $qq->whereIn('id', $customers);
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($buyerIds) {
                    $qq->whereIn('buyer_id', $buyerIds);
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($shipperIds) {
                    $qq->whereIn('supplier_id', $shipperIds);
                });
            })
            ->where(function ($qq) {
                $qq->whereNotNull('snooze_date_at');
                $qq->where('snooze_date_at','>', now());
            })
            ->where('booking_confirmed', 0)
            ->where('cancelled', 0)
            ->get();

            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;

                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    $getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    $shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;

                    $shipments[$key]['schedules_group'] = $findShipment['schedules_group_bookings'];
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];

                    $shipments[$key]['schedule_has_pricing'] = true;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);

                        //process schedules and others
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);

                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);

                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);
                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    }
                    $shipments[$key]['suppliers_name'] = $suppliers_name;
                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);
                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];

                    // new statuses
                    $shipments[$key]['status_v2'] = $shipment->getStatusV2();


                    // check if tracking shipments
                    $isSpecial = false;
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);

                    if ($shipments[$key]['booking_confirmed']==1) {
                        $should_push = false;
                    }
                    //
                    if ($should_push) {

                        // current day minus eta

                        //$diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = 100000;
                        $proceed = false;

                        $shipments[$key]['tab_status'] = 'snoozed';

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {
                            /*
                            if ($diff_days<20) {
                                $proceed = true;
                            }*/
                            $proceed = true;

                        } else {
                            if (strpos($shipments[$key]['status_v2'], 'In transit')) {
                                $proceed = false;
                            }
                        }

                        if ( $proceed ){
                            if (isset($shipments[$key]['schedules_group_bookings']) && $shipments[$key]['schedules_group_bookings']!=='' && $shipments[$key]['schedules_group_bookings']!==null) {

                                $schedules = json_decode($shipments[$key]['schedules_group_bookings']);
                                if (count($schedules)>0) {

                                    foreach($schedules as $keySecond => $schedule) {
                                        $hasUnset = false;
                                        /*
                                        if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                            if (count($schedule->sell_rates)>0) {
                                                unset($schedules[$keySecond]);
                                                $hasUnset = true;
                                            }
                                        }*/

                                        /*
                                        if ( !$hasUnset ) {
                                            $hasUnset = true;
                                            $etd = Carbon::parse($schedule->etd)->diffInDays(now(), false);
                                            if ($etd>=-4) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }

                                        if ( !$hasUnset ) {
                                            $hasUnset = true;
                                            $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                                            if ($eta>30) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }*/
                                    }

                                    if (count($schedules)>0) {
                                        $shipments[$key]['schedules_group'] = json_encode($schedules);

                                        $shipments[$key]['snooze_date_at_readable'] = Carbon::parse($shipment->snooze_date_at)->format('F d, Y');

                                        array_push($newShipments, $shipments[$key]);
                                        /*
                                        if ($diff_days<20){
                                            array_push($newShipments, $shipments[$key]);
                                        }*/
                                    }

                                }
                            }

                        }

                    }
                    //e

                    }
                }
            }


        if (is_null($request->per_page)) {
            //return StandardResource::collection((new Collection($newShipments)));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
        }
        return abort(404);
    }

    /**
     * Pending quote shipment
     *
     * @queryParam per_page int required
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|void
     *
     */
    public function indexPendingQuote(Request $request)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();
        $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];

        $newShipments = [];
        if (count($customers) > 0) {
            $buyerIds = Buyer::where('connected_customer', $customers[0])->pluck('id');
            $shipperIds = Supplier::where('connected_customer', $customers[0])->pluck('id');

            $shipments = Shipment::where(function($q) use ($customers, $buyerIds, $shipperIds) {
                $q->whereHas('customer', function($qq) use ($customers){
                    $qq->whereIn('id', $customers);
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($buyerIds) {
                    $qq->whereIn('buyer_id', $buyerIds);
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($shipperIds) {
                    $qq->whereIn('supplier_id', $shipperIds);
                });
            })->where(function ($qq) {
                $qq->where('snooze_date_at', NULL);
                $qq->orWhere('snooze_date_at','<=', now());
            })
            ->where('booking_confirmed', 0)
            ->where('cancelled', 0)
            ->get();

            if (count($shipments) > 0) {
                foreach ($shipments as $key => $shipment) {
                    $findShipment = $shipment;
                    $shipments[$key]['customer_id'] = $findShipment->customer->id;
                    $getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    $shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['schedules_group'] = $findShipment['schedules_group_bookings'];
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                    $shipments[$key]['schedule_has_pricing'] = true;
                    $shipments[$key]['shipment_metas'] = $findShipment->shipmentMetas;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);
                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);
                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;
                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);
                    $shipments[$key]['status_v2'] = $shipment->getStatusV2();
                    $isSpecial = false;
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);
                    $shipments[$key]['tab_status'] = 'pending_quote';
                    $shipments[$key]['diff_days'] = 100000;
                    $proceed = false;

                    if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {
                        $proceed = true;
                    } else {
                        if (strpos($shipments[$key]['status_v2'], 'In transit')) {
                            $proceed = false;
                        }
                    }

                    if ( $proceed ){
                        if (isset($shipments[$key]['schedules_group_bookings']) && $shipments[$key]['schedules_group_bookings']!=='' && $shipments[$key]['schedules_group_bookings']!==null) {

                            $schedules = json_decode($shipments[$key]['schedules_group_bookings']);
                            if (count($schedules)>0) {
                                $quoteAvailable = false;
                                foreach($schedules as $keySecond => $schedule) {
                                    if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                        if (count($schedule->sell_rates)>0) {
                                            $quoteAvailable = true;
                                            break;
                                        }
                                    }
                                }

                                if (count($schedules)>0 && !$quoteAvailable) {
                                    $shipments[$key]['schedules_group'] = json_encode($schedules);
                                    array_push($newShipments, $shipments[$key]);
                                }
                            }
                        }
                    }
                }
            }
        }

        if (is_null($request->per_page)) {
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($request->per_page)) {
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        return abort(404);
    }
    /**
     *
     * Get Po Shipment
     *
     * @authenticated
     *
     * @urlParam po_id int required
     * @queryParam supplier_id int required Supplier ID
     * @queryParam customer_id int required Customer ID
     * @queryParam po_number string required The shipment PO_NUM. Example: NULL
     *
     * @bodyParam supplier_id int Supplier ID. No-example
     * @bodyParam customer_id int Customer ID. No-example
     * @bodyParam po_number string The shipment PO_NUM. No-example
     *
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\Shipment
     *
     * @response status=422 scenario="Validation error" {
     *    "message": "The given data was invalid.",
     *    "status": "not ok",
     *    "errors": {
     *        "supplier_id": [
     *            "The supplier id is required."
     *        ],
    *         "customer_id": [
     *            "The customer id s required."
     *        ],
    *         "po_number": [
     *            "The po_number field is required."
     *        ]
     *    }
     * }
     *
     * @response status=404  {
     *  "message": "Not found"
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     */

    public function getPoShipments(Request $request, $po_id) {

        $response = ['status' => 'not ok', 'data' => []];
        $validator = Validator::make($request->all(), [
            'supplier_id' => ['required'],
            'customer_id' => ['required'],
            //'customer_id' => ['required', new CheckIfOwnCustomer],
            'po_number' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors()
            ]
            ,401);
        }

        $customers = Auth::user()->customersApi->pluck('id');
        $args = $request->all();

        $shipments = Shipment::whereHas('suppliers', function($q) use ($args) {
            $q->where('suppliers.id', $args['supplier_id']);
        })
        ->whereHas('customer', function($qq) use ($args) {
            $qq->where('customers.id', $args['customer_id']);
        })
        ->get();

        $token = CustomJWTGenerator::generateToken();

        $new_shipments = [];

        if ( count($shipments) >0) {

            foreach($shipments as $key => $shipment) {

                $findShipment = $shipment;
                $has_unset = false;
                $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'], $findShipment['schedules_group']);
                $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];

                $shipments[$key]['schedule_has_pricing'] = true;

                $getShipmentSchedules = [];

                if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                    $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);

                    if (count($getShipmentSchedules)>0) {
                        $hasConfirm = false;
                        $setKey = 0;
                        foreach ($getShipmentSchedules as $kk => $getShipmentSchedule) {
                            if (!isset($getShipmentSchedule->sell_rates)) {
                                if ($shipments[$key]['schedule_has_pricing']) {
                                    $shipments[$key]['schedule_has_pricing'] = false;
                                }
                            } else {
                                if (isset($getShipmentSchedule->sell_rates) && is_array($getShipmentSchedule->sell_rates) && count($getShipmentSchedule->sell_rates)==0) {
                                    if ($shipments[$key]['schedule_has_pricing']) {
                                        $shipments[$key]['schedule_has_pricing'] = false;
                                    }
                                }
                            }

                            if (isset($getShipmentSchedule->is_confirmed) && $getShipmentSchedule->is_confirmed==1 && !$hasConfirm) {
                                $hasConfirm = true;
                                $setKey = $kk;

                                if (isset($getShipmentSchedule->type)) {
                                    $shipments[$key]['type'] = $getShipmentSchedule->type;
                                }
                            }

                        }
                        if (!$hasConfirm) {
                            $shipments[$key]['shipment_status'] = 'Pending Approval';
                            $shipments[$key]['eta'] = null;
                            $shipments[$key]['etd'] = null;
                            $shipments[$key]['type'] = null;
                        }

                        $shipments[$key]['location_to_name'] = (isset($getShipmentSchedules[$setKey])) ? $this->getTerminal($getShipmentSchedules[$setKey]->location_to) : '';
                        $shipments[$key]['location_from_name'] = (isset($getShipmentSchedules[$setKey])) ? $this->getTerminal($getShipmentSchedules[$setKey]->location_from) : '';
                    }
                }


                $shipments[$key]['status_v2'] = $shipment->getStatusV2();

                //
                // check if tracking shipments
                if($findShipment['is_tracking_shipment']){
                    if(!empty($findShipment['mbl_num'])){
                        $isSpecial = true;
                        $shipments[$key]['booking_confirmed'] = 1;
                        $terminal49_shipment = $shipment->terminal_fortynine;
                        if( isset($terminal49_shipment->id) ){
                            $attributes = json_decode($terminal49_shipment->attributes,true);
                            $shipments[$key]['location_from_name'] = $attributes['port_of_lading_name'];
                            $shipments[$key]['location_to_name'] = $attributes['port_of_discharge_name'];
                            $shipments[$key]['eta'] = $this->ifNull($attributes['pod_eta_at'],$attributes['pod_ata_at']);
                            $shipments[$key]['etd'] = $this->ifNull($attributes['pol_etd_at'],$attributes['pol_atd_at']);

                            $shipments[$key]['special_attributes'] = $attributes;
                        }
                    }
                }

                // current day minus eta
                $diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                $shipments[$key]['diff_days'] = $diff_days;
                $proceed = false;

                if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {

                    if ($shipments[$key]['etd']!==null && $shipments[$key]['etd']!=='' && $shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {
                        $proceed = true;
                        if (Carbon::parse($shipments[$key]->etd)->gt(now())) {
                            $shipments[$key]['shipment_status'] = 'Awaiting Departure';
                        }
                        if (Carbon::parse($shipments[$key]->etd)->lt(now())) {
                            $shipments[$key]['shipment_status'] = 'In Transit';
                        }

                        if ( $diff_days > 30 ) {
                            $shipments[$key]['shipment_status'] = 'Completed';
                        }

                    } else {
                        if ( count($getShipmentSchedules) == 0) {
                            $shipments[$key]['shipment_status'] = 'Expired';
                        }

                    }

                } else {
                    if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {
                        $shipments[$key]['shipment_status'] = 'Completed';
                    } else {

                        $shipments[$key]['original_shipment_status'] = $shipments[$key]['status_v2'];
                        $shipments[$key]['shipment_status'] = $shipments[$key]['status_v2'];

                        if ( $shipments[$key]['status_v2'] === 'In transit - hold' ) {
                            $shipments[$key]['shipment_status'] = 'In Transit - Pending Release';
                        } elseif ( $shipments[$key]['status_v2'] === 'Past last free day' ) {
                            $shipments[$key]['shipment_status'] = 'Past last free day';
                        } elseif ( $shipments[$key]['status_v2'] === 'In transit - released' ) {
                            $shipments[$key]['shipment_status'] = 'In Transit - Released';
                        } elseif($shipments[$key]['status_v2'] === 'Discharged - hold') {
                            $shipments[$key]['shipment_status'] = 'Discharged - Hold';
                        }
                    }

                }

                $client = new \GuzzleHttp\Client([
                    'base_uri' => 'https://po.shifl.com',
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept'     => 'application/json',
                        'Authorization'  => 'Bearer ' . $token,
                    ]
                ]);

                $res = $client->get(sprintf('/api/po/shipments/%d/purchase-orders', $shipment->id),[
                    'query' => [
                        'supplierId' => $args['supplier_id']
                    ]
                ]);

                $shipments[$key]->purchase_orders = json_decode($res->getBody());
                $shipments[$key]->purchase_order_data = null;
                $final_purchase_orders = [];

                if ( count($shipments[$key]->purchase_orders) > 0) {
                    foreach ($shipments[$key]->purchase_orders as $keySecond => $purchase_order) {
                        if (isset($purchase_order->po_number) && strval($purchase_order->po_number)=== strval($args['po_number'])) {
                            $shipments[$key]->purchase_order_data = $purchase_order;
                        }
                    }
                } else {
                    $has_unset = true;
                }

                if ( !$has_unset ) {

                    array_push($new_shipments, [
                        'id' => $shipments[$key]->id,
                        'shifl_ref' => $shipments[$key]->shifl_ref,
                        'mbl_num' => $shipments[$key]->mbl_num,
                        'type' => $shipments[$key]->type,
                        'eta' => $shipments[$key]->eta,
                        'etd' => $shipments[$key]->etd,
                        'shipment_status' => $shipments[$key]->shipment_status,
                        'purchase_order_data' => (isset($shipments[$key]->purchase_order_data)) ? $shipments[$key]->purchase_order_data : null
                    ]);

                }

            }

        }



        if (is_null($request->per_page)) {
            return StandardResource::collection((new Collection($new_shipments))->paginate(30));
        }
        if (is_numeric($request->per_page)) {
            return StandardResource::collection((new Collection($new_shipments))->paginate($request->per_page));
        }
        return abort(404);
    }

    /**
     * Request New Schedule
     *
     * @authenticated
     *
     * @urlParam shipment_id int required Shipment ID
     *
     * @apiResource App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function requestNewSchedule(Request $request, $shipment_id) {

        $response = ['status' => 'not ok','error_message' => ''];
    //    $response = response([], 500);

        $model = Shipment::find($shipment_id);
        if (isset($model->id)) {
            $schedules_group_bookings = $model->schedules_group_bookings;

            if ( isset($schedules_group_bookings) ) {
                if ( $schedules_group_bookings!==null ) {
                    $schedules_group_bookings = json_decode($schedules_group_bookings);
                    if (isset($schedules_group_bookings[0]) && count($schedules_group_bookings)>0) {
                        foreach($schedules_group_bookings as $key => $schedule_group_booking) {
                           $schedules_group_bookings[$key]->is_confirmed = 0;
                        }
                        $schedules_group_bookings = json_encode($schedules_group_bookings);
                    } else {
                        $schedules_group_bookings = json_encode([]);
                    }
                } else {
                    $schedules_group_bookings = json_encode([]);
                }
            } else {
                $schedules_group_bookings = json_encode([]);
            }

            \DB::table('shipments')
               ->where('id',$model->id)
               ->update([
                    'is_schedule_requested' => 1
               ]);
            event(new SendNewBookingEmailEvent($model));
            $response['status'] = 'ok';
        } else {
            $response['error_message'] = 'Please provide a valid shipment ID.';
        }


        return response()->json($response);
    }

    /**
     * Terminal 49 Shipment
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\Standard
     * @apiResourceModel App\Terminal49Shipment
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     */

    public function indexTs(Request $request, $mbl_num)
    {
        $shipment = [];
        $shipment_terminal49 = \App\Terminal49Shipment::find($mbl_num);
        $eta_logs = [];

        if (isset($mbl_num)) {
            $eta_logs = \App\EtaLog::where("mbl_num", $mbl_num)
                               ->orderBy('id', 'asc')
                               ->get();
        }

        $shipment['eta_logs'] = $eta_logs;
        $shipment['containers'] = [];


        if ($shipment_terminal49) {
            $containers = json_decode($shipment_terminal49->containers, true);
            $attributes = json_decode($shipment_terminal49->attributes,true);
            $shipment['attributes'] = $attributes;

            if (!empty($containers)) {
                foreach ($containers as $key => $container) {
                    $container_id = $container['id'];
                    $standard = $request->standard;
                    //
                    if(isset($container['id'])){
                        $fetch_stored_data = false;
                        try {
                            $events = $this->getMilestones($container['id'], $request->standard);
                        } catch (\Exception $e) {
                            \Log::info($e);
                            $fetch_stored_data = true;
                        }
                        if($fetch_stored_data || $events == null){
                            if($request->standard){
                              $events_array = json_decode($shipment_terminal49->transport_events ?? '[]');
                              $events = [];
                              foreach ($events_array as $key => $value) {
                                  if($key == $container['id']) {
                                      $events = $value->data ?? [];
                                      break;
                                  }
                              }
                            }else{
                              $events = [];
                            }
                        }
                    }
                    $milestones = $events;

                    $shipment['containers'][$container['attributes']['number']] = [
                        "last_free_day" => $container['attributes']['pickup_lfd'],
                        "released_at_terminal" => $container['attributes']['available_for_pickup'],
                        "holds" => $container['attributes']['holds_at_pod_terminal'],
                        "milestones" => $milestones,
                        "pickup_appointment_at" => (isset($container['attributes']['pickup_appointment_at'])) ? $container['attributes']['pickup_appointment_at'] : '',
                        "container_details" => (isset($container['attributes'])) ? $container['attributes'] : [],
                        "fees" => (isset($container['attributes']['fees_at_pod_terminal'])) ? $container['attributes']['fees_at_pod_terminal'] : 0,
                    ];
                }
            }
        }

        return response($shipment, 200);
    }
    /**
     * Search customer order
     *
     * @authenticated
     * @queryParam q string
     * @queryParam tab string Tab can be expired, pending, completed, shipments. Example: completed
     * @queryParam order string required desc or asc. Example: desc
     * @queryParam sort string required desc or asc. Example: eta
     * @queryParam per_page int required Example: 30
     *
     * @apiResourceCollection App\Http\Resources\Standard
     * @apiResourceModel App\User with=customersApi
     *
     * @response 404 {
     *    "status":"Abort"
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     */

    public function search(Request $request) {
        if (!$request->has('q') && !$request->has('tab'))
            return abort(404);

        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $sort = ($request->has('sort')) ? $request->input('sort') : 'eta';

        $tab = $request->input('tab');

        $parameters = [
            'q' => $request->input('q'),
            'order' => $order,
            'sort' => $sort,
            'per_page' => $request->has('per_page') ? $request->per_page : 30
        ];

        if ( $tab === 'expired') {
            try{
                return $this->expiredSearch($parameters);
            }catch (\Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }
        } elseif ( $tab === 'pending') {
            try{
                return $this->pendingSearch($parameters);
            }catch (Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }
        } elseif ( $tab === 'completed') {
            try{
                return $this->completedSearchTest($parameters, 0);
            }catch (Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }
        } elseif ($tab==='shipments') {
            try {
                return $this->transitSearchTest($parameters, 0);
            }catch (Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }
        } elseif ($tab==='shipments-completed-merge') {
            try {
                return $this->transitSearch($parameters, 1);
            }catch (Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }

        } elseif ($tab==='pendingQuote') {
            try{
                return $this->pendingQuoteSearch($parameters);
            }catch (Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }
        } elseif ($tab==='snooze') {
            try{
                return $this->snoozeSearch($parameters);
            }catch (\Exception $exception){
                return response([
                    'message'=>'No results found.'
                ], 404);
            }
        } elseif ($tab==='draft') {
                return $this->indexDraft($request, $parameters);
        } else {
            return abort(404);
        }
    }
   /**
     * Select Schedule App
     *
     * @authenticated
     *
     * @urlParam id int required The shipments ID
     * @urlParam schedule_id int required The shipment SCHEDULE_ID
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment
     *
     * @response 200 {
     *  "status": "ok"
     * }
     *
     * @reponse 404 {
     *  "status": "not ok"
     * }
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @return \Illuminate\Http\Response
     */

    public function selectScheduleApp(Request $request)
    {
        //Auth::loginUsingId(11);

        $response = ['status' => 'not ok'];

        if ($request->has('id') && $request->has('schedule_id')) {
            $schedule_id = $request->input('schedule_id');
            $shipment_id = $request->input('id');

            $findShipment = \DB::table('shipments')
                            ->where('id', $request->input('id'))
                            ->first();
            $shipment = \App\Shipment::find($shipment_id);

            if (isset($findShipment->id)) {

                $newFinalSchedules = json_decode($findShipment->schedules_group_bookings);

                if (count($newFinalSchedules)>0) {
                    $schedules_group_bookings = $newFinalSchedules;
                    $isOkay = false;
                    foreach ($schedules_group_bookings as $k => $schedule) {
                        if ($schedule_id==$schedule->id) {
                            $date_create = date_create($schedule->eta);
                            $date_format = date_format($date_create, 'Y-m-d');
                            $schedules_group_bookings[$k]->eta = Carbon::createFromFormat('Y-m-d', $date_format)->format('Y-m-d H:i:s');

                            $date_create = date_create($schedule->etd);
                            $date_format = date_format($date_create, 'Y-m-d');

                            $schedules_group_bookings[$k]->is_confirmed = 1;
                            $schedules_group_bookings[$k]->etd = Carbon::createFromFormat('Y-m-d', $date_format)->format('Y-m-d H:i:s');

                            if (isset($shipment->id)) {
                                $shipment->update([
                                'eta' => $schedule->eta,
                                'etd' => $schedule->etd,
                                'booking_confirmed' => 1,
                                'shipment_status' => 'Approved',
                                'booking_confirmed_at' => Carbon::now()
                                ]);
                            }
                            $isOkay = true;
                        } else {
                            $schedules_group_bookings[$k]->is_confirmed = 0;
                        }
                    }

                    if ($isOkay) {

                        $shipment->update(
                            [
                                'schedules_group_bookings' => json_encode($schedules_group_bookings)
                            ]
                        );


                        if (isset($shipment->id)) {
                            \App\Events\SendBookingEmailCustomerEvent::dispatch($shipment);

                            \App\Events\SendBookingEmailConfirmedByCustomerEvent::dispatch($shipment);
                        }


                        $response['status'] = 'ok';
                    }
                }
            }
        }

        return response()->json($response);
    }

    /**
     * Get Schedule Option
     *
     * @authenticated
     *
     * @urlParam id int required The shipment's ID
     *
     * @apiResource App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */

    public function getScheduleOptions(Request $request)
    {
        $response = ['results' => []];
        $currentSelectedSchedule = null;

        //$newFinalSchedules = [];

        if ($request->has('id')) {
            $id = $request->input('id');
            $shipment = Shipment::find($id);
            $findShipment = $shipment;
            $selectedSchedule = [];

            //if this is tracking shipment replace default values with terminal 49 values
            if( $shipment->is_tracking_shipment=== 1 && $shipment->mbl_num!==null && $shipment->mbl_num!==''){
                $schedule_location_from = '';
                $schedule_location_to = '';
                $schedule_eta = '';
                $schedule_etd = '';
                $terminal49_shipment = $shipment->terminal_fortynine;
                if( isset($terminal49_shipment['id']) ){
                    $attributes = json_decode($terminal49_shipment['attributes'],true);
                    $schedule_location_from = $attributes['port_of_lading_name'];
                    $schedule_location_to = $attributes['port_of_discharge_name'];
                    $schedule_eta = $this->ifNull($attributes['pod_eta_at'],$attributes['pod_ata_at']);
                    $schedule_etd = $this->ifNull($attributes['pol_etd_at'],$attributes['pol_atd_at']);
                }

                //parse eta and etd
                $etd = \Carbon\Carbon::parse($schedule_etd);
                $eta = \Carbon\Carbon::parse($schedule_eta);

                //create shipment schedules
                $newFinalSchedules = [[
                    'id' =>  Carbon::now()->timestamp,
                    'eta' => null,
                    'etd' => null,
                    'eta_readable' => $eta->format('F d, Y'),
                    'etd_readable' => $etd->format('F d, Y'),
                    'location_from' => null,
                    'location_to' =>  null,
                    'location_from_name' => $schedule_location_from,
                    'location_to_name' => $schedule_location_to,
                    'mode' => null,
                    'legs' => [],
                    'type' => '',
                    'transit' => $etd->diffInDays($eta),
                    'test' => 1,
                    'carrier_name' => null,
                    'carrier_name_label' => '',
                    'is_confirmed' => 1,
                    'size_id' => null,
                    'price' => null,
                    'selling_size_id' => null,
                    'selling_price' => null,
                    'sell_rates' => [],
                    'size_name' => '',
                    'selling_size_name' => '',
                    'buy_rates' => []
                ]];
                $currentSelectedSchedule = $newFinalSchedules[0];

            } else {
                $newFinalSchedules = json_decode($this->merge_group($findShipment->schedules_group_bookings, $findShipment->schedules_group));
                if (count($newFinalSchedules)>0) {
                    $newFinalSchedules = json_decode(json_encode($newFinalSchedules));
                    $has_confirmed = false;

                    foreach ($newFinalSchedules as $key => $value) {
                        if ($value->is_confirmed==1) {
                            $has_confirmed = true;
                            array_push($selectedSchedule, $value);
                        }
                    }
                }
                $carrierName = '';

                if (isset($selectedSchedule[0]) && isset($selectedSchedule[0]->carrier_name) && isset($selectedSchedule[0]->carrier_name->id)) {
                    $findCarrier = Carrier::find(intval($selectedSchedule[0]->carrier_name->id));

                    if (isset($findCarrier->name)) {
                        $carrierName = $findCarrier->name;
                    }
                }

                if (count($newFinalSchedules) > 0) {
                    $cheapest = 0;
                    $cheapestKey = 0;
                    foreach ($newFinalSchedules as $key => $newFinalSchedule) {
                        if ($newFinalSchedule->is_confirmed==1) {
                            //$newFinalSchedules[$key]->carrier_name = $carrierName;
                        }
                        if (isset($newFinalSchedule->etd) && isset($newFinalSchedule->eta)) {
                            $etd = Carbon::parse($newFinalSchedule->etd);
                            $eta = Carbon::parse($newFinalSchedule->eta);
                            $newFinalSchedules[$key]->transit = $etd->diffInDays($eta);
                        } else {
                            $newFinalSchedules[$key]->transit = '';
                        }
                        $totalAmount = 0;
                        if (isset($newFinalSchedule->sell_rates) && $newFinalSchedule->sell_rates!==null && $newFinalSchedule->sell_rates!=='' && count($newFinalSchedule->sell_rates) > 0) {
                            foreach ($newFinalSchedule->sell_rates as $keySecond => $sell_rate) {
                                $totalAmount = $totalAmount + ($sell_rate->qty * $sell_rate->amount);
                                $newFinalSchedules[$key]->sell_rates[$keySecond]->total = ($sell_rate->qty * $sell_rate->amount);

                                $newFinalSchedules[$key]->sell_rates[$keySecond]->service_name = \App\Service::findOrFail($sell_rate->service_id)->name;
                                $newFinalSchedules[$key]->sell_rates[$keySecond]->container_size_name = \App\ContainerSize::findOrFail($sell_rate->container_size_id)->name;
                            }
                        }
                        if (isset($newFinalSchedule->legs) && $newFinalSchedule->legs!==null && $newFinalSchedule->legs!=='' && count($newFinalSchedule->legs) > 0) {
                            foreach ($newFinalSchedule->legs as $keySecond => $leg) {
                                $newFinalSchedules[$key]->legs[$keySecond]->location_to_name = \App\TerminalRegion::findOrFail($leg->location_to)->name;
                                // $newFinalSchedules[$key]->legs[$keySecond]->location_from_name = \App\TerminalRegion::findOrFail($leg->location_from)->name;

                                if (isset($leg->eta)) {
                                    $newFinalSchedules[$key]->legs[$keySecond]->eta_readable = \Carbon\Carbon::parse($leg->eta)->format('F d, Y');
                                }

                                if (isset($leg->etd)) {
                                    $newFinalSchedules[$key]->legs[$keySecond]->etd_readable = \Carbon\Carbon::parse($leg->etd)->format('F d, Y');
                                }
                            }
                        }
                        if ($cheapest==0) {
                            $cheapest = $totalAmount;
                        } else {
                            if ($totalAmount < $cheapest) {
                                $cheapest = $totalAmount;
                                $cheapestKey = $key;
                            }
                        }

                        if (isset($newFinalSchedule) && isset($newFinalSchedule->carrier_name) && isset($newFinalSchedule->carrier_name->id)) {
                            $findCarrier = Carrier::find(intval($newFinalSchedule->carrier_name->id));

                            if (isset($findCarrier->name)) {
                                $carrierName = $findCarrier->name;
                            }
                        }

                        $newFinalSchedules[$key]->carrier_name = $carrierName;
                        $newFinalSchedules[$key]->total_amount = $totalAmount;

                        //set default values to default shipment meta
                        $schedule_location_from = $newFinalSchedule->location_from;
                        $schedule_location_to = $newFinalSchedule->location_to;
                        $schedule_etd = $newFinalSchedule->etd;
                        $schedule_eta = $newFinalSchedule->eta;

                        $newFinalSchedules[$key]->location_to_name = \App\TerminalRegion::findOrFail($schedule_location_to)->name;
                        $newFinalSchedules[$key]->location_from_name = \App\TerminalRegion::findOrFail($schedule_location_from)->name;
                        $newFinalSchedules[$key]->etd_readable = \Carbon\Carbon::parse($schedule_etd)->format('F d, Y');
                        $newFinalSchedules[$key]->eta_readable = \Carbon\Carbon::parse($schedule_eta)->format('F d, Y');

                    }


                    foreach ($newFinalSchedules as $key => $newFinalSchedule) {

                        $newFinalSchedules[$key]->cheapest = false;
                        if (isset($newFinalSchedule->sell_rates) && $newFinalSchedule->sell_rates!==null && $newFinalSchedule->sell_rates!=='' && count($newFinalSchedule->sell_rates) > 0) {

                            if ( $cheapestKey === $key ) {
                                $newFinalSchedules[$key]->cheapest = true;
                            }
                        }

                        if ($newFinalSchedule->is_confirmed==1) {
                            $currentSelectedSchedule = $newFinalSchedule;
                        }
                    }
                }
            }


        }

        return response()->json([
            'results' => $newFinalSchedules,
            'current_selected_schedule' => $currentSelectedSchedule
        ]);
    }

    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     *
     * Get Shipment Details
     *
     * @authenticated
     *
     * @urlParam id int required Shipment ID
     *
     * @apiResource App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment
     *
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function details( Request $request, $id ) {

        /*$shipment = Shipment::where('id',$id)
                            ->with('customer', 'containers', 'suppliers', 'carrier', 'terminalRegions', 'shipmentSuppliers', 'shipmentSchedules', 'terminal_fortynine')
                            ->first();*/

        $shipment = Shipment::where('id',$id)
                            ->with('customer', 'containers', 'suppliers', 'carrier', 'terminalRegions', 'shipmentSchedules', 'terminal_fortynine')
                            ->first();


        $ais_link = $this->getAis($shipment) ?? '';
        //$status_v2 = $shipment->status_fallback;
        $status_v2 = $shipment->getStatusV2();
        $shipment->status_v2 = $status_v2;
        $shipment->ais_link = $ais_link;
        $shipment->general_status = $shipment->getGeneralStatus();

        if ( isset($shipment->id) ) {
            $schedules = [];
            $suppliers = [];
            $containers = [];
            if($shipment->terminal_id != '' && $shipment->terminal_id > 0) {
                $shipment->shipment_terminal_name = $this->getTerminal($shipment->terminal_id);
            }

            $shipment->carrier = '';
            if ( isset($shipment->schedules_group_bookings) && $shipment->schedules_group_bookings!=='') {
                $shipment->selected_schedule_type = '';
                if ( $this->isJson($shipment->schedules_group_bookings)) {
                    $hasConfirm = false;
                    $schedules = json_decode($shipment->schedules_group_bookings);

                    if ( count ($schedules) > 0) {
                        foreach( $schedules as $key => $schedule ) {
                            $shipment->location_to_name = $this->getTerminal($schedule->location_to);
                            $shipment->location_from_name = $this->getTerminal($schedule->location_from);

                            if (isset($schedule->carrier_name) && isset($schedule->carrier_name->id)) {
                                $c = Carrier::find(intval($schedule->carrier_name->id));
                                $shipment->carrier = (isset($c->name)) ? $c->name : '';
                            }

                            if (isset($schedule->is_confirmed) && $schedule->is_confirmed==1 && !$hasConfirm) {
                                $hasConfirm = true;

                                if (isset($schedule->type)) {
                                    $shipment->selected_schedule_type = $schedule->type;
                                }
                            }

                        }

                        if(!$hasConfirm && $shipment->selected_schedule_type == '' && is_countable($schedules) && count($schedules) > 0){
                            $shipment->selected_schedule_type = $schedules[0]->type;
                        }
                    }
                }
            }

            if ( isset($shipment->container_group_bookings) && $shipment->container_group_bookings!=='') {
                if ( $this->isJson($shipment->container_group_bookings)) {
                    $containers = json_decode($shipment->container_group_bookings);
                }
            }

            if ( isset($shipment->suppliers_group_bookings) && $shipment->suppliers_group_bookings!=='') {
                if ( $this->isJson($shipment->suppliers_group_bookings)) {
                    $suppliers = json_decode($shipment->suppliers_group_bookings);
                }
            }

            $suppliers_name = [];
            $shipment->obl_filled = false;
            $shipment->commercial_documents_filled = false;

            $obl_counter = 0;

            /* customer supplier */
            $check_supplier_documents = Document::where('shipment_id', $id)->get();

            if (count($suppliers) > 0) {
                foreach ($suppliers as $key => $supplier) {

                    $commercial_documents_counter = 0;
                    $commercial_invoice_counter = 0;
                    $packing_list_counter = 0;
                    $ilist_counter = 0;

                    $suppliers[$key]->obl_filled = true;
                    $suppliers[$key]->commercial_documents_filled = true;
                    array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    $suppliers[$key]->name = $this->getSupplierName($supplier, $shipment->customer_id);

                    if (isset($supplier->bl_status) && ($supplier->bl_status === 'Telex Released' || $supplier->bl_status ==='Original Received')) {
                        $obl_counter++;
                    } else {
                        $suppliers[$key]->obl_filled = false;
                    }

                    $get_supplier = Supplier::find($supplier->supplier_id);
                    if ( isset ($get_supplier->id)) {
                        $get_supplier_documents = $get_supplier->documents;

                        if ( count ($get_supplier_documents) > 0) {

                            //filter supplier documents first to return only match shipment id
                            $new_supplier_documents = [];
                            foreach ( $get_supplier_documents as $get_supplier_document ) {

                                //if ( $get_supplier_document->is_added_by_customer == 1 && $get_supplier_document->shipment_id == $id ) {
                                if ( $get_supplier_document->shipment_id == $id ) {
                                    array_push($new_supplier_documents, $get_supplier_document);
                                }
                            }
                            //re assign again
                            $get_supplier_documents = $new_supplier_documents;

                            foreach ( $get_supplier_documents as $get_supplier_document ) {

                                //if ( $get_supplier_document->is_added_by_customer == 1 ) {
                                    if ( $get_supplier_document->type === 'Other Commercial Docs') {
                                        //$commercial_documents_counter++;
                                    } elseif ( $get_supplier_document->type === 'Commercial Invoice') {
                                        $commercial_invoice_counter++;
                                    } elseif ( $get_supplier_document->type === 'Invoice And Packing List') {
                                        $ilist_counter++;
                                    } elseif ($get_supplier_document->type === 'Packing List') {
                                        $packing_list_counter++;
                                    }
                                //}

                            }

                            if ( $commercial_documents_counter == 0 ) {
                                $suppliers[$key]->commercial_documents_filled = false;
                            }

                            //case 1
                            //check for packing list and commercial invoice
                            if ( $commercial_invoice_counter>0 && $packing_list_counter > 0 ) {
                                $suppliers[$key]->commercial_documents_filled = true;
                            }

                            //case 2
                            //check for invoice and packing list
                            if ( $ilist_counter > 0 ) {
                                $suppliers[$key]->commercial_documents_filled = true;
                            }

                        } else {
                            $suppliers[$key]->commercial_documents_filled = false;
                        }

                        $suppliers[$key]->document_custom = $get_supplier->documents;
                    }

                    /*
                    if (isset($supplier->commercial_documents) && $supplier->commercial_documents !== null && $supplier->commercial_documents!=='') {
                        $commercial_documents_counter++;
                    } else {
                        $suppliers[$key]->commercial_documents_filled = false;
                    }*/
                }

                if ( $obl_counter >= count($suppliers) && count($suppliers) > 0) {
                    $shipment->obl_filled = true;
                }
                if ( count ($suppliers) > 0) {
                    $final_counter = 0;
                    foreach ($suppliers as $key => $supplier) {
                        if ( $supplier->commercial_documents_filled )
                            $final_counter++;
                    }

                    if ( $final_counter > 0 && $final_counter === count($suppliers)) {
                        $suppliers[$key]->commercial_documents_filled = true;
                    }
                }


                /*
                if ( $commercial_documents_counter >= count($suppliers) && count($suppliers) > 0) {
                    $shipment->commercial_documents_filled = true;
                }

                if (($packing_list_counter > 0 && $commercial_invoice_counter > 0 || $ilist_counter==count($suppliers)) && count($suppliers) > 0) {
                    $shipment->commercial_documents_filled = true;
                    $shipment->obl_filled = true;

                    foreach ($suppliers as $key => $supplier) {
                        $suppliers[$key]->obl_filled = true;
                        $suppliers[$key]->commercial_documents_filled = true;
                    }
                }*/
            }

            $shipment->suppliers_name = $suppliers_name;


            if( $shipment->is_tracking_shipment ){
                if(!empty($shipment->mbl_num)){
                    $shipment->booking_confirmed = 1;
                    $shipment->is_tracking_shipment = 1;
                    $terminal49_shipment = $shipment->terminal_fortynine;

                    if( isset($terminal49_shipment['id']) ){
                        $attributes = json_decode($terminal49_shipment['attributes'],true);
                        $shipment->location_from_name = $attributes['port_of_lading_name'];
                        $shipment->location_to_name = $attributes['port_of_discharge_name'];
                        $shipment->eta = $this->ifNull($attributes['pod_eta_at'],$attributes['pod_ata_at']);
                        $shipment->etd = $this->ifNull($attributes['pol_etd_at'],$attributes['pol_atd_at']);
                    }
                }
            }

            //auto filled to dos if the shipment is older than july 31, 2022
            if ( Carbon::parse($shipment->created_at)->lt(Carbon::parse('2022-07-31'))) {
                $this->autoFilledToDos($suppliers, $shipment, 1);
            }

            if ( $shipment->booking_confirmed === 1 ) {

                $diff_days = Carbon::parse($shipment->eta)->diffInDays(now(), false);
                $shipment->shipment_status = 'Shipments';

                if ( $shipment->status_v2 === null || $shipment->status_v2 === 'No Status' || $shipment->status_v2 === 'In transit') {

                    if (Carbon::parse($shipment->etd)->gt(now())) {
                        $shipment->shipment_status = 'Awaiting Departure';
                    }

                    if (Carbon::parse($shipment->etd)->lt(now())) {
                        $shipment->shipment_status = 'In Transit';
                    }

                    if ($diff_days > 60) {
                        $shipment->shipment_status = 'Completed';
                    }

                } else {

                    if ($shipment->status_v2==='Full Out' || $shipment->status_v2==='Empty In') {
                        $shipment->shipment_status = 'Completed';
                        //$shipment->shipment_status = $status_v2;
                    } else {

                        $shipment->original_shipment_status = $shipment->status_v2;
                        $shipment->shipment_status = $shipment->status_v2;

                        if ( $shipment->status_v2 === 'In transit - hold' ) {
                            $shipment->shipment_status = 'In Transit - Pending Release';
                        } elseif ( $shipment->status_v2 === 'Past last free day' ) {
                            $shipment->shipment_status = 'Past last free day';
                        } elseif ( $shipment->status_v2 === 'In transit - released' ) {
                            $shipment->shipment_status = 'In Transit - Released';
                        } elseif($shipment->status_v2 === 'Discharged - hold') {
                            $shipment->shipment_status = 'Discharged - Hold';
                        }

                        if ( $diff_days > 60 ) {
                            $shipment->shipment_status = 'Completed';
                        }
                    }
                }

            } else {

                //auto filled to dos
                $this->autoFilledToDos($suppliers, $shipment, 0);

                $shipment->shipment_status = 'Pending Approval';

                $is_pending_quote_counter = 0;
                if (count($schedules) > 0) {
                    foreach($schedules as $key => $schedule) {
                        $hasUnset = false;

                        $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                        if ( $eta >60 ) {
                            $hasUnset = true;
                            unset($schedules[$key]);
                        }
                        if ( !$hasUnset ) {
                            if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                if (count($schedule->sell_rates)==0) {
                                    $is_pending_quote_counter++;
                                    //unset($schedules[$key]);
                                    //$hasUnset = true;
                                }
                            } else {
                                $is_pending_quote_counter++;
                            }
                        }

                        /*
                        if ( !$hasUnset ) {
                            $hasUnset = true;
                            $etd = Carbon::parse($schedule->etd)->diffInDays(now(), false);
                            if ($etd>=-4) {
                                unset($schedules[$key]);
                            }
                        }*/

                    }
                }

                if (count($schedules)==0) {
                    $shipment->shipment_status = 'Expired';
                } else {
                    if ($shipment->snooze_date_at === null || $shipment->snooze_date_at <= now()) {
                        if ($is_pending_quote_counter > 0) {
                            $shipment->shipment_status = 'Pending Quote';
                        }
                    } else {
                        $shipment->shipment_status = 'Snoozed';
                    }
                }
            }

            //$shipment->schedules_group = json_encode($schedules);
            //$shipment->containers_group = json_encode($containers);
            //$shipment->suppliers_group = json_encode($suppliers);
            $shipment->schedules_group_bookings = json_encode($schedules);
            $shipment->containers_group_bookings = json_encode($containers);
            $shipment->suppliers_group_bookings = json_encode($suppliers);

            //tracking status
            $shipment->tracking_status = $shipment->getTrackingStatus();
            $shipment->shipment_suppliers = $suppliers;

            //shipment metas
            $shipment->shipment_metas = $shipment->shipmentMetas;

            if ( $request->has('include_orders') ) {

                $jwtToken = CustomJWTGenerator::generateToken();
                $res = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept'     => 'application/json',
                    'Authorization'  => 'Bearer ' . $jwtToken,
                ])->get(getenv('PO_URL').'/api/po/purchase-orders/purchase-orders-shipments/'.$shipment->id);
                $shipment->purchase_orders = $res->json();
            }

        } else {
            return abort(404);
        }
       //Change company_name to importname it shipment & customer has an import_name
       $shipment->customer->company_name = $shipment->getCustomerImportName();

        return new StandardResource($shipment);

    }

    public function checkShipmentStatus(Request $request)
    {
        $returnPoArray = [];
        if (isset($request->shipment_data)) {
            $bookingConfirmed = in_array($request->tab_name, ['booked', 'awaiting_shipment', 'in_transit', 'delivered']) ? 1 : 0;

            foreach ($request->shipment_data as $key => $value) {
                if (isset($value['po_id']) && count($value['shipment_ids']) > 0) {
                    $shipments = Shipment::where('booking_confirmed', $bookingConfirmed)
                        ->whereIn('id', $value['shipment_ids'])
                        ->get();
                    foreach ($shipments as $shipment) {
                        $containersGroupUntracked = json_decode($shipment->containers_group_untracked);
                        if ($request->tab_name == 'pending_quote' || $request->tab_name == 'booking_pending_approval') {
                            if (isset($shipment->schedules_group_bookings) && $shipment->schedules_group_bookings !== '') {
                                if ($this->isJson($shipment->schedules_group_bookings)) {
                                    $schedules = json_decode($shipment->schedules_group_bookings);

                                    if (count($schedules) > 0) {
                                        foreach ($schedules as $schedule) {
                                            if (isset($schedule->sell_rates) && $schedule->sell_rates !== '' && $schedule->sell_rates !== null) {
                                                if ($request->tab_name == 'pending_quote') {
                                                    if (count($schedule->sell_rates) == 0) {
                                                        array_push($returnPoArray, $value['po_id']);
                                                    }
                                                } elseif ($request->tab_name == 'booking_pending_approval') {
                                                    if (count($schedule->sell_rates) > 0) {
                                                        array_push($returnPoArray, $value['po_id']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } elseif ($request->tab_name == 'booked') {
                            array_push($returnPoArray, $value['po_id']);
                        } elseif (in_array($request->tab_name, ['awaiting_shipment', 'in_transit', 'delivered'])) {
                            if ($request->tab_name == 'awaiting_shipment' && $shipment->booking_confirmed == 1) {
                                array_push($returnPoArray, $value['po_id']);
                            }
                            if ($containersGroupUntracked && count($containersGroupUntracked->containers) > 0) {
                                foreach ($containersGroupUntracked->containers as $cntkey => $cntValue) {
                                    if ($request->tab_name == 'awaiting_shipment') {
                                        if ((
                                                (isset($cntValue->empty_out) && $cntValue->empty_out) ||
                                                (isset($cntValue->pod_full_in_at) && $cntValue->pod_full_in_at) ||
                                                $shipment->booking_confirmed == 1)
                                            && (!isset($cntValue->vessel_loaded) || !$cntValue->vessel_loaded)
                                            && (!isset($cntValue->pod_discharged_at) || !$cntValue->pod_discharged_at)
                                            && (!isset($cntValue->available_for_pickup) || !$cntValue->available_for_pickup)
                                            && (!isset($cntValue->pickup_lfd) || !$cntValue->pickup_lfd)
                                            && (!isset($cntValue->pod_full_out_at) || !$cntValue->pod_full_out_at)
                                            && (!isset($cntValue->pod_empty_returned_at) || !$cntValue->pod_empty_returned_at)) {
                                            array_push($returnPoArray, $value['po_id']);
                                        }
                                    } elseif ($request->tab_name == 'in_transit') {
                                        if ((
                                                (isset($cntValue->vessel_loaded) && $cntValue->vessel_loaded) ||
                                                (isset($cntValue->pod_discharged_at) && $cntValue->pod_discharged_at) ||
                                                (isset($cntValue->available_for_pickup) && $cntValue->available_for_pickup) ||
                                                (isset($cntValue->pickup_lfd) && $cntValue->pickup_lfd))
                                            && (!isset($cntValue->pod_full_out_at) || !$cntValue->pod_full_out_at)
                                            && (!isset($cntValue->pod_empty_returned_at) || !$cntValue->pod_empty_returned_at)) {
                                            array_push($returnPoArray, $value['po_id']);
                                        }
                                    } elseif ($request->tab_name == 'delivered') {
                                        if ((isset($cntValue->pod_full_out_at) && $cntValue->pod_full_out_at) || (isset($cntValue->pod_empty_returned_at) && $cntValue->pod_empty_returned_at)) {
                                            array_push($returnPoArray, $value['po_id']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (count($returnPoArray) > 0) {
            array_unique($returnPoArray);
        }
        return response()->json($returnPoArray);

    }

    public function getReferenceNumber(Request $request)
    {
        $returnValue = [];
        if (isset($request->shipment_ids)) {
            foreach ($request->shipment_ids as $shipmentId) {
                $shipment = Shipment::where('id', $shipmentId)->first();
                $containersGroupUntracked = json_decode($shipment->containers_group_untracked);
                if ($request->tab_name == 'pending_quote' || $request->tab_name == 'booking_pending_approval') {
                    if (isset($shipment->schedules_group_bookings) && $shipment->schedules_group_bookings !== '') {
                        if ($this->isJson($shipment->schedules_group_bookings)) {
                            $schedules = json_decode($shipment->schedules_group_bookings);

                            if (count($schedules) > 0) {
                                foreach ($schedules as $schedule) {
                                    if (isset($schedule->sell_rates) && $schedule->sell_rates !== '' && $schedule->sell_rates !== null) {
                                        if ($request->tab_name == 'pending_quote') {
                                            if (count($schedule->sell_rates) == 0) {
                                                !in_array($shipment->shifl_ref, $returnValue)? array_push($returnValue, $shipment->shifl_ref) : [];
                                            }
                                        } elseif ($request->tab_name == 'booking_pending_approval') {
                                            if (count($schedule->sell_rates) > 0) {
                                                !in_array($shipment->shifl_ref, $returnValue)? array_push($returnValue, $shipment->shifl_ref) : [];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } elseif ($request->tab_name == 'in_transit' || $request->tab_name == 'delivered' || $request->tab_name == 'awaiting_shipment') {
                    if ($request->tab_name == 'awaiting_shipment' || $shipment->booking_confirmed == 1) {
                        !in_array($shipment->shifl_ref, $returnValue)? array_push($returnValue, $shipment->shifl_ref) : [];
                    }
                    if ($containersGroupUntracked && count($containersGroupUntracked->containers) > 0) {
                        foreach ($containersGroupUntracked->containers as $cntkey => $cntValue) {
                            if ($request->tab_name == 'awaiting_shipment') {
                                if ((
                                        (isset($cntValue->empty_out) && $cntValue->empty_out) ||
                                        (isset($cntValue->pod_full_in_at) && $cntValue->pod_full_in_at) ||
                                        $shipment->booking_confirmed == 1)
                                    && (!isset($cntValue->vessel_loaded) || !$cntValue->vessel_loaded)
                                    && (!isset($cntValue->pod_discharged_at) || !$cntValue->pod_discharged_at)
                                    && (!isset($cntValue->available_for_pickup) || !$cntValue->available_for_pickup)
                                    && (!isset($cntValue->pickup_lfd) || !$cntValue->pickup_lfd)
                                    && (!isset($cntValue->pod_full_out_at) || !$cntValue->pod_full_out_at)
                                    && (!isset($cntValue->pod_empty_returned_at) || !$cntValue->pod_empty_returned_at)) {
                                    !in_array($shipment->shifl_ref, $returnValue)? array_push($returnValue, $shipment->shifl_ref) : [];
                                }
                            } elseif ($request->tab_name == 'in_transit') {
                                if ((
                                        (isset($cntValue->vessel_loaded) && $cntValue->vessel_loaded) ||
                                        (isset($cntValue->pod_discharged_at) && $cntValue->pod_discharged_at) ||
                                        (isset($cntValue->available_for_pickup) && $cntValue->available_for_pickup) ||
                                        (isset($cntValue->pickup_lfd) && $cntValue->pickup_lfd))
                                    && (!isset($cntValue->pod_full_out_at) || !$cntValue->pod_full_out_at)
                                    && (!isset($cntValue->pod_empty_returned_at) || !$cntValue->pod_empty_returned_at)) {
                                    !in_array($shipment->shifl_ref, $returnValue)? array_push($returnValue, $shipment->shifl_ref) : [];
                                }
                            } elseif ($request->tab_name == 'delivered') {
                                if ((isset($cntValue->pod_full_out_at) && $cntValue->pod_full_out_at) || (isset($cntValue->pod_empty_returned_at) && $cntValue->pod_empty_returned_at)) {
                                    !in_array($shipment->shifl_ref, $returnValue)? array_push($returnValue, $shipment->shifl_ref) : [];
                                }
                            }
                        }
                    }
                }
            }
            if (count($returnValue) > 0) {
                array_unique($returnValue);
            }
        }
        return response()->json($returnValue);
    }

    /**
     * Expired
     *
     * @authenticated
     *
     * @queryParam per_page int Size per page. Default to 30. Example: 30
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment with=customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     */

    public function indexExpired(Request $request)
    {
        //$customers = Auth::loginUsingId(11);

        $customers = Auth::user()->customersApi->pluck('id');

        $get_authenticated_user = Auth::user();
        try{
            $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        }catch (\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }

        $shipments = [];

        $newShipments = [];
        if (count($customers) > 0) {
            $buyerIds = Buyer::where('connected_customer', $customers[0])->pluck('id');
            $shipperIds = Supplier::where('connected_customer', $customers[0])->pluck('id');

            $shipments = Shipment::where(function($q) use ($customers, $buyerIds, $shipperIds) {
                $q->whereHas('customer', function($qq) use ($customers){
                    $qq->whereIn('id', $customers);
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($buyerIds) {
                    $qq->whereIn('buyer_id', $buyerIds);
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($shipperIds) {
                    $qq->whereIn('supplier_id', $shipperIds);
                });
            })
            ->where(function ($qq) {
                $qq->where('snooze_date_at', NULL);
                $qq->orWhere('snooze_date_at','<=', now());
            })
            ->where('booking_confirmed', 0)
            ->where('cancelled', 0)
            ->get();


            if (count($shipments) > 0) {
                foreach ($shipments as $key => $shipment) {
                    $should_push = true;
                    $findShipment = $shipment;
                    $getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    $shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['schedules_group'] = $findShipment['schedules_group_bookings'];
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                    $shipments[$key]['schedule_has_pricing'] = true;
                    $shipments[$key]['shipment_metas'] = $findShipment->shipmentMetas;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);
                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);
                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);
                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];
                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);

                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;
                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);
                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];

                    // new statuses
                    $shipments[$key]['status_v2'] = $shipment->getStatusV2() ?? null;
                    $isSpecial = false;
                    // check if tracking shipments
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);
                    if ($should_push) {
                        $shipments[$key]['tab_status'] = 'expired';
                        $shipments[$key]['diff_days'] = 100000;
                        $proceed = false;

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {
                            $proceed = true;
                        } else {
                            if (strpos($shipments[$key]['status_v2'], 'In transit')) {
                                $proceed = false;
                            }
                        }

                        if ( $proceed ){
                            if (isset($shipments[$key]['schedules_group_bookings']) && $shipments[$key]['schedules_group_bookings']!=='' && $shipments[$key]['schedules_group_bookings']!==null) {
                                $schedules = json_decode($shipments[$key]['schedules_group_bookings']);
                                if (count($schedules)>0) {
                                    foreach($schedules as $keySecond => $schedule) {
                                        $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                                        if ( $eta >60 ) {
                                            unset($schedules[$keySecond]);
                                        }
                                    }

                                    if (count($schedules)==0) {
                                        $shipments[$key]['schedules_group'] = json_encode([]);
                                        $shipments[$key]['schedules_group_bookings'] = json_encode([]);
                                        $shipments[$key]['shipment_status'] = 'Expired';
                                        array_push($newShipments, $shipments[$key]);
                                    }
                                } else {
                                    $shipments[$key]['schedules_group'] = json_encode([]);
                                    $shipments[$key]['schedules_group_bookings'] = json_encode([]);
                                    $shipments[$key]['shipment_status'] = 'Expired';
                                    array_push($newShipments, $shipments[$key]);
                                }
                            } else {
                                $shipments[$key]['schedules_group'] = json_encode([]);
                                $shipments[$key]['schedules_group_bookings'] = json_encode([]);
                                $shipments[$key]['shipment_status'] = 'Expired';
                                array_push($newShipments, $shipments[$key]);
                            }
                        }
                    }
                    }
                }
            }


        if (is_null($request->per_page)) {
            //return StandardResource::collection((new Collection($newShipments)));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        return abort(404);
    }

    /**
     * Pending
     *
     * @authenticated
     *
     * @queryParam per_page int Size per page. Default to 30. Example: 30
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment with=customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
    */

    public function indexPending(Request $request)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();
        try {
            $customers = ($get_authenticated_user->default_customer_id !== null) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        }catch (\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }

        $newShipments = [];
        if (count($customers) > 0) {

            $buyerIds = Buyer::where('connected_customer', $customers[0])->pluck('id');
            $shipperIds = Supplier::where('connected_customer', $customers[0])->pluck('id');

            $shipments = Shipment::where(function($q) use ($customers, $buyerIds, $shipperIds) {
                $q->whereHas('customer', function($qq) use ($customers){
                    $qq->whereIn('id', $customers);
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($buyerIds) {
                    $qq->whereIn('buyer_id', $buyerIds);
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($shipperIds) {
                    $qq->whereIn('supplier_id', $shipperIds);
                });
            })->where(function ($qq) {
                $qq->where('snooze_date_at', NULL);
                $qq->orWhere('snooze_date_at','<=', now());
            })
            ->where('booking_confirmed', 0)
            ->where('cancelled', 0)
            ->get();

            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;

                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    $getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    $shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['schedules_group'] = $findShipment['schedules_group_bookings'];
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];

                    $shipments[$key]['schedule_has_pricing'] = true;
                    $shipments[$key]['shipment_metas'] = $findShipment->shipmentMetas;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);
                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);
                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);
                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];
                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;
                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);
                    $shipments[$key]['containers_group_bookings']  = $shipments[$key]['containers_group'];
                    $shipments[$key]['status_v2'] = $shipment->getStatusV2();
                    $isSpecial = false;
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);

                    if ($should_push) {
                        $shipments[$key]['tab_status'] = 'pending_approval';
                        $shipments[$key]['diff_days'] = 100000;
                        $proceed = false;

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {
                            $proceed = true;
                        } else {
                            if (strpos($shipments[$key]['status_v2'], 'In transit')) {
                                $proceed = false;
                            }
                        }

                        if ( $proceed ){
                            if (isset($shipments[$key]['schedules_group_bookings']) && $shipments[$key]['schedules_group_bookings']!=='' && $shipments[$key]['schedules_group_bookings']!==null) {
                                $schedules = json_decode($shipments[$key]['schedules_group_bookings']);
                                if (count($schedules)>0) {

                                    foreach($schedules as $keySecond => $schedule) {
                                        $hasUnset = false;
                                        if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                            if (count($schedule->sell_rates)==0) {
                                                unset($schedules[$keySecond]);
                                                $hasUnset = true;
                                            }
                                        } else {
                                            unset($schedules[$keySecond]);
                                            $hasUnset = true;
                                        }

                                        if ( !$hasUnset ) {
                                            $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                                            if ($eta>60) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }
                                    }

                                    if (count($schedules)>0) {
                                        $shipments[$key]['schedules_group'] = json_encode($schedules);
                                        array_push($newShipments, $shipments[$key]);
                                    }
                                }
                            }

                        }
                    }
                    }
                }
            }


        if (is_null($request->per_page)) {
            //return StandardResource::collection((new Collection($newShipments)));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        return abort(404);
    }


    /**
     * Completed
     *
     * @authenticated
     *
     * @queryParam per_page int Size per page. Default to 30. Example: 30
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment with=customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
    */

    public function indexCompletedTest(Request $request)
    {

        $customers = Auth::user()->customersApi->pluck('id');
        $order_by = $request->has('sort') ? $request->input('sort') : 'eta';
        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $shipments = [];
        $isSpecial = false;

        $get_authenticated_user = Auth::user();

        try {

          $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        } catch (\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }

        $newShipments = [];
        if ( count($customers) > 0) {
            $buyerIds = Buyer::where('connected_customer', $customers[0])->pluck('id');
            $shipperIds = Supplier::where('connected_customer', $customers[0])->pluck('id');
            $per_page = 20;

            $sort = $request->has('sort') ? $request->sort : 'eta';
            $order = $request->has('order') ? $request->order : 'asc';

            $shipments = Shipment::where(function($q) use ($customers, $buyerIds, $shipperIds) {
                $q->whereHas('customer', function($qq) use ($customers){
                    $qq->whereIn('id', $customers);
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($buyerIds) {
                    $qq->whereIn('buyer_id', $buyerIds);
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($shipperIds) {
                    $qq->whereIn('supplier_id', $shipperIds);
                });
            })
            ->where('booking_confirmed', 1)
            ->where('cancelled', 0)
            ->where(function($q) {
                $q->where(function($qq) {
                    $qq->whereNotNull('eta')
                    ->whereNotNull('etd')
                    ->where('eta','<>','0000-00-00')
                    ->where('etd','<>','0000-00-00')
                    ->whereDate('eta', '<', Carbon::now()->subDays(60));

                    $qq->where(function($qqqq) {
                        $qqqq->where('status_fallback','No Status');
                        $qqqq->orWhere('status_fallback','<>','In transit');
                    });
                });
                $q->orWhere(function($qq) {
                    $qq->whereNotNull('tracking_eta')
                    ->whereNotNull('tracking_etd')
                    ->whereDate('tracking_eta', '<', Carbon::now()->subDays(60))
                    ->orWhere(function($qqqq) {
                        $qqqq->whereNotNull('status_fallback');
                        $qqqq->where(function($qqqqq) {
                            $qqqqq->where('status_fallback','Full Out');
                            $qqqqq->orWhere('status_fallback','Empty In');
                        });
                    });
                });

                $q->orWhere(function($qq) {
                    $qq->whereNull('tracking_eta')
                    ->whereNull('tracking_etd')
                    ->whereNotNull('eta')
                    ->whereNotNull('etd')
                    ->where('eta','<>','0000-00-00')
                    ->where('etd','<>','0000-00-00')
                    ->whereDate('eta', '<', Carbon::now()->subDays(60))
                    ->orWhere(function($qqqq) {
                        $qqqq->whereNotNull('status_fallback');
                        $qqqq->where(function($qqqqq) {
                            $qqqqq->where('status_fallback','Full Out');
                            $qqqqq->orWhere('status_fallback','Empty In');
                        });
                    });
                });
            })
            ->orderBy($sort, $order)
            ->paginate($per_page);

            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;
                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;
                    //$getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    //$shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['carrier'] = $findShipment->carrier;
                    $shipments[$key]['shipment_metas'] = $findShipment->shipmentMetas;
                    $shipments[$key]['schedules_group'] = $findShipment['schedules_group_bookings'];
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                    $shipments[$key]['schedule_has_pricing'] = true;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);

                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);

                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);

                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;

                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);

                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];

                    // new statuses
                    //$shipments[$key]['status_v2'] = $shipment->getStatusV2();
                    $shipments[$key]['status_v2'] = $shipment->status_fallback;

                    // check if tracking shipments
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);
                    //

                    //tracking status
                    $shipments[$key]['tracking_status'] = $findShipment->getTrackingStatus();
                    //$shipments[$key]['tracking_status'] = '';


                    $completed = false;

                    if ($shipments[$key]['booking_confirmed']==0) {
                        $should_push = false;
                    }

                    if ($should_push) {

                        // current day minus eta
                        $diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = $diff_days;
                        $proceed = false;
                        $shipments[$key]['shipment_status'] = 'Shipments';

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {

                            if ($shipments[$key]['etd']!==null && $shipments[$key]['etd']!=='') {
                                $proceed = true;
                                if (Carbon::parse($shipments[$key]->etd)->gt(now())) {
                                    $shipments[$key]['shipment_status'] = 'Awaiting Departure';
                                }
                                if (Carbon::parse($shipments[$key]->etd)->lt(now())) {
                                    $shipments[$key]['shipment_status'] = 'In Transit';
                                }
                            }

                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {
                                if ($diff_days<60) {
                                    $proceed = true;
                                } else {
                                    $proceed = false;
                                }
                            } else {
                                $proceed = false;
                            }

                            //for completed
                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {

                                if ($diff_days > 60) {
                                    $proceed = false;
                                    //$proceed = true;
                                    //$completed = true;
                                    //$shipments[$key]['shipment_status'] = 'Completed';
                                }
                            }

                            if ( $shipments[$key]['mbl_num'] !=='' && $shipments[$key]['mbl_num']!==null && ($shipments[$key]['eta']==null || $shipments[$key]['eta']=='' || $shipments[$key]['etd']==null || $shipments[$key]['etd']=='')) {
                                $proceed = true;
                            }


                        } else {

                            if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {
                                $proceed = false;

                            } else {

                                $shipments[$key]['original_shipment_status'] = $shipments[$key]['status_v2'];
                                $shipments[$key]['shipment_status'] = $shipments[$key]['status_v2'];

                                if ( $shipments[$key]['status_v2'] === 'In transit - hold' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Pending Release';
                                } elseif ( $shipments[$key]['status_v2'] === 'Past last free day' ) {
                                    $shipments[$key]['shipment_status'] = 'Past last free day';
                                } elseif ( $shipments[$key]['status_v2'] === 'In transit - released' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Released';
                                } elseif($shipments[$key]['status_v2'] === 'Discharged - hold') {
                                    $shipments[$key]['shipment_status'] = 'Discharged - Hold';
                                }

                                if ($diff_days > 60) {
                                    $proceed = false;
                                    //$completed = true;
                                    //$shipments[$key]['shipment_status'] = 'Completed';
                                } else {
                                    $proceed = true;
                                }

                            }


                        }


                    }
                    //e
                }
            }
        }

        return response()->json([
            'data' => $shipments->items(),
            'links' => [
                'prev' => $shipments->previousPageUrl(),
                'next' => $shipments->nextPageUrl()
            ],
            'meta' => [
                'current_page' => $shipments->currentPage(),
                'from' => $shipments->firstItem(),
                'last_page' => $shipments->lastPage(),
                'path' => $shipments->path(),
                'per_page' => $shipments->perPage(),
                'to' => $shipments->lastItem(),
                'total' =>$shipments->total(),
            ]
        ]);

        return abort(404);

    }



    public function indexCompletedTestBeta(Request $request)
    {

        $subFifteen = Carbon::now()->subDays(15);
        $subForty = Carbon::now()->subDays(40);

        $customers = Auth::user()->customersApi->pluck('id');
        $order_by = $request->has('sort') ? $request->input('sort') : 'eta';
        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $shipments = [];
        $isSpecial = false;

        $get_authenticated_user = Auth::user();

        try {

          $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        } catch (\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }

        $newShipments = [];
        if ( count($customers) > 0) {

            $per_page = 20;

            $sort = $request->has('sort') ? $request->sort : 'eta';
            $order = $request->has('order') ? $request->order : 'asc';

            $shipments = Shipment::whereIn('customer_id', $customers)
            ->where('booking_confirmed', 1)
            ->where('cancelled', 0)
            ->where(function($q) use ($subForty, $subFifteen) {
                $q->where(function($qq) {
                    $qq->whereNotNull('eta')
                    ->whereNotNull('etd')
                    ->where('eta','<>','0000-00-00')
                    ->where('etd','<>','0000-00-00')
                    ->whereDate('eta', '<', Carbon::now()->subDays(60));

                    $qq->where(function($qqqq) {
                        $qqqq->where('status_fallback','No Status');
                        $qqqq->orWhere('status_fallback','<>','In transit');
                    });
                });

                $q->orWhere(function($qq) {
                    $qq->whereNotNull('tracking_eta')
                    ->whereNotNull('tracking_etd')
                    ->whereDate('tracking_eta', '<', Carbon::now()->subDays(60))
                    ->orWhere(function($qqqq) {
                        $qqqq->whereNotNull('status_fallback');
                        $qqqq->where(function($qqqqq) {
                            $qqqqq->orWhere('status_fallback','Empty In');
                        });
                    });
                });

                $q->orWhere(function($qq) {
                    $qq->whereNull('tracking_eta')
                    ->whereNull('tracking_etd')
                    ->whereNotNull('eta')
                    ->whereNotNull('etd')
                    ->where('eta','<>','0000-00-00')
                    ->where('etd','<>','0000-00-00')
                    ->whereDate('eta', '<', Carbon::now()->subDays(60))
                    ->orWhere(function($qqqq) {
                        $qqqq->whereNotNull('status_fallback');
                        $qqqq->where(function($qqqqq) {
                            $qqqqq->orWhere('status_fallback','Empty In');
                        });
                    });
                });

                $q->orWhere(function($qq) use ($subForty) {
                    $qq->where('schedules_group_bookings', '<>', '')
                        ->where('schedules_group_bookings', '<>', '[]')
                        ->where('schedules_group_bookings', '<>', '[{}]')
                        ->whereRaw('JSON_CONTAINS(schedules_group_bookings, \'{"is_confirmed": 1}\', \'$\')')
                        ->whereRaw('JSON_CONTAINS(schedules_group_bookings, \'{"type": "FCL"}\', \'$\')')
                        ->where('status_fallback', 'Full Out')
                        ->where(function($qq) use ($subForty) {

                            $qq->where(function($qqq) {
                                $qqq->where('containers_return_count','>',0)
                                ->whereRaw('containers_return_count = containers_count');
                            })
                            ->where(function($qq) use ($subForty) {
                                $qq->orWhere(function($qqqq) use ($subForty){
                                    $qqqq->whereNotNull('containers_array')
                                    ->where('containers_array', '<>', '[]')
                                    ->whereExists(function($qqqqq) use ($subForty){
                                        $qqqqq->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(full_out_at_array, "$[*]")) < ?', [$subForty]);
                                    });
                                });
                            });
                        });
                });

                $q->orWhere(function($qq) use ($subFifteen) {
                    $qq->where('schedules_group_bookings', '<>', '')
                        ->where('schedules_group_bookings', '<>', '[]')
                        ->where('schedules_group_bookings', '<>', '[{}]')
                        ->whereRaw('JSON_CONTAINS(schedules_group_bookings, \'{"is_confirmed": 1}\', \'$\')')
                        ->whereRaw('JSON_CONTAINS(schedules_group_bookings, \'{"type": "LCL"}\', \'$\')')
                        ->where('status_fallback', 'Full Out')
                        ->where(function($qq) use ($subFifteen) {

                            $qq->orWhere(function($qqqq) use ($subFifteen){
                                $qqqq->whereNotNull('containers_array')
                                ->where('containers_array', '<>', '[]')
                                ->whereExists(function($qqqqq) use ($subFifteen){
                                    $qqqqq->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(full_out_at_array, "$[*]")) < ?', [$subFifteen]);
                                });
                            });
                        });
                });
            })
            ->orderBy($sort, $order)
            ->paginate($per_page);

            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;
                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;
                    //$getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    //$shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['carrier'] = $findShipment->carrier;
                    $shipments[$key]['shipment_metas'] = $findShipment->shipmentMetas;
                    $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'], $findShipment['schedules_group']);
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                    $shipments[$key]['schedule_has_pricing'] = true;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);

                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);

                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);

                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;

                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);

                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];

                    // new statuses
                    //$shipments[$key]['status_v2'] = $shipment->getStatusV2();
                    $shipments[$key]['status_v2'] = $shipment->status_fallback;

                    // check if tracking shipments
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);
                    //

                    //tracking status
                    $shipments[$key]['tracking_status'] = $findShipment->getTrackingStatus();
                    //$shipments[$key]['tracking_status'] = '';


                    $completed = false;

                    if ($shipments[$key]['booking_confirmed']==0) {
                        $should_push = false;
                    }

                    if ($should_push) {

                        // current day minus eta
                        $diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = $diff_days;
                        $proceed = false;
                        $shipments[$key]['shipment_status'] = 'Shipments';

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {

                            if ($shipments[$key]['etd']!==null && $shipments[$key]['etd']!=='') {
                                $proceed = true;
                                if (Carbon::parse($shipments[$key]->etd)->gt(now())) {
                                    $shipments[$key]['shipment_status'] = 'Awaiting Departure';
                                }
                                if (Carbon::parse($shipments[$key]->etd)->lt(now())) {
                                    $shipments[$key]['shipment_status'] = 'In Transit';
                                }
                            }

                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {
                                if ($diff_days<60) {
                                    $proceed = true;
                                } else {
                                    $proceed = false;
                                }
                            } else {
                                $proceed = false;
                            }

                            //for completed
                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {

                                if ($diff_days > 60) {
                                    $proceed = false;
                                    //$proceed = true;
                                    //$completed = true;
                                    //$shipments[$key]['shipment_status'] = 'Completed';
                                }
                            }

                            if ( $shipments[$key]['mbl_num'] !=='' && $shipments[$key]['mbl_num']!==null && ($shipments[$key]['eta']==null || $shipments[$key]['eta']=='' || $shipments[$key]['etd']==null || $shipments[$key]['etd']=='')) {
                                $proceed = true;
                            }


                        } else {

                            if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {
                                $proceed = false;

                            } else {

                                $shipments[$key]['original_shipment_status'] = $shipments[$key]['status_v2'];
                                $shipments[$key]['shipment_status'] = $shipments[$key]['status_v2'];

                                if ( $shipments[$key]['status_v2'] === 'In transit - hold' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Pending Release';
                                } elseif ( $shipments[$key]['status_v2'] === 'Past last free day' ) {
                                    $shipments[$key]['shipment_status'] = 'Past last free day';
                                } elseif ( $shipments[$key]['status_v2'] === 'In transit - released' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Released';
                                } elseif($shipments[$key]['status_v2'] === 'Discharged - hold') {
                                    $shipments[$key]['shipment_status'] = 'Discharged - Hold';
                                }

                                if ($diff_days > 60) {
                                    $proceed = false;
                                    //$completed = true;
                                    //$shipments[$key]['shipment_status'] = 'Completed';
                                } else {
                                    $proceed = true;
                                }

                            }


                        }


                    }
                    //e
                }
            }
        }

        return response()->json([
            'data' => $shipments->items(),
            'links' => [
                'prev' => $shipments->previousPageUrl(),
                'next' => $shipments->nextPageUrl()
            ],
            'meta' => [
                'current_page' => $shipments->currentPage(),
                'from' => $shipments->firstItem(),
                'last_page' => $shipments->lastPage(),
                'path' => $shipments->path(),
                'per_page' => $shipments->perPage(),
                'to' => $shipments->lastItem(),
                'total' =>$shipments->total(),
            ]
        ]);

        return abort(404);

    }

















    /**
     * Completed
     *
     * @authenticated
     *
     * @queryParam per_page int Size per page. Default to 30. Example: 30
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment with=customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
    */

    public function indexCompleted(Request $request)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $order_by = $request->has('sort') ? $request->input('sort') : 'eta';
        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $get_authenticated_user = Auth::user();

        try{
            $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        }catch (\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }

        $shipments = [];
        $isSpecial = false;

        $newShipments = [];

        if (count($customers) > 0) {

            $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                $q->whereIn('id', $customers);
            })
            ->where('cancelled', 0)
            ->get();
            /*
            if ($request->has('sort') && $request->input('sort')=='eta') {
                $order = ($request->has('order')) ? $request->input('order') : 'desc';
                $valid_orders = ['asc','desc'];

                if (in_array($order, $valid_orders)) {
                    $shipments = $shipments->orderBy('eta', $order)->get();
                } else {
                    $shipments = $shipments->orderBy('eta', 'desc')->get();
                }
            } else {
                $shipments = $shipments->orderBy('eta', 'desc')->get();
            }*/

            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;

                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    $getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    $shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'], $findShipment['schedules_group']);
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                    $shipments[$key]['schedule_has_pricing'] = true;
                    $shipments[$key]['shipment_metas'] = $findShipment->shipmentMetas;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);
                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);
                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);
                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        // code...
                        array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;
                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);
                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];
                    // new statuses
                    $shipments[$key]['status_v2'] = $shipment->getStatusV2();
                    //$shipments[$key]['status_v2'] = $shipment->status_fallback;
                    // check if tracking shipments
                    $isSpecial = false;
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);

                    //tracking status
                    $shipments[$key]['tracking_status'] = $findShipment->getTrackingStatus();
                    //$shipments[$key]['tracking_status'] = '';

                    if ( $shipments[$key]['booking_confirmed']==0 ) {
                        $should_push = false;
                    }
                    //
                    if ($should_push) {

                        $shipments[$key]['tab_status'] = 'completed';

                        // current day minus eta
                        $diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = $diff_days;
                        $proceed = false;

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {
                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {
                                if ($diff_days > 60) {
                                    $proceed = true;
                                }
                            }
                        } else {
                            if (isset($shipments[$key]['selected_schedule_type']) && $shipments[$key]['selected_schedule_type']=='FCL') {
                                $maxDays = 40;
                            } elseif (isset($shipments[$key]['selected_schedule_type']) && $shipments[$key]['selected_schedule_type']=='LCL'){
                                $maxDays = 15;
                            }
                            $isFullOutPassed = false;
                            if(isset($maxDays))
                                $isFullOutPassed = $shipment->isFullOutPassed($maxDays);

                            if ($shipments[$key]['status_v2'] === 'Empty In' || ( $shipments[$key]['status_v2'] === 'Full Out' && $isFullOutPassed ))
                                $proceed = true;
                        }

                        if ( $proceed ){
                            if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='' && $shipments[$key]['schedules_group']!==null && !$isSpecial) {

                                $schedules = json_decode($shipments[$key]['schedules_group']);
                                if (count($schedules)>0) {

                                    foreach($schedules as $keySecond => $schedule) {
                                        $hasUnset = false;
                                        /*
                                        if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                            if (count($schedule->sell_rates)==0) {
                                                unset($schedules[$keySecond]);
                                                $hasUnset = true;
                                            }
                                        } else {
                                            unset($schedules[$keySecond]);
                                            $hasUnset = true;
                                        }*/
                                    }

                                    if (count($schedules)>0) {
                                        $shipments[$key]['schedules_group'] = json_encode($schedules);
                                        $shipments[$key]['shipment_status'] = 'Completed';
                                        array_push($newShipments, $shipments[$key]);
                                        /*
                                        if ($diff_days<20){
                                            array_push($newShipments, $shipments[$key]);
                                        }*/
                                    }

                                }
                            } else {
                                if ( $isSpecial ) {
                                    $shipments[$key]['shipment_status'] = 'Completed';
                                    array_push($newShipments, $shipments[$key]);
                                }
                            }
                        }

                    }
                    //e

                    }
                }
            }

        if ( count ($newShipments) > 0 ) {
            usort($newShipments, function($a, $b) use ($order, $order_by) {
                if ( $order === 'desc' ) {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                } else {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                }
            });


        }

        if (is_null($request->per_page)) {
            //return StandardResource::collection((new Collection($newShipments)));
            //return StandardResource::collection((new Collection($newShipments))->paginate(30));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            //return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        return abort(404);
    }

    /**
     * Transit/Completed
     *
     *
     * @queryParam per_page int
     * @queryParam sort string
     * @queryParam order string
     *
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment paginate=30 with=customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function indexTransitCompleted(Request $request)
    {

        $customers = Auth::user()->customersApi->pluck('id');
        $order_by = $request->has('sort') ? $request->input('sort') : 'eta';
        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $shipments = [];
        $isSpecial = false;

        $get_authenticated_user = Auth::user();
        try{
          $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        }catch(\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }
        $newShipments = [];
        if (count($customers) > 0) {

            $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                $q->whereIn('id', $customers);
            })
            //->where('booking_confirmed', 1)
            ->where('cancelled', 0);
            /*
            if ($request->has('sort')) {

                $order = ($request->has('order')) ? $request->input('order') : 'desc';
                $order_by = $request->input('sort');

                $valid_orders = ['asc', 'desc'];
                $valid_sorts = ['eta', 'etd'];

                if (in_array($order, $valid_orders) && in_array($order_by, $valid_sorts)) {
                   // $shipments = $shipments->orderBy($order_by, $order)->get();
                } else {
                    //$shipments = $shipments->orderBy($order_by, 'desc')->get();
                }
            } else {
                //$shipments = $shipments->orderBy('eta', 'desc')->get();
            }*/

            $shipments = $shipments->get();
            $sort_shipments = [];
            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;
                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    $getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    $shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'], $findShipment['schedules_group']);
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                    $shipments[$key]['schedule_has_pricing'] = true;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);

                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);

                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);

                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;

                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);

                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];

                    // new statuses
                    $shipments[$key]['status_v2'] = $shipment->getStatusV2();

                    // check if tracking shipments
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);
                    //

                    //tracking status
                    $shipments[$key]['tracking_status'] = $findShipment->getTrackingStatus();

                    $completed = false;

                    if ($shipments[$key]['booking_confirmed']==0) {
                        $should_push = false;
                    }

                    if ($should_push) {

                        // current day minus eta
                        $diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = $diff_days;
                        $proceed = false;
                        $shipments[$key]['shipment_status'] = 'Shipments';

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {

                            if ($shipments[$key]['etd']!==null && $shipments[$key]['etd']!=='') {
                                $proceed = true;
                                if (Carbon::parse($shipments[$key]->etd)->gt(now())) {
                                    $shipments[$key]['shipment_status'] = 'Awaiting Departure';
                                }
                                if (Carbon::parse($shipments[$key]->etd)->lt(now())) {
                                    $shipments[$key]['shipment_status'] = 'In Transit';
                                }
                            }

                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {
                                /*
                                if ($diff_days<60) {
                                    $proceed = true;
                                } else {
                                    $proceed = false;
                                }*/
                                $proceed = true;
                            } else {
                                //$proceed = true;
                                $proceed = false;
                            }


                            //for completed
                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {

                                if ($diff_days > 60) {
                                    //$proceed = false;
                                    $proceed = true;
                                    $completed = true;
                                    $shipments[$key]['shipment_status'] = 'Completed';
                                }

                            }

                        } else {

                            if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {

                                $completed = true;
                                $shipments[$key]['shipment_status'] = 'Completed';
                                $proceed = true;
                                //for completed
                                /*
                                if (isset($shipments[$key]['selected_schedule_type']) && $shipments[$key]['selected_schedule_type']=='LCL') {
                                    if ( $diff_days>60 ) {
                                        //$proceed = false;
                                        $completed = true;
                                        $shipments[$key]['shipment_status'] = 'Completed';
                                        $proceed = true;
                                    } else {
                                        $proceed = true;
                                        //$proceed = false;
                                    }
                                } else {
                                    $completed = true;
                                    $shipments[$key]['shipment_status'] = 'Completed';
                                    $proceed = true;
                                }*/
                                //$proceed = false;

                            } else {

                                $shipments[$key]['original_shipment_status'] = $shipments[$key]['status_v2'];
                                $shipments[$key]['shipment_status'] = $shipments[$key]['status_v2'];

                                if ( $shipments[$key]['status_v2'] === 'In transit - hold' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Pending Release';
                                } elseif ( $shipments[$key]['status_v2'] === 'Past last free day' ) {
                                    $shipments[$key]['shipment_status'] = 'Past last free day';
                                } elseif ( $shipments[$key]['status_v2'] === 'In transit - released' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Released';
                                } elseif($shipments[$key]['status_v2'] === 'Discharged - hold') {
                                    $shipments[$key]['shipment_status'] = 'Discharged - Hold';
                                }


                                if ($diff_days > 60) {
                                    $proceed = true;
                                    $completed = true;
                                    $shipments[$key]['shipment_status'] = 'Completed';
                                } else {

                                    if ( $shipments[$key]['eta']===null || $shipments[$key]['etd']==null ) {
                                        $proceed = true;
                                        //$proceed = false;
                                    } else {
                                        $proceed = true;
                                    }

                                }

                            }


                        }

                        if ( $proceed ) {
                            if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='' && $shipments[$key]['schedules_group']!==null && !$isSpecial) {

                                $schedules = json_decode($shipments[$key]['schedules_group']);
                                if (count($schedules)>0) {

                                    foreach($schedules as $keySecond => $schedule) {
                                        $hasUnset = false;

                                        /*
                                        if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                            if (count($schedule->sell_rates)==0) {
                                                unset($schedules[$keySecond]);
                                                $hasUnset = true;
                                            }
                                        } else {
                                            unset($schedules[$keySecond]);
                                            $hasUnset = true;
                                        }*/

                                        if ( !$hasUnset && !$completed ) {
                                            $hasUnset = true;
                                            $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                                            /*
                                            if ($eta>60) {
                                                unset($schedules[$keySecond]);
                                            }*/
                                        }

                                    }
                                    if (count($schedules)>0) {
                                        $shipments[$key]['schedules_group'] = json_encode($schedules);
                                        array_push($newShipments, $shipments[$key]);
                                    }
                                }


                            } else {
                                if ($isSpecial) {
                                    array_push($newShipments, $shipments[$key]);
                                }
                            }
                        }

                    }

                    //e
                    }


                }
            }

        if ( count ($newShipments) > 0 ) {

            usort($newShipments, function($a, $b) use ($order, $order_by) {
                if ( $order === 'desc' ) {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                } else {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                }
            });


        }

        if (is_null($request->per_page)) {
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
            //return StandardResource::collection((new Collection($newShipments)))->paginate(30)
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
        }



        return abort(404);
    }



    public function indexShipmentsByIds(Request $request) {

        //empty by default
        $shipments = [];

        //get json request input
        $data = json_decode($request->getContent());

        //get all ids
        $ids = (isset($data->ids)) ? $data->ids : [];

        if ( is_countable($ids) && count($ids) > 0 ) {
            //query shipments
            $shipments = Shipment::whereIn('id', $ids)->get();
        }

        return response()->json([
            'items' => $shipments
        ]);

    }

    /**
     * Transit
     *
     * @authenticated
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment paginate=30 with=customer
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function indexTransit(Request $request)
    {

        $customers = Auth::user()->customersApi->pluck('id');
        $order_by = $request->has('sort') ? $request->input('sort') : 'eta';
        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $shipments = [];
        $isSpecial = false;

        $get_authenticated_user = Auth::user();
        try {
          $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        } catch (\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }

        $newShipments = [];

        if ( count($customers) > 0) {

            $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                $q->whereIn('id', $customers);
            })
            //->where('booking_confirmed', 1)
            ->where('cancelled', 0)
            //->where('is_draft', 0)
            ->get();
            /*
            if ( $request->has('new')) {
                // new logic

            } else {
                $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                    $q->whereIn('id', $customers);
                })
                //->where('booking_confirmed', 1)
                ->where('cancelled', 0)
                //->where('is_draft', 0)
                ->get();
            }*/
            /*
            $shipments = Shipment::whereHas('customer', function($q) use ($customers){
                $q->whereIn('id', $customers);
            })
            //->where('booking_confirmed', 1)
            ->where('cancelled', 0)
            //->where('is_draft', 0)
            ->get();*/
            /*
            $per_page = 20;

            $sort = $request->has('sort') ? $request->sort : 'eta';
            $order = $request->has('order') ? $request->order : 'asc';

            $shipments = Shipment::whereIn('customer_id', $customers)
            ->where('booking_confirmed', 1)
            ->where('cancelled', 0)
            ->where(function($q) {
                $q->where(function($qq) {
                    $qq->whereNotNull('eta')
                    ->whereNotNull('etd')
                    ->whereDate('eta', '>', Carbon::now()->subDays(60));
                    $qq->where(function($qqqq) {
                        $qqqq->where('status_fallback','No Status');
                        $qqqq->orWhere('status_fallback', 'In transit');
                    });
                });
                $q->orWhere(function($qq) {
                    $qq->whereNotNull('tracking_eta')
                    ->whereNotNull('tracking_etd')
                    ->whereDate('tracking_eta', '>', Carbon::now()->subDays(60));
                    $qq->whereNotNull('status_fallback');
                    $qq->where('status_fallback','<>','No Status');
                    $qq->where('status_fallback', '<>','Full Out');
                    $qq->where('status_fallback', '<>','Empty In');
                });
            })
            ->orderBy($sort, $order)
            ->paginate($per_page);*/
            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;
                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    //$getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    //$shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['carrier'] = $findShipment->carrier;
                    $shipments[$key]['shipment_metas'] = $findShipment->shipmentMetas;
                    $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'], $findShipment['schedules_group']);
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                    $shipments[$key]['schedule_has_pricing'] = true;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);

                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);

                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);

                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;

                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);

                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];

                    // new statuses
                    $shipments[$key]['status_v2'] = $shipment->getStatusV2();
                    //$shipments[$key]['status_v2'] = $shipment->status_fallback;

                    // check if tracking shipments
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);
                    //

                    //tracking status
                    $shipments[$key]['tracking_status'] = $findShipment->getTrackingStatus();
                    //$shipments[$key]['tracking_status'] = '';


                    $completed = false;

                    if ($shipments[$key]['booking_confirmed']==0) {
                        $should_push = false;
                    }

                    if ($should_push) {

                        // current day minus eta
                        $diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = $diff_days;
                        $proceed = false;
                        $shipments[$key]['shipment_status'] = 'Shipments';

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status' || $shipments[$key]['status_v2']=='In transit') {

                            if ($shipments[$key]['etd']!==null && $shipments[$key]['etd']!=='') {
                                $proceed = true;
                                if (Carbon::parse($shipments[$key]->etd)->gt(now())) {
                                    $shipments[$key]['shipment_status'] = 'Awaiting Departure';
                                }
                                if (Carbon::parse($shipments[$key]->etd)->lt(now())) {
                                    $shipments[$key]['shipment_status'] = 'In Transit';
                                }
                            }

                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {
                                if ($diff_days<60) {
                                    $proceed = true;
                                } else {
                                    $proceed = false;
                                }
                            } else {
                                $proceed = false;
                            }

                            //for completed
                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {

                                if ($diff_days > 60) {
                                    $proceed = false;
                                    //$proceed = true;
                                    //$completed = true;
                                    //$shipments[$key]['shipment_status'] = 'Completed';
                                }
                            }

                            if ( $shipments[$key]['mbl_num'] !=='' && $shipments[$key]['mbl_num']!==null && ($shipments[$key]['eta']==null || $shipments[$key]['eta']=='' || $shipments[$key]['etd']==null || $shipments[$key]['etd']=='')) {
                                $proceed = true;
                            }


                        } else {

                            if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {

                                /*
                                //for completed
                                if (isset($shipments[$key]['selected_schedule_type']) && $shipments[$key]['selected_schedule_type']=='LCL') {
                                    if ( $diff_days>60 ) {
                                        $proceed = false;
                                        //$completed = true;
                                        //$shipments[$key]['shipment_status'] = 'Completed';
                                        //$proceed = true;
                                    } else {
                                        $proceed = true;
                                        //$proceed = false;
                                    }
                                } else {
                                    $completed = true;
                                    $shipments[$key]['shipment_status'] = 'Completed';
                                    $proceed = true;
                                }*/
                                $proceed = false;

                            } else {

                                $shipments[$key]['original_shipment_status'] = $shipments[$key]['status_v2'];
                                $shipments[$key]['shipment_status'] = $shipments[$key]['status_v2'];

                                if ( $shipments[$key]['status_v2'] === 'In transit - hold' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Pending Release';
                                } elseif ( $shipments[$key]['status_v2'] === 'Past last free day' ) {
                                    $shipments[$key]['shipment_status'] = 'Past last free day';
                                } elseif ( $shipments[$key]['status_v2'] === 'In transit - released' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Released';
                                } elseif($shipments[$key]['status_v2'] === 'Discharged - hold') {
                                    $shipments[$key]['shipment_status'] = 'Discharged - Hold';
                                }



                                if ($diff_days > 60) {
                                    $proceed = false;
                                    //$completed = true;
                                    //$shipments[$key]['shipment_status'] = 'Completed';
                                } else {
                                    $proceed = true;
                                }

                            }


                        }

                        if ( $proceed ) {

                            $shipments[$key]['tab_status'] = 'transit';

                            if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='' && $shipments[$key]['schedules_group']!==null && !$isSpecial) {

                                $schedules = json_decode($shipments[$key]['schedules_group']);
                                if (count($schedules)>0) {

                                    foreach($schedules as $keySecond => $schedule) {
                                        $hasUnset = false;

                                        /*
                                        if (isset($schedule->sell_rates) && $schedule->sell_rates!=='' && $schedule->sell_rates!==null) {
                                            if (count($schedule->sell_rates)==0) {
                                                unset($schedules[$keySecond]);
                                                $hasUnset = true;
                                            }
                                        } else {
                                            unset($schedules[$keySecond]);
                                            $hasUnset = true;
                                        }*/

                                        if ( !$hasUnset && !$completed ) {
                                            $hasUnset = true;
                                            $eta = Carbon::parse($schedule->eta)->diffInDays(now(), false);
                                            if ($eta>60) {
                                                unset($schedules[$keySecond]);
                                            }
                                        }

                                    }
                                    if (count($schedules)>0) {
                                        $shipments[$key]['schedules_group'] = json_encode($schedules);
                                        array_push($newShipments, $shipments[$key]);
                                    }
                                }


                            } else {
                                if ($isSpecial) {
                                    array_push($newShipments, $shipments[$key]);
                                }
                            }
                        }

                    }
                    //e

                    }
                }
            }

        if ( count ($newShipments) > 0 ) {

            usort($newShipments, function($a, $b) use ($order, $order_by) {
                if ( $order === 'desc' ) {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                } else {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                }
            });

        }


        if (is_null($request->per_page)) {
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
            //return StandardResource::collection((new Collection($newShipments)))->paginate(30)
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            //return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }
        /*
        return response()->json([
            'data' => $shipments->items(),
            'links' => [
                'prev' => $shipments->previousPageUrl(),
                'next' => $shipments->nextPageUrl()
            ],
            'meta' => [
                'current_page' => $shipments->currentPage(),
                'from' => $shipments->firstItem(),
                'last_page' => $shipments->lastPage(),
                'path' => $shipments->path(),
                'per_page' => $shipments->perPage(),
                'to' => $shipments->lastItem(),
                'total' =>$shipments->total(),
            ]
        ]);*/
        return abort(404);
    }


    public function indexTransitTestBeta(Request $request)
    {

        $subFifteen = Carbon::now()->subDays(15);
        $subForty = Carbon::now()->subDays(40);

        $customers = Auth::user()->customersApi->pluck('id');
        $order_by = $request->has('sort') ? $request->input('sort') : 'eta';
        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $shipments = [];
        $isSpecial = false;

        $get_authenticated_user = Auth::user();

        try {

          $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        } catch (\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }

        $newShipments = [];
        if ( count($customers) > 0) {

            $per_page = 20;

            $sort = $request->has('sort') ? $request->sort : 'eta';
            $order = $request->has('order') ? $request->order : 'asc';

            $shipments = Shipment::whereIn('customer_id', $customers)
            ->where('booking_confirmed', 1)
            ->where('cancelled', 0)
            ->where(function($q) use ($subForty, $subFifteen){
                $q->where(function($qq) {
                    $qq->whereNotNull('eta')
                    ->whereNotNull('etd')
                    ->where('eta','<>','0000-00-00')
                    ->where('etd','<>','0000-00-00')
                    ->whereDate('eta', '>', Carbon::now()->subDays(60));
                    $qq->where(function($qqqq) {
                        $qqqq->where('status_fallback','No Status');
                        $qqqq->orWhere('status_fallback', 'In transit');
                    });
                });


                $q->orWhere(function($qq) {
                    $qq->whereNotNull('tracking_eta')
                    ->whereNotNull('tracking_etd')
                    ->whereDate('tracking_eta', '>', Carbon::now()->subDays(60));
                    $qq->whereNotNull('status_fallback');
                    $qq->where('status_fallback','<>','No Status');
                    //$qq->where('status_fallback', '<>','Full Out');
                    $qq->where('status_fallback', '<>','Empty In');
                });

                $q->orWhere(function($qq) {
                    $qq->whereNull('tracking_eta')
                    ->whereNull('tracking_etd')
                    ->whereNotNull('eta')
                    ->whereNotNull('etd')
                    ->where('eta','<>','0000-00-00')
                    ->where('etd','<>','0000-00-00')
                    ->whereDate('eta', '>', Carbon::now()->subDays(60));
                    $qq->where(function($qqqq) {
                        $qqqq->whereNotNull('status_fallback');
                        $qqqq->where('status_fallback','No Status');
                        $qqqq->orWhere('status_fallback', 'In transit');
                    });
                });



                $q->orWhere(function($qq) {
                    $qq->whereNull('tracking_eta')
                    ->whereNull('tracking_etd')
                    ->whereNotNull('eta')
                    ->whereNotNull('etd')
                    ->where('eta','<>','0000-00-00')
                    ->where('etd','<>','0000-00-00')
                    ->whereDate('eta', '<', Carbon::now()->subDays(60))
                    ->orWhere(function($qqqq) {
                        $qqqq->whereNotNull('status_fallback');
                        $qqqq->where(function($qqqqq) {
                            $qqqqq->orWhere('status_fallback','Empty In');
                        });
                    });
                });

                $q->orWhere(function($qq) use ($subForty) {
                    $qq->where('schedules_group_bookings', '<>', '')
                        ->where('schedules_group_bookings', '<>', '[]')
                        ->where('schedules_group_bookings', '<>', '[{}]')
                        ->whereRaw('JSON_CONTAINS(schedules_group_bookings, \'{"is_confirmed": 1}\', \'$\')')
                        ->whereRaw('JSON_CONTAINS(schedules_group_bookings, \'{"type": "FCL"}\', \'$\')')
                        ->where('status_fallback', 'Full Out')
                        ->where(function($qq) use ($subForty) {

                            $qq->where(function($qqq) {
                                $qqq->where('containers_return_count','>',0)
                                ->whereRaw('containers_return_count = containers_count');
                            })
                            ->where(function($qq) use ($subForty) {
                                $qq->orWhere(function($qqqq) use ($subForty){
                                    $qqqq->whereNotNull('containers_array')
                                    ->where('containers_array', '<>', '[]')
                                    ->whereExists(function($qqqqq) use ($subForty){
                                        $qqqqq->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(full_out_at_array, "$[*]")) > ?', [$subForty]);
                                    });
                                });
                            });
                        });
                });

                $q->orWhere(function($qq) use ($subFifteen) {
                    $qq->where('schedules_group_bookings', '<>', '')
                        ->where('schedules_group_bookings', '<>', '[]')
                        ->where('schedules_group_bookings', '<>', '[{}]')
                        ->whereRaw('JSON_CONTAINS(schedules_group_bookings, \'{"is_confirmed": 1}\', \'$\')')
                        ->whereRaw('JSON_CONTAINS(schedules_group_bookings, \'{"type": "FCL"}\', \'$\')')
                        ->where('status_fallback', 'Full Out')
                        ->where(function($qq) use ($subFifteen) {

                            $qq->where(function($qqq) {
                                $qqq->where('containers_return_count','>',0)
                                ->whereRaw('containers_return_count = containers_count');
                            })
                            ->where(function($qq) use ($subFifteen) {
                                $qq->orWhere(function($qqqq) use ($subFifteen){
                                    $qqqq->whereNotNull('containers_array')
                                    ->where('containers_array', '<>', '[]')
                                    ->whereExists(function($qqqqq) use ($subFifteen){
                                        $qqqqq->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(full_out_at_array, "$[*]")) > ?', [$subFifteen]);
                                    });
                                });
                            });
                        });
                });


            })
            ->orderBy($sort, $order)
            ->paginate($per_page);

            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;
                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    //$getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    //$shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['carrier'] = $findShipment->carrier;
                    $shipments[$key]['shipment_metas'] = $findShipment->shipmentMetas;
                    $shipments[$key]['schedules_group'] = $this->merge_group($findShipment['schedules_group_bookings'], $findShipment['schedules_group']);
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                    $shipments[$key]['schedule_has_pricing'] = true;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);

                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);

                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);

                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;

                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);

                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];

                    // new statuses
                    //$shipments[$key]['status_v2'] = $shipment->getStatusV2();
                    $shipments[$key]['status_v2'] = $shipment->status_fallback;

                    // check if tracking shipments
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);
                    //

                    //tracking status
                    $shipments[$key]['tracking_status'] = $findShipment->getTrackingStatus();
                    //$shipments[$key]['tracking_status'] = '';


                    $completed = false;

                    if ($shipments[$key]['booking_confirmed']==0) {
                        $should_push = false;
                    }

                    if ($should_push) {

                        // current day minus eta
                        $diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = $diff_days;
                        $proceed = false;
                        $shipments[$key]['shipment_status'] = 'Shipments';

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {

                            if ($shipments[$key]['etd']!==null && $shipments[$key]['etd']!=='') {
                                $proceed = true;
                                if (Carbon::parse($shipments[$key]->etd)->gt(now())) {
                                    $shipments[$key]['shipment_status'] = 'Awaiting Departure';
                                }
                                if (Carbon::parse($shipments[$key]->etd)->lt(now())) {
                                    $shipments[$key]['shipment_status'] = 'In Transit';
                                }
                            }

                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {
                                if ($diff_days<60) {
                                    $proceed = true;
                                } else {
                                    $proceed = false;
                                }
                            } else {
                                $proceed = false;
                            }

                            //for completed
                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {

                                if ($diff_days > 60) {
                                    $proceed = false;
                                    //$proceed = true;
                                    //$completed = true;
                                    //$shipments[$key]['shipment_status'] = 'Completed';
                                }
                            }

                            if ( $shipments[$key]['mbl_num'] !=='' && $shipments[$key]['mbl_num']!==null && ($shipments[$key]['eta']==null || $shipments[$key]['eta']=='' || $shipments[$key]['etd']==null || $shipments[$key]['etd']=='')) {
                                $proceed = true;
                            }


                        } else {

                            if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {
                                $proceed = false;

                            } else {

                                $shipments[$key]['original_shipment_status'] = $shipments[$key]['status_v2'];
                                $shipments[$key]['shipment_status'] = $shipments[$key]['status_v2'];

                                if ( $shipments[$key]['status_v2'] === 'In transit - hold' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Pending Release';
                                } elseif ( $shipments[$key]['status_v2'] === 'Past last free day' ) {
                                    $shipments[$key]['shipment_status'] = 'Past last free day';
                                } elseif ( $shipments[$key]['status_v2'] === 'In transit - released' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Released';
                                } elseif($shipments[$key]['status_v2'] === 'Discharged - hold') {
                                    $shipments[$key]['shipment_status'] = 'Discharged - Hold';
                                }



                                if ($diff_days > 60) {
                                    $proceed = false;
                                    //$completed = true;
                                    //$shipments[$key]['shipment_status'] = 'Completed';
                                } else {
                                    $proceed = true;
                                }

                            }


                        }


                    }
                    //e
                }
            }
        }

        /*
        if ( count ($newShipments) > 0 ) {

            usort($newShipments, function($a, $b) use ($order, $order_by) {
                if ( $order === 'desc' ) {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                } else {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                }
            });

        }


        if (is_null($request->per_page)) {
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
            //return StandardResource::collection((new Collection($newShipments)))->paginate(30)
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            //return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }*/

        return response()->json([
            'data' => $shipments->items(),
            'links' => [
                'prev' => $shipments->previousPageUrl(),
                'next' => $shipments->nextPageUrl()
            ],
            'meta' => [
                'current_page' => $shipments->currentPage(),
                'from' => $shipments->firstItem(),
                'last_page' => $shipments->lastPage(),
                'path' => $shipments->path(),
                'per_page' => $shipments->perPage(),
                'to' => $shipments->lastItem(),
                'total' =>$shipments->total(),
            ]
        ]);

        return abort(404);
    }


    public function indexTransitTest(Request $request)
    {
        $customers = Auth::user()->customersApi->pluck('id');
        $shipments = [];
        $isSpecial = false;
        $get_authenticated_user = Auth::user();

        try {
          $customers = ( $get_authenticated_user->default_customer_id!==null ) ? [$get_authenticated_user->default_customer_id] : [$customers[0]];
        } catch (\Exception $exception){
            return response([
                'message'=>'No results found.'
            ], 404);
        }

        if ( count($customers) > 0) {
            $buyerIds = Buyer::where('connected_customer', $customers[0])->pluck('id');
            $shipperIds = Supplier::where('connected_customer', $customers[0])->pluck('id');

            $per_page = 20;
            $sort = $request->has('sort') ? $request->sort : 'eta';
            $order = $request->has('order') ? $request->order : 'asc';

            $shipments = Shipment::where(function($q) use ($customers, $buyerIds, $shipperIds) {
                $q->whereHas('customer', function($qq) use ($customers){
                    $qq->whereIn('id', $customers);
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($buyerIds) {
                    $qq->whereIn('buyer_id', $buyerIds);
                });
                $q->orWhereHas('shipmentSuppliers', function($qq) use ($shipperIds) {
                    $qq->whereIn('supplier_id', $shipperIds);
                });
            })
            ->where('cancelled', 0)
            ->where(function($q) {
                $q->where(function($qq) {
                    $qq->whereNotNull('eta')
                    ->whereNotNull('etd')
                    ->where('eta','<>','0000-00-00')
                    ->where('etd','<>','0000-00-00')
                    ->whereDate('eta', '>', Carbon::now()->subDays(60));
                    $qq->where(function($qqqq) {
                        $qqqq->where('status_fallback','No Status');
                        $qqqq->orWhere('status_fallback', 'In transit');
                        $qqqq->orWhere('status_fallback', 'In transit - hold');
                    });
                });
                $q->orWhere(function($qq) {
                    $qq->whereNotNull('tracking_eta')
                    ->whereNotNull('tracking_etd')
                    ->whereDate('tracking_eta', '>', Carbon::now()->subDays(60));
                    $qq->whereNotNull('status_fallback');
                    $qq->where('status_fallback','<>','No Status');
                    $qq->where('status_fallback', '<>','Full Out');
                    $qq->where('status_fallback', '<>','Empty In');
                    $qq->orWhere('status_fallback', 'In transit - hold');
                });
                $q->orWhere(function($qq) {
                    $qq->whereNull('tracking_eta')
                    ->whereNull('tracking_etd')
                    ->whereNotNull('eta')
                    ->whereNotNull('etd')
                    ->where('eta','<>','0000-00-00')
                    ->where('etd','<>','0000-00-00')
                    ->whereDate('eta', '>', Carbon::now()->subDays(60));
                    $qq->where(function($qqqq) {
                        $qqqq->whereNotNull('status_fallback');
                        $qqqq->where('status_fallback','No Status');
                        $qqqq->orWhere('status_fallback', 'In transit');
                        $qqqq->orWhere('status_fallback', 'In transit - hold');
                    });
                });
            })
            ->orderBy($sort, $order)
            ->paginate($per_page);

            if (count($shipments) > 0) {

                foreach ($shipments as $key => $shipment) {

                    $should_push = true;
                    //schedule merge
                    //merge group fields start
                    $findShipment = $shipment;

                    //$getCarrier = Carrier::find(intval($findShipment['carrier_id']));
                    //$shipments[$key]['carrier'] = (isset($getCarrier->id)) ? $getCarrier : null;
                    $shipments[$key]['carrier'] = $findShipment->carrier;
                    $shipments[$key]['shipment_metas'] = $findShipment->shipmentMetas;
                    $shipments[$key]['schedules_group'] = $findShipment['schedules_group_bookings'];
                    $shipments[$key]['schedules_group_bookings'] = $shipments[$key]['schedules_group'];
                    $shipments[$key]['schedule_has_pricing'] = true;

                    if (isset($shipments[$key]['schedules_group']) && $shipments[$key]['schedules_group']!=='') {
                        $getShipmentSchedules = json_decode($shipments[$key]['schedules_group']);
                        $this->processShipmentSchedules($getShipmentSchedules, $shipments, $key, $this);
                    }

                    $shipments[$key]['location_from_name'] = $this->cleanField($shipments[$key]['location_from_name']);

                    $shipments[$key]['location_to_name'] = $this->cleanField($shipments[$key]['location_to_name']);

                    $shipments[$key]['suppliers_group'] = $this->merge_group($findShipment['suppliers_group_bookings'], $findShipment['suppliers_group']);

                    $shipments[$key]['suppliers_group_bookings'] = $shipments[$key]['suppliers_group'];

                    $suppliers_group = json_decode($shipments[$key]['suppliers_group']);
                    $suppliers_name = [];
                    foreach ($suppliers_group ?? [] as $supplier) {
                        array_push($suppliers_name, $this->getSupplierName($supplier, $shipment->customer_id));
                    }
                    $shipments[$key]["suppliers_name"] = $suppliers_name;

                    $shipments[$key]['containers_group'] = $this->merge_group($findShipment['containers_group_bookings'], $findShipment['containers_group']);

                    $shipments[$key]['containers_group_bookings'] = $shipments[$key]['containers_group'];

                    // new statuses
                    //$shipments[$key]['status_v2'] = $shipment->getStatusV2();
                    $shipments[$key]['status_v2'] = $shipment->status_fallback;

                    // check if tracking shipments
                    $this->processTrackingShipments($findShipment, $shipments, $key, $this, $isSpecial);
                    //

                    //tracking status
                    $shipments[$key]['tracking_status'] = $findShipment->getTrackingStatus();
                    //$shipments[$key]['tracking_status'] = '';


                    $completed = false;

                    if ($shipments[$key]['booking_confirmed']==0) {
                        $should_push = false;
                    }

                    if ($should_push) {

                        // current day minus eta
                        $diff_days = Carbon::parse($shipments[$key]['eta'])->diffInDays(now(), false);
                        $shipments[$key]['diff_days'] = $diff_days;
                        $proceed = false;
                        $shipments[$key]['shipment_status'] = 'Shipments';

                        if ($shipments[$key]['status_v2']==null || $shipments[$key]['status_v2']=='No Status') {

                            if ($shipments[$key]['etd']!==null && $shipments[$key]['etd']!=='') {
                                $proceed = true;
                                if (Carbon::parse($shipments[$key]->etd)->gt(now())) {
                                    $shipments[$key]['shipment_status'] = 'Awaiting Departure';
                                }
                                if (Carbon::parse($shipments[$key]->etd)->lt(now())) {
                                    $shipments[$key]['shipment_status'] = 'In Transit';
                                }
                            }

                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {
                                if ($diff_days<60) {
                                    $proceed = true;
                                } else {
                                    $proceed = false;
                                }
                            } else {
                                $proceed = false;
                            }

                            //for completed
                            if ($shipments[$key]['eta']!==null && $shipments[$key]['eta']!=='') {

                                if ($diff_days > 60) {
                                    $proceed = false;
                                    //$proceed = true;
                                    //$completed = true;
                                    //$shipments[$key]['shipment_status'] = 'Completed';
                                }
                            }

                            if ( $shipments[$key]['mbl_num'] !=='' && $shipments[$key]['mbl_num']!==null && ($shipments[$key]['eta']==null || $shipments[$key]['eta']=='' || $shipments[$key]['etd']==null || $shipments[$key]['etd']=='')) {
                                $proceed = true;
                            }


                        } else {

                            if ($shipments[$key]['status_v2']==='Full Out' || $shipments[$key]['status_v2']==='Empty In') {
                                $proceed = false;

                            } else {

                                $shipments[$key]['original_shipment_status'] = $shipments[$key]['status_v2'];
                                $shipments[$key]['shipment_status'] = $shipments[$key]['status_v2'];

                                if ( $shipments[$key]['status_v2'] === 'In transit - hold' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Pending Release';
                                } elseif ( $shipments[$key]['status_v2'] === 'Past last free day' ) {
                                    $shipments[$key]['shipment_status'] = 'Past last free day';
                                } elseif ( $shipments[$key]['status_v2'] === 'In transit - released' ) {
                                    $shipments[$key]['shipment_status'] = 'In Transit - Released';
                                } elseif($shipments[$key]['status_v2'] === 'Discharged - hold') {
                                    $shipments[$key]['shipment_status'] = 'Discharged - Hold';
                                }



                                if ($diff_days > 60) {
                                    $proceed = false;
                                    //$completed = true;
                                    //$shipments[$key]['shipment_status'] = 'Completed';
                                } else {
                                    $proceed = true;
                                }

                            }


                        }


                    }
                    //e
                }
            }
        }

        /*
        if ( count ($newShipments) > 0 ) {

            usort($newShipments, function($a, $b) use ($order, $order_by) {
                if ( $order === 'desc' ) {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                } else {
                    if ( intval(strtotime($a[$order_by])) > intval(strtotime($b[$order_by])) ) {
                        return 1;
                    } elseif( intval(strtotime($a[$order_by])) < intval(strtotime($b[$order_by])) ) {
                        return -1;
                    } elseif ( intval(strtotime($a[$order_by])) === intval(strtotime($b[$order_by])) ) {
                        return 0;
                    }
                }
            });

        }


        if (is_null($request->per_page)) {
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
            //return StandardResource::collection((new Collection($newShipments)))->paginate(30)
        }
        if (is_numeric($request->per_page)) {
            // return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            //return StandardResource::collection((new Collection($newShipments))->paginate($request->per_page));
            return StandardResource::collection((new Collection($newShipments))->paginate(30));
        }*/

        return response()->json([
            'data' => $shipments->items(),
            'links' => [
                'prev' => $shipments->previousPageUrl(),
                'next' => $shipments->nextPageUrl()
            ],
            'meta' => [
                'current_page' => $shipments->currentPage(),
                'from' => $shipments->firstItem(),
                'last_page' => $shipments->lastPage(),
                'path' => $shipments->path(),
                'per_page' => $shipments->perPage(),
                'to' => $shipments->lastItem(),
                'total' =>$shipments->total(),
            ]
        ]);

        return abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @urlParam id int require Shipment ID
     *
     * @apiResourceCollection App\Http\Resources\Shipment
     * @apiResourceModel App\Shipment
     *
     * @response status=400 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $shipment = Shipment::findOrFail($id);
        } catch (\Exception $e) {
            return abort(404);
        }
        $customer_admins = $shipment->customer->customerAdminsApi;
        $scheds = $shipment->shipmentSchedules;

        foreach ($customer_admins as $customer_admin) {
            if ($customer_admin->id == Auth::guard('api')->id() && $customer_admin->hasRole('Customer Admin')) {
                $ship = Shipment::where('id', $id)->with('customer', 'containers', 'suppliers', 'carrier', 'terminalRegions', 'shipmentSuppliers', 'shipmentSchedules')->firstOrFail();
                if (isset($ship['shipmentSchedules'][0])) {
                    $location_from_id = $ship['shipmentSchedules'][0]['location_from'];
                    $getTerminalRegionFrom = TerminalRegion::find($location_from_id);

                    if (isset($getTerminalRegionFrom->id) && $getTerminalRegionFrom->id == $location_from_id) {
                        $location_from_name = $getTerminalRegionFrom->name;
                        $ship['location_from_name'] =  $location_from_name;
                    } else {
                        $ship['location_from_name'] =  '';
                    }

                    $location_to_id = $ship['shipmentSchedules'][0]['location_to'];
                    $getTerminalRegionTo = TerminalRegion::find($location_to_id);

                    if (isset($getTerminalRegionTo->id) && $getTerminalRegionTo->id == $location_to_id) {
                        $location_to_name = $getTerminalRegionTo->name;
                        $ship['location_to_name'] =  $location_to_name;
                    } else {
                        $ship['location_to_name'] =  '';
                    }
                } else {
                    $ship['location_from_name'] =  '';
                    $ship['location_to_name'] =  '';
                }

                $results = new StandardResource($ship);

                return $results;
            }
        }

        return abort(404);
    }

    public function createShipmentWithoutMbl(Request $request)
    {
        try {
            $response = ['status' => 'not ok'];

            $customer_id = $this->getCurrentSelectedCustomer();

            $validator = Validator::make($request->all(), [
                'date_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'errors' => $validator->errors(),
                        'status' => 'not ok'
                    ]
                    , 401);
            } else {
                $args = $request->all();
                $carrier_name_label = Carrier::find(intval($args['carrier_id']));
                $carrier_name_label = isset($carrier_name_label->name) ? $carrier_name_label->name : '';

                //create shipment schedules
                $schedules = [[
                    'id' => $args['date_id'],
                    'eta' => $this->formatDate($args['eta']),
                    'etd' => $this->formatDate($args['etd']),
                    'location_from' => isset($args['location_from']) ? intval($args['location_from']) : 0,
                    'location_to' => isset($args['location_to']) ? intval($args['location_to']) : 0,
                    'mode' => isset($args['mode']) ? $args['mode'] : '',
                    'legs' => [],
                    'type' => isset($args['type']) ? $args['type'] : '',
                    'carrier_name' => [
                        'id' => (isset($args['carrier_id'])) ? intval($args['carrier_id']) : 0
                    ],
                    'carrier_name_label' => $carrier_name_label,
                    'is_confirmed' => 0,
                    'size_id' => null,
                    'price' => null,
                    'selling_size_id' => null,
                    'selling_price' => null,
                    'sell_rates' => [],
                    'buy_rates' => [],
                ]];

                $suppliers = [];

                $incoterm = $this->getIncotermByName($args['inco_term']);
                $incotermId = $incoterm ? $incoterm->id : null;

                if (isset($args['purchase_orders']) && count($args['purchase_orders']) > 0) {

                    foreach ($args['purchase_orders'] as $key => $purchase_order) {

                        // count weight, carton, volume
                        $total_cartons = null;
                        $total_volume = null;
                        $total_weight = null;
                        if($purchase_order['layout'] == 'default') {
                            foreach($purchase_order['products'] as $key => $purchase_product) {
                                $total_cartons += isset($purchase_product['carton']) ? $purchase_product['carton'] : 0;
                                $total_volume += isset($purchase_product['volume']) ? $purchase_product['volume'] : 0;
                                $total_weight += isset($purchase_product['weight']) ? $purchase_product['weight'] : 0;
                            }
                        } else {
                            $total_weight = isset($purchase_order['total_weights']) ? $purchase_order['total_weights'] : 0;
                            $total_cartons = isset($purchase_order['total_cartons']) ? $purchase_order['total_cartons'] : 0;
                            $total_volume = isset($purchase_order['total_volumes']) ? $purchase_order['total_volumes'] : 0;
                        }

                        array_push($suppliers, [
                            'id' => strval(hexdec(uniqid())),
                            'hbl_copy' => null,
                            'packing_list' => null,
                            'commercial_documents' => null,
                            'commercial_invoice' => null,
                            'po_id' => $purchase_order['layout'] == 'default' ? !empty($purchase_order['purchase_order_id']) ? $purchase_order['purchase_order_id'] : '' : '',
                            'po_num' => '',
                            'volume' => '',
                            'supplier_id' => $purchase_order['supplier_id'],
                            'buyer_id' => $purchase_order['buyer_id'],
                            'cargo_ready_date' => '',
                            'kg' => $total_weight,
                            'cbm' => $total_volume,
                            'incoterm_id' => $incotermId,
                            'hbl_num' => '',
                            'product_description' => '',
                            'total_cartons' => $total_cartons,
                            'bl_status' => 'Pending',
                            'ams_num' => '',
                            'containers' => [],
                            'isOpenAddPurchaseOrders' => false,
                        ]);

                        $products = [];
                        //remove special fields
                        if (count($purchase_order['products']) > 0) {
                            foreach ($purchase_order['products'] as $product) {
                                if (!isset($product['special']) && !isset($product['addSpecial'])) {
                                    array_push($products, [
                                        'id' => $product['product_id'],
                                        'is_shipment' => 1,
                                        'ship_cartons' => intval($product['carton'])
                                    ]);
                                }
                            }
                        }
                        $args['purchase_orders'][$key]['products'] = $products;
                    }

                } else {

                    $suppliers = [[
                        'id' => strval(hexdec(uniqid())),
                        'hbl_copy' => null,
                        'packing_list' => null,
                        'commercial_documents' => null,
                        'commercial_invoice' => null,
                        'po_id' => '',
                        'po_num' => '',
                        'volume' => '',
                        'supplier_id' => null,
                        'buyer_id' => null,
                        'cargo_ready_date' => '',
                        'kg' => '',
                        'cbm' => '',
                        'incoterm_id' => $incotermId,
                        'hbl_num' => '',
                        'product_description' => '',
                        'total_cartons' => '',
                        'bl_status' => 'Pending',
                        'ams_num' => '',
                        'containers' => [],
                        'isOpenAddPurchaseOrders' => false
                    ]];

                }

                if ($customer_id !== 0) {

                    $created_shipment = Shipment::create([
                        'schedules_group_bookings' => json_encode($schedules),
                        'schedules_group' => json_encode($schedules),
                        'suppliers_group_bookings' => json_encode($suppliers),
                        'suppliers_group' => json_encode($suppliers),
                        'containers_group_bookings' => json_encode($args['containers']),
                        'containers_group' => json_encode($args['containers']),
                        'mbl_num' => $args['mbl_num'],
                        'mode' => '',
                        'eta' => $schedules[0]['eta'],
                        'etd' => $schedules[0]['etd'],
                        'carrier_id' => intval($args['carrier_id']),
                        'booking_number' => $args['booking_num'],
                        'vessel' => $args['vessel'],
                        'voyage_number' => $args['voyage_number'],
                        'customer_reference_number' => $args['customer_reference_number'],
                        'customer_id' => $customer_id,
                        'is_tracking_shipment' => 0,
                        'booking_confirmed' => 0,
                        'terminal_id' => intval($args['terminal']),
                        'is_shipment_tracking_create' => 1,
                        'not_tracking_manually' => 1,
                    ]);

                    if (isset($created_shipment->id)) {

                        //update again because observer automatically create a dummy schedule for schedules group bookings when creating for tracking shipment
                        $created_shipment->eta = $schedules[0]['eta'];
                        $created_shipment->etd = $schedules[0]['etd'];
                        $created_shipment->containers_group_bookings = json_encode($args['containers']);
                        $created_shipment->schedules_group_bookings = json_encode($schedules);
                        $created_shipment->schedules_group = json_encode($schedules);
                        $created_shipment->containers_group = json_encode($args['containers']);
                        $created_shipment->save();

                        $response['status'] = 'ok';
                        $response['shipment_id'] = $created_shipment->id;
                        $response['shipment'] = $created_shipment;
                        if (isset($args['purchase_orders']) && count($args['purchase_orders']) > 0) {
                            try {
                                $jwtToken = CustomJWTGenerator::generateToken();
                                $res = Http::withHeaders([
                                    'Content-Type' => 'application/json',
                                    'Accept' => 'application/json',
                                    'Authorization' => 'Bearer ' . $jwtToken,
                                ])->post(getenv('PO_URL') . '/api/po/purchase-orders/purchase-orders-shipments', [
                                    'purchase_orders' => $args['purchase_orders'],
                                    'shipment_id' => $created_shipment->id
                                ]);
                            } catch (Exception $e) {

                            }
//                            $response['po_response'] = $res->json();
                        }

                    } else {
                        $response['special_error'] = 'An unexpected error occured.';
                    }
                } else {
                    $response['special_error'] = 'An unexpected error occured.';
                }

            }
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage(),
                'message' => 'Internal Server Error, Please try again later.',
                'status' => 500
            ], 500);
        }
    }
}
