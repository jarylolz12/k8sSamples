<?php

namespace Kenetashi\ShipmentTabButtonSave;

use Laravel\Nova\Fields\Field;
use App\Shipment;
use App\Trucker;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\NovaRequest;

class ShipmentTabButtonSave extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-tab-button-save';

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        if ($request->has('trucker')) {
            if ($request->trucker!='')
               $model->trucker_id = $request->trucker;
        }

        if ($request->has('trucker_custom_note')) {
            if ($request->trucker_custom_note!='')
               $model->trucker_custom_note = $request->trucker_custom_note;
        }
    }

    public function initiate($id) {
    	$truckers = Trucker::all();
    	$newResults = [];
    	$trucker_id = null;
        $trucker_name = '';
    	$trucker_custom_note = null;

    	foreach ($truckers as $key => $trucker) {
            array_push($newResults, [
                'label' => $trucker->name,
                'value' => $trucker->id
            ]);
        }

        $truckers = StandardResource::collection((new Collection($newResults)));

        if ($id!=null) {
        	$checkShipment = Shipment::find($id);
        	if(isset($checkShipment->id)) {
                $trucker_name = (isset($checkShipment->trucker)) ? $checkShipment->trucker->name : '';
        		$trucker_id = $checkShipment->trucker_id;
        		$trucker_custom_note = $checkShipment->trucker_custom_note;
        	}
        }


    	return $this->withMeta([
    		'truckers' => $truckers,
    		'trucker_id' => $trucker_id,
            'trucker_name' => $trucker_name,
    		'trucker_custom_note' => $trucker_custom_note,
    		'id' => $id,
    		'baseUrl' => url('/')
    	]);


    }
    /*
    public function truckerCustomNotes($trucker_custom_note)
    {
        return $this->withMeta(['trucker_custom_note' => $trucker_custom_note]);
    }*/
}
