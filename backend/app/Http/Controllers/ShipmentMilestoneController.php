<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ShipmentShipmentMilestone;
use App\ShipmentMilestone;

class ShipmentMilestoneController extends Controller
{

    public function create(Request $request){

        $request->validate([
            'shipment_id' => 'required',
            'notes' => 'required',
            'milestone' => 'required'
        ]);

        $query = ShipmentShipmentMilestone::where('shipment_id',$request->input('shipment_id'))->whereHas('milestone',function($q) use($request){
            $q->where('name',$request->input('milestone'));
        })->exists();

        if( $query ){
            return response()->json([ 'success' => false, 'message' => ucfirst($request->input('milestone')).' already created!' ]);
        }else{

            $milestone = new ShipmentMilestone;
            $milestone->name = $request->input('milestone');
            $milestone->instructions = $request->input('notes');
            $milestone->save();

            $ssm = new ShipmentShipmentMilestone;
            $ssm->shipment_id = $request->input('shipment_id');
            $ssm->milestone_id = $milestone->id;
            $ssm->save();
        }

        return response()->json([ 'success' => true, 'message' => ucfirst($request->input('milestone')).' Successfully created!', 'milestone' => $milestone ]);

    }
}
