<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ImportNames;
use Validator;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportNameController extends Controller
{
    public function getAllImportName()
    {
        try {
            $importNames = ImportNames::all() ?? [];
            
            return response()->json($importNames);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
  
           return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getImportNames(Request $request, $customer_id)
    {
       try {
       
        $per_page = $request->per_page ? intval($request->per_page) : 15;

        $importNames = ImportNames::where('customer_id',$customer_id)->paginate($per_page);

        foreach($importNames as $importName)
        {
            if($importName->image != null)
            {
                if(Str::contains($importName->image, 'public'))
                {
                    $importName->image = substr($importName->image,7);
                }
                $importName->image = getenv('APP_URL')."/storage/".$importName->image;
            }
        }
      
        return response()->json([
            "results" => $importNames
        ]);

    } catch ( \Exception $e ) {
        return response()->json(['error'=>$e->getMessage(),
                                'message' => 'Internal Server Error, Please try again later.',
                                'status' => 500], 500);
    }
    }

    public function getImportNameById($id)
    {
        
        try {
            $importName = ImportNames::find($id) ?? [];
            
            return response()->json($importName);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
  
           return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'import_name' => 'required',
                'customer_id' => 'required',
                'ein' => ['regex:/\d{2}-\d{7}[\dA-Za-z]?[\dA-Za-z]?|\d{6}-\d{5}|\d{3}-\d{2}-\d{4}/'],
                'email' => 'string',
                'phone_number' => 'string',
                'address' => 'string',
                'country' => 'string',
                'state' => 'string',
                'city' => 'string',
                'zipcode' => 'string'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 400, 'errors' => $validator->errors()]);
            }

            $path = null;
            $importNameHistory = ImportNames::find($id);

            if ($request->hasFile('image'))
            {
                $path = $request->file('image')->store('import-names/images','public');
            }  
            elseif (!$request->has("image"))
            {
               $path = $importNameHistory->image;
            }
            elseif ($request->image === null)
            {
                $path = null;
            }

          

            $importName = [
                'image' => $path,
                'import_name' => $request->import_name,
                'customer_id' => $request->customer_id,
                'ein' => $request->ein,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'zipcode' => $request->zipcode
            ];
            
            $importNameRecord = ImportNames::find($id)->update($importName);

            DB::commit();
            return response()->json([
                'message' => $importNameRecord ? 'Import Name updated successfully' : 'No import name found',
                'data' => $importNameRecord ? $importName : []
            ]);

        } catch ( \Exception $e ) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Internal Server Error',
                'status' => 500
            ], 500);

        }
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'import_name' => 'required',
                'customer_id' => 'required',
                'ein' => ['regex:/\d{2}-\d{7}[\dA-Za-z]?[\dA-Za-z]?|\d{6}-\d{5}|\d{3}-\d{2}-\d{4}/'],
                'email' => 'string',
                'phone_number' => 'string',
                'address' => 'string',
                'country' => 'string',
                'state' => 'string',
                'city' => 'string',
                'zipcode' => 'string'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 400, 'errors' => $validator->errors()]);
            }

            if ($request->hasFile('image'))
            {
                $path = $request->file('image')->store('import-names/images','public');
            }

            $importName = [
                'image' => $path,
                'import_name' => $request->import_name,
                'customer_id' => $request->customer_id,
                'ein' => $request->ein,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'zipcode' => $request->zipcode
            ];
            
            $importNameRecord = ImportNames::create($importName);

            DB::commit();
            return response()->json([
                'message' => $importName ? 'Import Name created successfully' : 'Failed to create import name',
                'data' => $importName ? $importName : []
            ]);

        } catch ( \Exception $e ) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Internal Server Error',
                'status' => 500
            ], 500);

        }
    }

   public function delete($id)
   {
     
        try {
            $importName = ImportNames::find($id);
            if(!isset($importName->id)){
                throw new Exception('Import name not found', 401);
            }

            $importName->delete();

            return response()->json([
                'status' => 'ok',
                'message' => 'Import name deleted successfully'
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Internal Server Error',
                'status' => 500
            ], 500);
        }
   }


}