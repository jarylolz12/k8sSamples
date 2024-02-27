<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
/**
 *
 * @authenticated
 *
 * @group Shipment V2
 *
 * Class DocumentController
 * @package App\Http\Controllers\API
 *
 *
 */
class DocumentController extends Controller
{
    /**
     * Submit shipment documents
     *
     * @bodyParam shipment_id int required The shipment ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */
    public function submitShipmentDocuments(Request $request) {

        $validator = Validator::make($request->all(), [
            'shipment_id' => ['required', new CheckOwnShipment],
            'document_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(
            [
                'errors' => $validator->errors()
            ]
            ,401);
        } else {
            $args = $request->all();
            $checkShipment = Shipment::find($request->input('shipment_id'));
            if (isset($checkShipment->id)) {
                $checkShipment->send_documents = $args['document_ids'];
                event(new SendShipmentDocumentsEvent($checkShipment));
            }

            return response()->json([
                'status' => 'ok'
            ]);
        }

    }

    /**
     *
     * Update Name Type
     *
     * @bodyParam document_id int required The shipment ID
     * @bodyParam supplier_ids int[] required The supplier IDs
     * @bodyParam name string required The document NAME
     * @bodyParam type string required The document TYPE
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function updateNameType(Request $request) {

        $valid_document_types = [
            'commercial invoice',
            'packing list',
            'invoice and packing list',
            'delivery order',
            'other',
            'other commercial docs',
            'hbl'
        ];

        $validator = Validator::make($request->all(), [
            'document_id' => 'required',
            //'supplier_ids' => 'required|array',
            'supplier_ids' => 'required_if:type,commercial invoice,packing list,invoice and packing list,other commercial docs,hbl',
            'name' => 'required',
            'type' => ['required',Rule::in($valid_document_types)]
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
            $checkDocument = Document::find($args['document_id']);

            if ( isset($checkDocument->id) ) {
                $checkDocument->type = ucwords(strtolower($args['type']));
                $checkDocument->name = $args['name'];

                $supplier_ids = [];

                if ( isset($args['supplier_ids']) && $args['supplier_ids']!==null ) {
                    foreach( $args['supplier_ids'] as $supplier_id) {
                        array_push($supplier_ids, $supplier_id);
                    }
                } else {
                    $supplier_ids = [];
                }

                $checkDocument->supplier_ids = json_encode($supplier_ids);
                $checkDocument->save();

                //delete all documents for the document first
                DocumentSupplier::where('document_id', $checkDocument->id)->delete();

                $insert_documents_suppliers = [];

                if ( count($supplier_ids) > 0) {

                    foreach ( $supplier_ids as $supplier_id) {
                        $document_supplier = DocumentSupplier::where('supplier_id', intval($supplier_id))
                                        ->where('document_id', intval($checkDocument->id))
                                        ->first();

                        if ( !isset($document_supplier->id) ) {
                            array_push($insert_documents_suppliers, [
                                'document_id' => $checkDocument->id,
                                'supplier_id' => $supplier_id,
                            ]);
                        }
                    }

                    if ( count ($insert_documents_suppliers) > 0 ) {
                        \DB::table('documents_suppliers')->insert($insert_documents_suppliers);
                    }
                }

                return response()->json([
                    'status' => 'ok'
                ]);

            } else {
                return response()->json([
                    'status' => 'not ok'
                ]);
            }
        }


    }
    /**
     *
     * Delete Multiple Documents
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
     *
     * Insert Multiple Documents
     *
     *
     * @bodyParam document_infos string required
     *
     *
     *
     * @bodyParam shipment_id int required The shipment ID
     * @bodyParam shipment_id int required
     * @bodyParam document_infos.* object required
     * @bodyParam document_infos.*.name string required The documenta info's name
     * @bodyParam document_infos.*.type string required The documents info's type
     * @bodyParam document_infos.*.file file required Must be a file.
     * @bodyParam document_infos.*.supplier_id int required
     *
     * @response status=200 {
     *      "status": "ok",
     *      "result": {
     *          "name": "aperiam",
     *          "type": "commercial invoice",
     *          "shipment_id": "2",
     *          "path": "shipment/documents-customer/f80bd7b1c8e68d4e51b81bad28006230.png",
     *          "updated_at": "2022-08-04T14:10:35.000000Z",
     *          "created_at": "2022-08-04T14:10:35.000000Z",
     *          "id": 5
     *      }
     * }
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
            'hbl'
        ];

        $validationRules = [
            'shipment_id' => ['required', new CheckOwnShipment],
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
                        if (!in_array(strtolower($extension), $allowedfileExtension)) {
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
                            'is_added_by_customer' => 1,
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

    /**
     * Insert Documents
     *
     * @bodyParam shipment_id int required The shiptment ID. Example: 2
     * @bodyParam supplier_id int required The supplier ID. Example: 2
     * @bodyParam name string required The name of document.
     * @bodyParam type string required The type of document
     * should be commercial invoice, packing list, invoice and packing list,
     * invoice and packing list, delivery order and other. Example: commercial invoice
     *
     *
     * @response status=401 scenario="Error validating fields"{
     *    "errors" => ""
     * }
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     *
     */
    public function insertDocument(Request $request) {

        $valid_document_types = [
            'commercial invoice',
            'packing list',
            'invoice and packing list',
            'delivery order',
            'other'
        ];

        $validator = Validator::make($request->all(), [
            'shipment_id' => ['required', new CheckOwnShipment],
            'supplier_id' => ['required', new CheckSupplier],
            'name' => 'required',
            'type' => ['required', Rule::in($valid_document_types)],
            'file' => 'required|file'
        ]);
       //abort(500, 'error');
        if ($validator->fails()) {

            return response()->json(
            [
                'errors' => $validator->errors()
            ]
            ,401);
        }else {

            $args = $request->all();

            //create document
            $createDocument = Document::create([
                'name' => $args['name'],
                'type' => $args['type'],
                'shipment_id' => $args['shipment_id'],
                'supplier_id' => $args['supplier_id'],
                'path' => 'temp'
            ]);

            //upload now the file
            $final_display_name = '';
            $hash_file = md5($request->file('file')->getClientOriginalName() . now());
            $final_display_name = $hash_file . '.' . $request->file('file')->guessExtension();
            $final_name = 'shipment/documents-customer/'.$final_display_name;
            Storage::disk('local')->putFileAs('/public', $request->file('file'), $final_name);

            $createDocument->path = $final_name;
            $createDocument->save();

            return response()->json([
                'status' => 'ok',
                'result' => $createDocument
            ]);

        }


    }
    /**
     * Fetch Documents
     *
     * @queryParam shipment_id int required The shipment ID and use this field instead
     * @bodyParam shipment_id int This field generated to our app but it's not working. No-example
     *
     * @response status=200 {
     *  "results": {
     *      "current_page": 1,
     *          "data": [
     *              {
     *                  "id": 4,
     *                  "name": "image (18).png",
     *                  "type": "Other",
     *                  "path": "shipment/documents-customer/db6258277323d39e5a02a586e6fd205c.png",
     *                  "shipment_id": 12438,
     *                  "created_at": "2022-04-21T04:03:55.000000Z",
     *                  "updated_at": "2022-04-21T04:03:55.000000Z",
     *                  "supplier_ids": [
     *                      {
     *                          "id": 1831,
     *                          "company_name": "Test Supplier - Dream Catcher Company",
     *                          "address": "2464 Royal Ln. Mesa, New Jersey 45463",
     *                          "phone": "+1 234-567-8900",
     *                          "admin_user_id": null,
     *                          "created_at": null,
     *                          "updated_at": "2022-03-30T13:54:18.000000Z",
     *                          "emails": "[\"johndoe@gmail.com\", \"johndoe@gmail.com\", \"johndoe@gmail.com\"]",
     *                          "name": "Test Supplier - Dream Catcher Company"
     *                      }
     *               ]
     *          }
     *      ],
     *      "first_page_url": "api/v2/shipment/documents?page=1",
     *      "from": 1,
     *      "last_page": 1,
     *      "last_page_url": "api/v2/shipment/documents?page=1",
     *      "next_page_url": null,
     *      "path": "api/v2/shipment/documents",
     *      "per_page": 35,
     *      "prev_page_url": null,
     *      "to": 1,
     *      "total": 1
     *  }
     * }
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function fetchDocuments(Request $request)
    {

        $args = $request->all();
        $check_documents =  Document::where('shipment_id', $args['shipment_id'])
                                    ->groupBy('name')
                                    ->paginate(35);
        foreach($check_documents as $key => $check_document) {
            $supplier_ids = json_decode($check_document->supplier_ids);
            foreach($supplier_ids as $keySecond => $supplier_id) {
                //$supplier_ids[$keySecond] = intval($supplier_id);
                $supplier_ids[$keySecond] = Supplier::find(intval($supplier_id));
                if (isset($supplier_ids[$keySecond]->id)) {
                    $supplier_ids[$keySecond]->name = $supplier_ids[$keySecond]->company_name;
                }
            }
            $check_documents[$key]->supplier_ids = $supplier_ids;
        }

        return response()->json([
            'results' => $check_documents
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Get Shipment Document
     *
     * @qurlParam id int required The document ID
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     *
     */
    public function show($id)
    {
        $customers = Auth::user()->customersApi->pluck('id');

        $get_authenticated_user = Auth::user();
        try {
            $customer = ($get_authenticated_user->default_customer_id !== null) ? $get_authenticated_user->default_customer_id : $customers[0];
        }catch (\Exception $exception){
            return response([
                'message'=>'No results found'
            ], 404);
        }
        $check_document = Document::where('id', $id)
                                  ->whereHas('shipment', function($q) use ($customer){
                                    $q->where('customer_id', $customer);
                                  })->with('supplier')->first();


        if (isset($check_document->id)) {
            return response()->json([
                'result' => $check_document
            ]);

        } else {
            return response()->json([
                'status' => 'not ok',
            ], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {



    }

    /**
     * Update Shipment Document
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();

        try{
            $customer = ( $get_authenticated_user->default_customer_id!==null ) ? $get_authenticated_user->default_customer_id : $customers[0];
        }catch (\Exception $exception){
            return response([
                'message'=>'No results found'
            ], 404);
        }
        $check_document = Document::where('id', $id)
                                  ->whereHas('shipment', function($q) use ($customer){
                                    $q->where('customer_id', $customer);
                                  })->first();


        if (isset($check_document->id)) {
            $validator = Validator::make($request->all(), [
                'supplier_id' => ['required', new CheckSupplier],
            ]);

            if ($validator->fails()) {
                return response()->json(
                [
                    'errors' => $validator->errors()
                ]
                ,401);
            } else {
                $args = $request->all();

                if ($request->has('name')) {
                    $check_document->name = $args['name'];
                }

                if ($request->has('type')) {
                    $check_document->type = $args['type'];
                }

                if ($request->has('supplier_id')) {
                    $check_document->supplier_id = $args['supplier_id'];
                }


                if ($request->hasFile('file') && $request->file('file')->isValid()) {

                    //delete if there is an existing associated file
                    if ( $check_document->path!=='' ) {
                        if (Storage::exists('public/'.$check_document->path)) {
                            Storage::delete('public/'.$check_document->path);
                        }
                    }

                    //upload now the file
                    $final_display_name = '';
                    $hash_file = md5($request->file('file')->getClientOriginalName() . now());
                    $final_display_name = $hash_file . '.' . $request->file('file')->guessExtension();
                    $final_name = 'shipment/documents-customer/'.$final_display_name;
                    Storage::disk('local')->putFileAs('/public', $request->file('file'), $final_name);

                    $check_document->path = $final_name;

                }
                $check_document->save();

                return response()->json([
                    'status' => 'ok',
                    'result' => $check_document
                ]);



            }
        } else {
            return response()->json([
                'status' => 'not ok',
            ], 401);
        }
    }

    /**
     * Remove Shipment Document
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $customers = Auth::user()->customersApi->pluck('id');
        $get_authenticated_user = Auth::user();
        try {
            $customer = ($get_authenticated_user->default_customer_id !== null) ? $get_authenticated_user->default_customer_id : $customers[0];
        }catch (\Exception $exception){
            return response([
                'message'=>'No results found'
            ], 404);
        }

        $check_document = Document::where('id', $id)
                                  ->whereHas('shipment', function($q) use ($customer){
                                    $q->where('customer_id', $customer);
                                  })->first();


        if (isset($check_document->id)) {
            $check_document_file = $check_document->path;
            if (Storage::exists('public/'.$check_document_file)) {
                Storage::delete('public/'.$check_document_file);
            }
            $check_document->delete();

            return response()->json([
                'status' => 'ok'
            ]);
        } else {
            return response()->json([
                'status' => 'not ok'
            ]);
        }

    }
}
