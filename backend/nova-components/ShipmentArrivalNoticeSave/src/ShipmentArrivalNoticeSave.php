<?php

namespace Kenetashi\ShipmentArrivalNoticeSave;

use App\CustomsBroker;
use Laravel\Nova\Fields\Field;
use App\Shipment;
use App\Terminal;
use App\Http\Resources\Standard as StandardResource;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\NovaRequest;

class ShipmentArrivalNoticeSave extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'shipment-arrival-notice-save';

    protected function fillAttributeFromRequest(
        NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute
    ) {
        if ($request->has('terminal')) {
            if ($request->terminal!='') {
                $model->terminal_id = $request->terminal;
            }
        }
    }

    public function initiate($id)
    {
        $terminals = Terminal::all();
        $newResults = [];
        $newCustomsBroker = [];
        $terminal_id = null;
        $terminal_name = '';
        $carrier_arrival_notice_eta = null;
        $carrier_arrival_notice_firms = null;
        $eta= null;
        $type= "";
        $terminal49_terminal = null;

        $customs_broker_name = '';
        $customs_broker_id = 0;

        foreach ($terminals as $key => $terminal) {
            array_push($newResults, [
                'label' => $terminal->name . " - " .  $terminal->firms_code . " - " . $terminal->address,
                'value' => $terminal->id,
                'firms_code' => $terminal->firms_code,
            ]);
        }

        $terminals = StandardResource::collection((new Collection($newResults)));

        $checkShipment = Shipment::find($id);

        if ($id!=null) {
            //$checkShipment = Shipment::find($id);
            if (isset($checkShipment->id)) {
                $terminal_name = (isset($checkShipment->terminal)) ? $checkShipment->terminal->name : '';
                $canf_terminal = (isset($checkShipment->canfTerminal)) ? $checkShipment->canfTerminal->name : '—';
                $terminal_id = $checkShipment->terminal_id;
                $carrier_arrival_notice_eta = $checkShipment->carrier_arrival_notice_eta;
                $carrier_arrival_notice_firms = $checkShipment->carrier_arrival_notice_firms;
                $customs_broker_name = (isset($checkShipment->customsBroker)) ? $checkShipment->customsBroker->name : '—';
                $customs_broker_id   = $checkShipment->customs_broker_id;
                $eta = $checkShipment->eta;
                $type = $checkShipment->getType();
                $terminal49_terminal = $checkShipment->terminal49_terminal();

                if (! isset($checkShipment->customsBroker)) {
                    if ($checkShipment->customs_broker_id === 0) {
                        $customs_broker_name = 'In House';
                    }
                    if ($checkShipment->customs_broker_id === -1) {
                        $customs_broker_name = 'None';
                    }
                }
            }
        }

        return $this->withMeta([
            'eta' => $eta,
            'carrier_arrival_notice_eta' => $carrier_arrival_notice_eta,
            'carrier_arrival_notice_firms' => $carrier_arrival_notice_firms,
            'terminals' => $terminals,
            'terminal_id' => $terminal_id,
            'terminal_name' => $terminal_name,
            'canf_terminal' => $canf_terminal??null,
            'customs_broker' => StandardResource::collection((new Collection($this->fillCustomsBrokerOption($checkShipment??null, $newCustomsBroker)))),
            'custom_broker_name' => $customs_broker_name??null,
            'customs_broker_id' => $customs_broker_id??null,
            'id' => $id,
            'type' => $type,
            'terminal49_terminal' => $terminal49_terminal,
            'baseUrl' => url('/')
        ]);
    }
    private function fillCustomsBrokerOption($checkShipment, $newCustomsBroker)
    {
        $newCustomsBroker[] = [
            'label'  => 'In House',
            'value'  => 0,
            'emails' => 'customs@shifl.com'
        ];

        if ($checkShipment) {
            $customs_broker = CustomsBroker::where('customer_id', $checkShipment->customer_id)->get();
            foreach ($customs_broker as $key => $cb) {
                $newCustomsBroker[] = [
                    'label'  => $cb->name,
                    'value'  => $cb->id,
                    'emails' => $cb->emails
                ];
            }
        }
        

        $newCustomsBroker[] = [
            'label'  => 'None',
            'value'  => -1,
            'emails' => 'none@shifl.com'
        ];

        return $newCustomsBroker;
    }
}
