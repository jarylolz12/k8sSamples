<?php

namespace App\Http\Controllers\NetCHB;

use App\Events\SendShipmentToNetCHBEvent;
use App\Http\Controllers\Controller;
use App\Shipment;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\Number;

class EntryController extends Controller
{
    public function createEntry(Request $request)
    {

        $entryRequest = $request->all();
        $shipmentsId = $entryRequest["resourceId"];
        $suppliers = isset( $entryRequest["suppliers"] ) ? $entryRequest["suppliers"] : [];

        $tempSuppliers = [];
        foreach ($suppliers as $key => $supplier) {
            $fileNames = [];
            if (isset($supplier["files"])) {
                foreach ($supplier["files"] as $key => $fileObject) {
                    if ($fileObject['status'] == 'new') {
                        $copyKey = intval($key + 1);
                        $originalName = 'shipment/commercial_documents/other/' . basename($fileObject['file']->getClientOriginalName(), '.' . $fileObject['file']->guessExtension()) . '_' . $shipmentsId . '_' . $supplier["supplier_id"] . '_' . $copyKey . '_commercial_docs.' . $fileObject['file']->guessExtension();
                        Storage::disk('local')->putFileAs('/public', $fileObject['file'], $originalName);
                        array_push($fileNames, [
                            "name" => $fileObject['file']->getClientOriginalName(),
                            "file" => $originalName,
                        ]);
                    } elseif ($fileObject['status'] == 'deleted') {
                        if (Storage::exists('public/' . $fileObject['file'])) {
                            Storage::delete('public/' . $fileObject['file']);
                        }
                    } else {
                        array_push($fileNames, [
                            "file" => $fileObject['file'],
                            "name" => $fileObject['name']
                        ]);
                    }
                }
            }
            array_push($tempSuppliers, [
                "id" => $supplier["id"],
                "supplier_id" => $supplier["supplier_id"],
                "commercial_documents" => $fileNames
            ]);
        }

        $shipment = Shipment::find($shipmentsId);
        $shipment->suppliers_commercial_docs = count($tempSuppliers) > 0 ? json_encode($tempSuppliers) : null;
        $shipment->save();

        event(new SendShipmentToNetCHBEvent($shipment));
        $shipment = Shipment::find($shipmentsId);
        return response()->json($shipment, 200);
    }

    public function getEntryFromShipment(Shipment $shipment_id)
    {
        $suppliers_commercial_docs = [];
        if (isset($shipment_id->suppliers_commercial_docs)) {
            $tempSuppliers = json_decode($shipment_id->suppliers_commercial_docs);
            foreach ($tempSuppliers as $key => $tempSupplier) {
                $supplier = Supplier::find($tempSupplier->supplier_id);
                array_push($suppliers_commercial_docs, [
                    "id" => $tempSupplier->id,
                    "supplier_id" => $tempSupplier->supplier_id,
                    "company_name" => $supplier->company_name,
                    "commercial_documents" => $tempSupplier->commercial_documents,
                ]);
            }
        }

        $data = [
            "entry_netchb_no" => $shipment_id->entry_netchb_no,
            "entry_netchb_date" => $shipment_id->entry_netchb_date,
            "suppliers_commercial_docs" => $suppliers_commercial_docs,
            "entry_netchb_pdf" => $shipment_id->netchb_pdf,
            "entry_netchb_xml" => $shipment_id->netchb_xml
        ];

        return response()->json($data, 200);
    }

    public function getSupplier(Shipment $shipment_id, Supplier $id, Request $request)
    {

        $suppliersDocs = $shipment_id->suppliers_commercial_docs;
        $data = [
            "id" => $id->id,
            "company_name" => $id->company_name,
            "files" => []
        ];
        if (isset($suppliersDocs)) {
            $suppliersDocs = json_decode($suppliersDocs);
            foreach ($suppliersDocs as $value) {
                if ($value->supplier_id == $id->id && $value->id == $request->meta_id) {
                    $data["files"] = $value->commercial_documents;
                    break;
                }
            }
        }
        return response()->json($data, 200);
    }

    public function uploadDocuments(Request $request)
    {
        $entryRequest = $request->all();
        $shipmentsId = $entryRequest["resourceId"];
        $suppliers = $entryRequest["suppliers"];

        $tempSuppliers = [];
        foreach ($suppliers as $key => $supplier) {
            $fileNames = [];
            if (isset($supplier["files"])) {
                foreach ($supplier["files"] as $key => $fileObject) {
                    if ($fileObject['status'] == 'new') {
                        $copyKey = intval($key + 1);
                        $originalName = 'shipment/commercial_documents/other/' . basename($fileObject['file']->getClientOriginalName(), '.' . $fileObject['file']->guessExtension()) . '_' . $shipmentsId . '_' . $supplier["supplier_id"] . '_' . $copyKey . '_commercial_docs.' . $fileObject['file']->guessExtension();
                        Storage::disk('local')->putFileAs('/public', $fileObject['file'], $originalName);
                        array_push($fileNames, [
                            "name" => $fileObject['file']->getClientOriginalName(),
                            "file" => $originalName,
                        ]);
                    } elseif ($fileObject['status'] == 'deleted') {
                        if (Storage::exists('public/' . $fileObject['file'])) {
                            Storage::delete('public/' . $fileObject['file']);
                        }
                    } else {
                        array_push($fileNames, [
                            "file" => $fileObject['file'],
                            "name" => $fileObject['name']
                        ]);
                    }
                }
            }
            array_push($tempSuppliers, [
                "id" => $supplier["id"],
                "supplier_id" => $supplier["supplier_id"],
                "commercial_documents" => $fileNames
            ]);
        }
        $shipment = Shipment::find($shipmentsId);
        $shipment->suppliers_commercial_docs = count($tempSuppliers) > 0 ? json_encode($tempSuppliers) : null;
        $shipment->save();

        return response()->json($shipment, 200);
    }
}
