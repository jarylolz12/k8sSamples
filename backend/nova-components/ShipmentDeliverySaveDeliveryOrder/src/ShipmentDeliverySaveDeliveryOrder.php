<?php

namespace Kenetashi\ShipmentDeliverySaveDeliveryOrder;

use Laravel\Nova\Fields\Field;
use App\Shipment;
use App\Trucker;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\NovaRequest;

class ShipmentDeliverySaveDeliveryOrder extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-delivery-save-delivery-order';

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        if ($request->has('trucker')) {
            if ($request->trucker!='' && $request->trucker!=null)
               $model->trucker_id = $request->trucker;

            if ($request->trucker==null)
                $model->trucker_id = '';
        }


        if ($request->has('trucker_custom_note')) {
            if ($request->trucker_custom_note!='' && $request->trucker_custom_note!=null)
               $model->trucker_custom_note = $request->trucker_custom_note;

            if ($request->trucker_custom_note==null)
                $model->trucker_custom_note = '';
        }

        if ($request->has('copy_customer')) {
            if ($request->copy_customer!='' && $request->copy_customer!=null)
               $model->copy_customer = $request->copy_customer;

            if ($request->copy_customer==null)
                $model->copy_customer = '';
        }
    }

    public function initiate($id) {
    	$truckers = Trucker::all();
    	$newResults = [];
    	$trucker_id = null;
        $trucker_name = '';
        $trucker_custom_note = null;
        $copy_customer = null;
        $terminal = false;
        $checkShipment = "";

    	foreach ($truckers as $key => $trucker) {
            array_push($newResults, [
                'label' => $trucker->name,
                'value' => $trucker->id
            ]);
        }

        $truckers = StandardResource::collection((new Collection($newResults)));

        if ($id && !is_null($id) ){
        	$checkShipment = Shipment::findOrFail($id);

        	if( $checkShipment ) {
                $trucker_name = isset($checkShipment->trucker) ? $checkShipment->trucker->name : '';
        		$trucker_id = $checkShipment->trucker_id;
                $trucker_custom_note = $checkShipment->trucker_custom_note;
                $copy_customer = $checkShipment->copy_customer;
        	}
        }

    	return $this->withMeta([
    		'truckers' => $truckers,
    		'trucker_id' => $trucker_id,
            'copy_customer' => $copy_customer,
            'trucker_name' => $trucker_name,
            'trucker_custom_note' => $trucker_custom_note,
            'copy_customer' => $copy_customer,
            'terminal' => $checkShipment && $checkShipment->terminal_id && !is_null($checkShipment->terminal_id) && $checkShipment->terminal_id != '',
    		'id' => $id,
    		'baseUrl' => url('/')
    	]);


    }
}
