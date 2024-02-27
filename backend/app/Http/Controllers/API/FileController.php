<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Shipment;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Nova;

class FileController extends Controller
{
    public function download($resource_name, $resource_id, $field)
    {
        return Storage::download('public/'.\DB::table($resource_name)->where('id', '=', $resource_id)->first()->{$field});
    }

    public function downloadCustom($resource_name, $resource_id, $field)
    {
        $item = \DB::table($resource_name)->where('id', '=', $resource_id)->first();
        $checkFile = '';
        if (isset($item->id)) {
            $checkFile = $item->{$field};
            if ($checkFile!=='') {
                if ( $field === 'ssfour_doc' || $field === 'wnine_doc' ) {
                    return Storage::download(
                        'public/shipment/customer_additions/'.$item->{$field}
                    );
                }
            }
        }
        return null;
    }

    public function delete($resource_name, $resource_id, $attribute)
    {
        $model = \DB::table($resource_name)->where('id', '=', $resource_id)->first();

        if (is_null($model) || is_null($model->{$attribute} ?? null)) {
            return response()->json(['error'=> 'File not found'], 404);
        }

        if ( $attribute === 'ssfour_doc' || $attribute === 'wnine_doc' || $attribute === 'mbl_copy_surrendered') {
            if ( $attribute === 'ssfour_doc' || $attribute === 'wnine_doc' ) {
                if (Storage::exists('public/shipment/customer_additions/'.$model->{$attribute})) {
                    Storage::delete('public/shipment/customer_additions/'.$model->{$attribute});
                }
            } else {
                if (Storage::exists('public/shipment/mbl_copy_surrendered/'.$model->{$attribute})) {
                    Storage::delete('public/shipment/mbl_copy_surrendered/'.$model->{$attribute});
                }
            }
        } else {
            if (Storage::exists('public/'.$model->{$attribute})) {
                Storage::delete('public/'.$model->{$attribute});
            }
        }

        \DB::table($resource_name)->where('id', '=', $resource_id)->update([$attribute => null , 'updated_at' => $model->updated_at]);

        return response()->json(['success' => 'File Has been deleted'], 200);
    }

    /*
    $request will contain
    1. shipment id
    3. file name // hbl_copy , commercial_invoice etc.
    3. key
    */


    public function deleteShipmentSupplierSectionFile(Request $request)
    {
        $request->validate([
            'shipment_id' => 'required',
            'file' => 'required',
            'key' => 'required',
        ]);


        try {
            $shipment = Shipment::findOrFail($request->shipment_id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'internal server error'], 500);
        }


        $shipment_suppliers = json_decode($shipment->suppliers_group);

        if (Storage::exists('public/'.$shipment_suppliers[$request->key]->{$request->file})) {
            Storage::delete('public/'    .$shipment_suppliers[$request->key]->{$request->file});
        }
        //
        $shipment_suppliers[$request->key]->{$request->file} = null;

        $shipment->suppliers_group = json_encode($shipment_suppliers);

        // $shipment->update([
        //     'suppliers_group' => $shipment->suppliers_group,
        //     'updated_at' => $shipment->updated_at
        // ]);

        // \Debugbar::info($shipment->updated_at);

        // $shipment->save(['timestamps' => false]);
        //

        \DB::table('shipments')->where('id', '=', $shipment->id)->update(['suppliers_group' => $shipment->suppliers_group , 'updated_at' => $shipment->updated_at]);

        // \Debugbar::info(Shipment::findOrFail($shipment->id)->updated_at);
        //
        // $last_action_event = Nova::actionEvent()->forResourceUpdate(
        //     $request->user(),
        //     $shipment
        // );
        // $last_action_event->save();

        // $last_action_event = \DB::table('action_events')->last();
        // \Debugbar::info($last_action_event->delete());
        // DB::table('shipments')->where('id', '=', $shipment->id)->update(['shifl_ref' => (700000+$shipment->id)]);



        return response()->json(['success' => 'success'], 200);
    }
}
