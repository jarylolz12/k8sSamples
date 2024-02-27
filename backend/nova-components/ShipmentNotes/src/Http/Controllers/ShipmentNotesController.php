<?php


namespace Vishalmarakana\ShipmentNotes\Http\Controllers;

use App\ShipmentNotes;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * @authenticated
 * @group Shipment Notes
 *
 * Class ShipmentNotesController
 * @package Vishalmarakana\ShipmentNotes\Http\Controllers
 */

class ShipmentNotesController extends Controller
{
    /**
     * Get Notes
     *
     * @queryParam resourceId int required
     *
     * @response status=200 {
     *      "date_format": "DD MMM YYYY HH:mm",
     *      "notes": [
     *          {
     *              "id": 2,
     *              "subject": "shipment notes",
     *              "description": "This is it ",
     *              "uploaded_from": 1,
     *              "createdBy": "Anthony Marwyn Abadicio",
     *              "created_at": null
     *          }
     *      ]
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotes(Request $request)
    {
        $shipmentId = $request->input('resourceId');
        $notes = ShipmentNotes::orderBy('created_at', 'DESC')
            ->where('shipment_id', $shipmentId)
            ->orderBy('id', 'DESC')->get();

        $updatedNotes = collect();
        foreach ($notes as $note){
            $updatedNotes->push(
                [
                    'id' => $note->id,
                    'subject' => $note->subject,
                    'description' => $note->description,
                    'uploaded_from' => $note->uploaded_from,
                    'createdBy' => $note->users->name,
                    'created_at' => $note->created_at
                ]
            );
        }
        return response()->json([
            'date_format' => 'DD MMM YYYY HH:mm',
            'notes' => $updatedNotes,
        ], 200);
    }

    /**
     * Add Note
     *
     * @bodyParam subject string required
     * @bodyParam note string required
     * @bodyParam shipmentId int required
     * @bodyParam uploaded_from int required
     *
     * @response status=200 {
     *     "<Empty response>"
     * }
     *
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     *
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function addNote(Request $request)
    {
        $subject = $request->input('subject');
        $note = $request->input('note');
        $shipmentId = $request->input('shipmentId');
        $uploaded_from = $request->input('uploaded_from');

        ShipmentNotes::create([
            'subject' =>  $subject,
            'description' => $note,
            'shipment_id' => $shipmentId,
            'uploaded_from' => $uploaded_from
        ]);

        return response('', 204);
    }

    /**
     * Delete Notes
     *
     *
     * @response status=200 {
     *     "<Empty response>"
     * }
     *
     * @response status=400 {
     *      "errors": {
     *          "noteId": "required"
     *      }
     * }
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     * @queryParam noteId int required
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function deleteNote(Request $request)
    {
        $noteId = $request->input('noteId');

        if (empty($noteId)) return response()->json(['errors' => ['noteId' => 'required']], 400);

        $note = ShipmentNotes::where('id', $noteId)->first();
        if (empty($note)) return response('', 204);
        $note->delete();

        return response('', 204);
    }

    /**
     *
     * Update Note
     *
     * @urlParam noteId int required
     *
     *
     * @bodyParam subject string required
     * @bodyParam note string required
     * @bodyParam shipmentId int required
     * @bodyParam uploaded_from int required
     *
     * @response status=200 {
     *      "<Empty response>"
     * }
     *
     *
     * @response status=401 {
     *     "message": "Unauthenticated."
     * }
     *
     *
     *
     * @param Request $request
     * @param $noteId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function updateNote(Request $request, $noteId)
    {
        $subject = $request->input('subject');
        $description = $request->input('note');

        if (empty($noteId)) return response()->json(['errors' => ['noteId' => 'required']], 400);

        $note = ShipmentNotes::where('id', $noteId)->first();
        if (empty($note)) return response('', 204);
        $note->subject = $subject;
        $note->description = $description;
        $note->save();

        return response('', 204);
    }

}
