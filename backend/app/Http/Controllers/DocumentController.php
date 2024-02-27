<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Document;
use App\Rules\CheckOwnShipment;
use App\Rules\CheckSupplier;
use App\Rules\CheckIfOwnDocument;
use Illuminate\Support\Facades\Storage;
use App\Events\InsertDocumentEvent;
use App\Events\SendShipmentDocumentsEvent;
use App\Shipment;
use Illuminate\Validation\Rule;
use App\Supplier;
use App\DocumentSupplier;
use App\Http\Resources\Standard as StandardResource;
/**
 *
 * @authenticated
 *
 * @group Shipment Documents
 *
 * Class DocumentController
 * @package App\Http\Controllers\API
 *
 *
 */
class DocumentController extends Controller
{

    public function fetchDocuments(Request $request) {

        $documents = [];
        $new_documents = [];
        $order_by = ($request->has('order_by')) ? $request->input('order_by') : 'id';
        $order = ($request->has('order')) ? $request->input('order') : 'desc';
        $query = ($request->has('qry')) ? $request->input('qry') : '';

        if ( $request->has('shipment_id') ) {

            $shipment_id = intval($request->input('shipment_id'));
            $documents = Document::where('shipment_id', $shipment_id)
                                 ->orderBy($order_by, $order);

            if ( $query!=='') {
                $documents = $documents->where('shipment_id', $shipment_id)
                                       ->where(function($q) use ($query){
                                            $q->where('name', 'LIKE','%'.$query.'%');
                                            $q->orWhereHas('suppliers', function($qq) use ($query){
                                                $qq->where('company_name', 'LIKE','%'.$query.'%');
                                            });
                                            $q->orWhere('type', 'LIKE','%'.$query.'%');
                                       });
            }
            $documents = $documents->paginate(30);

           // $documents = $documents->groupBy('name')->paginate(30);

            if ( count ($documents) > 0 ) {
                $suppliers = [];
                $check_document_names = [];
                $remove_document_keys = [];
                $check_document_ids = [];

                foreach ($documents as $key => $document ) {

                    if ( isset($document->supplier_ids) && $document->supplier_ids!==null && $document->supplier_ids!=='') {
                        $filterSupplierIds = json_decode($document->supplier_ids);
                        foreach ($filterSupplierIds as $filterSupplierId) {
                            if ( !in_array(intval($filterSupplierId), $suppliers)) {
                                array_push($suppliers, intval($filterSupplierId));
                            }
                        }
                    }
                    $documents[$key]->supplier_belong = $document->suppliers;

                    if ( !in_array($document->id, $check_document_ids)) {
                        array_push($check_document_ids, $document->id);
                        array_push($new_documents, $documents[$key]);
                    } else {
                        array_push($remove_document_keys, $key);
                    }
                    /*
                    if ( !in_array($document->name, $check_document_names) ) {
                        array_push($check_document_names, $document->name);
                        array_push($new_documents, $documents[$key]);
                    } else {
                        array_push($remove_document_keys, $key);
                    }*/
                }

                if ( count($remove_document_keys) > 0 ) {
                    foreach($remove_document_keys as $remove_document_key ) {
                        array_splice($documents,$remove_document_key, 1);
                    }
                }


                /*
                if ( count ($suppliers) > 0 ) {
                    $get_suppliers = Supplier::whereIn('id', $suppliers)->get();
                    if ( count ($get_suppliers) > 0) {

                    }
                }
                */

            }

        }
        return response()->json($documents);
    }


    /**
     *
     * Delete documents from resource
     *
     * @bodyParam ids int[] Delete documents multi ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */

    public function deleteDocuments(Request $request) {

        $response = ['status' => 'not ok'];

        $validator = Validator::make($request->all(), [
            'ids' => 'required|array'
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
            $document_ids = $args['ids'];

            //fetch all shipment's customer documents
            $delete_documents = Document::whereIn('id', $document_ids)
                                        ->get();
            $delete_associated_data = [];
            if (count($delete_documents) > 0) {
                foreach($delete_documents as $key => $delete_document) {
                    if ( $delete_document->path!=='') {
                        if (Storage::exists('public/'.$delete_document->path)) {
                            Storage::delete('public/'.$delete_document->path);
                        }
                    }
                    array_push($delete_associated_data, $delete_document->id);
                    $delete_document->delete();
                }
            }

            if ( count ($delete_associated_data) > 0 ) {
                DocumentSupplier::whereIn('document_id', $delete_associated_data)->delete();
            }

            return response()->json([
                'status' => 'ok'
            ]);

        }

    }

    /**
     * Insert Multiple Documents
     *
     * @bodyParam shipment_id int required The shipment ID
     *
     * @bodyParam document_infos[].name string required The documenta info's name
     * @bodyParam document_infos[].type string required The documents info's type
     * @bodyParam document_infos[].file file required Must be a file.
     * @bodyParam document_infos[].supplier_id  string[]
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */

    public function insertMultipleDocuments(Request $request) {

        $valid_document_types = [
            'commercial invoice',
            'packing list',
            'invoice and packing list',
            'delivery order',
            'other',
            'other commercial docs',
            'hbl',
            'so document'
        ];

        $validationRules = [
            'shipment_id' => ['required'],
            'document_infos' => 'required|array',
            'document_infos.*.name' => 'required',
            'document_infos.*.type' => ['required',Rule::in($valid_document_types)],
            'document_infos.*.file' => ['file','mimes:pdf,jpg,png,docx,jfif,xlsx,xlsm,xlsb,xltx,xltm,xls,xlt'],
            'document_infos.*.supplier_id' => 'required_if:document_infos.*.type,commercial invoice,packing list,invoice and packing list,other commercial docs,hbl',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors()
            ]
            ,400);
        } else {

            $allowedfileExtension= ['pdf','jpg','png','docx','jfif','xlsx','xlsm','xlsb','xltx','xltm','xls','xlt'];
            $build_documents = [];
            //s
            $fileInfo = [];
            $args = $request->all();
            if (count($args['document_infos']) > 0) {
                $pass = true;
                $file_errors = [];
                $document_infos = $args['document_infos'];
                $document_ids = [];
                foreach($document_infos as $key => $document_info) {

                    if ($request->hasFile('document_infos.'.$key.'.file')) {
                        $extension = $document_info['file']->getClientOriginalExtension();
                        $extension = strtolower($extension);
                        if (!in_array($extension, $allowedfileExtension)) {
                            $pass = false;
                        }
                    }

                    if (isset($document_info['id']))
                        array_push($document_ids, $document_info['id']);
                }

                if (!$pass) {
                    return response()->json([
                        'errors' => [
                            'document_infos' => ['Valid file extension are pdf,jpg,png,jfif, and docx']
                        ]
                    ], 400);
                } else {

                    //fetch all shipment's customer documents
                    $delete_documents = Document::where('shipment_id', $args['shipment_id'])
                                                ->whereIn('id', $document_ids)
                                                ->get();

                    if (count($delete_documents) > 0) {
                        foreach($delete_documents as $key => $delete_document) {
                            if ( $delete_document->path!=='') {
                                if (Storage::exists('public/'.$delete_document->path)) {
                                    Storage::delete('public/'.$delete_document->path);
                                }
                            }
                        }
                        $delete_documents->delete();
                    }

                    //submit now the documents
                    foreach($document_infos as $key => $document_info) {

                        $data = [
                            'name' => $document_info['name'],
                            'type' => $document_info['type'],
                            'shipment_id' => $args['shipment_id'],
                            'is_added_by_customer' => 0,
                            //'supplier_id' => $document_info['supplier_id'],
                            'path' => (isset($document_info['path'])) ? $document_info['path'] : ''
                        ];

                        if (isset($document_info['supplier_id'])) {
                            $data['supplier_ids'] = $document_info['supplier_id'];
                        }
                        if ( $request->hasFile('document_infos.'.$key.'.file')) {
                            $data['file'] = $document_info['file'];
                            $data['has_file'] = true;
                        }

                        //insert document through event
                        event(new InsertDocumentEvent($data));
                    }

                    return response()->json([
                        'status' => 'ok'
                    ]);
                }
            } else {
                return response()->json([
                    'errors' => [
                        'document_infos' => [
                            'Make sure to pass an array with valid values.'
                        ]
                    ]
                ], 400);
            }
            //e

        }

    }

    public function getSoDocuments($id)
    {
        try {
            $SoDocuments = Document::where('shipment_id', $id)->where('type', 'So Document')->get();

            return response()->json([
                "results" => $SoDocuments
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Something went wrong",
            ], 500);
        }
    }
}
