<?php

namespace App\Http\Controllers\PO_INSTANCE;

use App\Custom\CustomJWTGenerator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Shipment;

class PurchaseOrdersController extends Controller
{
    //
    private static $token = null;

    function __construct() {
        self::$token = CustomJWTGenerator::generateToken();
    }

    public function getPurchaseOrders(Request $request, $customer_id){
        $client = new \GuzzleHttp\Client([
            'base_uri' => getenv('PO_URL'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'     => 'application/json',
                'Authorization'  => 'Bearer ' . self::$token,
            ],
        ]);

        $res = $client->get('/api/po/customers/'. $customer_id .'/purchase-orders',[
            'query' => [
                'searchText' => $request->query('searchText'),
                'supplierId' => $request->query('supplierId'),
                'buyerIds' => $request->query('buyerIds'),
                'conneCustId' => $request->query('conneCustId'),
            ]
        ]);
        return response()->json(json_decode($res->getBody()));
    }

    /**
     * Get sales order by buyerId
     * @param Request $request
     * @param $customer_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSalesOrders(Request $request, $customer_id){
        $client = new \GuzzleHttp\Client([
            'base_uri' => getenv('PO_URL'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'     => 'application/json',
                'Authorization'  => 'Bearer ' . self::$token,
            ],
        ]);

        $res = $client->get('/api/po/customers/'. $customer_id .'/sales-orders',[
            'query' => [
                'searchText' => $request->query('searchText'),
                'buyerId' => $request->query('buyerId'),
                'supplierIds' => $request->query('supplierIds'),
                'conneCustId' => $request->query('conneCustId'),
            ]
        ]);
        return response()->json(json_decode($res->getBody()));
    }

    public function getPurchaseOrderProducts($purchase_order_id){

        $client = new \GuzzleHttp\Client([
            'base_uri' => getenv('PO_URL'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'     => 'application/json',
                'Authorization'  => 'Bearer ' . self::$token,
            ]
        ]);

        $res = $client->get('/api/po/purchase-orders/'. $purchase_order_id .'/products');
        return response()->json(json_decode($res->getBody()));
    }

    public function getShipmentPurchaseOrders(Request $request, $shipment_id){

        $client = new \GuzzleHttp\Client([
            'base_uri' => getenv('PO_URL'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'     => 'application/json',
                'Authorization'  => 'Bearer ' . self::$token,
            ]
        ]);

        $res = $client->get('/api/po/shipments/'. $shipment_id .'/purchase-orders',[
            'query' => [
                'supplierId' => $request->query('supplierId')
            ]
        ]);
        return response()->json(json_decode($res->getBody()));
    }

    public function getCustomerOrders(Request $request, $id,$order_type){
        $client = new \GuzzleHttp\Client([
            'base_uri' => getenv('PO_URL'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'     => 'application/json',
                'Authorization'  => 'Bearer ' . self::$token,
            ],
        ]);

        $res = $client->get('/api/orders/'. $id .'/'. $order_type );

        return response()->json(json_decode($res->getBody()));
    }

    public function removeOrdersFromShipment(Request $request, $shipmentId)
    {
        Shipment::where('id', $shipmentId)->update(['suppliers_group' => null, 'suppliers_group_bookings' => null]);

        $client = new \GuzzleHttp\Client([
            'base_uri' => env('PO_URL'),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . self::$token,
            ]
        ]);

        $res = $client->delete('/api/po/shipments/' . $shipmentId . '/orders');

        return response()->json(json_decode($res->getBody()));
    }
}
