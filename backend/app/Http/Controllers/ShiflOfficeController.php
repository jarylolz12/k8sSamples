<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiflOfficeController extends Controller
{


	public function findByManager(Request $request) {

		$response = ['status' => 'not ok', 'result' =>''];

		if ($request->has('id')) {
			$id = intval($request->input('id'));

			$offices_managers = \DB::table('shifl_offices_managers')
                                   ->where('manager_id', $id)
                                   ->first();

            if (isset($offices_managers->id)) {
            	
            	$checkOffice = \App\ShiflOffice::find($offices_managers->shifl_office_id);

            	if (isset($checkOffice->id)) {

            		$response['result'] = $checkOffice;
					$response['status'] = 'ok';
            	}

            }

		}
		
		return response()->json($response);
	}

	public function findById(Request $request) {
		$response = ['status' => 'not ok', 'result' =>''];

		if ($request->has('id')) {
			$id = intval($request->input('id'));
			$office = \App\ShiflOffice::find($id);

			if (isset($office->id)) {

				$response['result'] = [[
					'label' => $office->name,
					'value' => $office->id
				]];
				$response['status'] = 'ok';
			}
		}
		
		return response()->json($response);
	}

	public function getAll(Request $request) {
		$response = ['results' => []];
		$offices = \App\ShiflOffice::all();


		if (count($offices) > 0) {
			foreach ($offices as $key => $office) {
				$offices[$key]->managers = $office->managers;
			}
		}

		$response['results'] = $offices;
		return response()->json($response);
	}

	public function search(Request $request) {

		$response = ['status' => 'not ok', 'results' => ''];
		if ($request->has('query')) {
			
			$query = trim($request->input('query'));
			$offices = \App\ShiflOffice::where('name','LIKE','%'.$query.'%')
									  ->get();

			$results = [];

			if (count($offices)>0) {
				$response['status'] = 'ok';
				foreach ($offices as $office) {
					array_push($results, [
						'label' => $office->name,
						'value' => $office->id
					]);
				}

				$response['results'] = $results;
			}
		}
		
		return response()->json($response);

	}

}