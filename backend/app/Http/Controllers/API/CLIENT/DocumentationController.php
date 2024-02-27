<?php

namespace App\Http\Controllers\API\CLIENT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    //
    public function index (Request $request){

        if($request->version=='v1'){
            $validator = \Validator::make($request->all(), [
                'version' => 'required|in:v1'
            ]);

            $version = $validator->fails() ? 'v1' : $request->version;

            return view("api.documentation.$version.index")->with([
                'version' => $version
            ]);
        }else if($request->version=='v2') {
            $validator = \Validator::make($request->all(), [
                'version' => 'required|in:v2'
            ]);
            $version = $validator->fails() ? 'v2' : $request->version;

            return view("api.documentation.$version.home")->with([
                'version' => $version
            ]);
        }else if($request->version=='v3') {
            $validator=\Validator::make($request->all(), [
                'version'=>'required|in:v3'
            ]);

            $version = $validator->fails() ? 'v3' : $request->version;


            return view("api.documentation.$version.shipment-integration")->with([
                'version'=>$version
            ]);
        } else if($request->version=='v4') {
            $validator=\Validator::make($request->all(), [
                'version'=>'required|in:v4'
            ]);

            $version = $validator->fails() ? 'v4' : $request->version;


            return view("api.documentation.$version.docs")->with([
                'version'=>$version
            ]);
        }

    }





    public function stepsDocs(Request $request){
        $validator = \Validator::make($request->all(), [
            'version' => 'required|in:v2'
        ]);
        $version = $validator->fails() ? 'v2' : $request->version;

        return view("api.documentation.$version.shipment-by-po")->with([
            'version' => $version
        ]);
    }


    public function genToken(Request $request){
        $validator=\Validator::make($request->all(), [
            'version'=>'required|in:v2'
        ]);

       $version = $validator->fails() ? 'v2' : $request->version;

        return view("api.documentation.$version.internal-api")->with([
            'version'=>$version
        ]);
    }


//
//
//    public function index2 (Request $request){
//
//        if($request->version=='v1') {
//            $validator = \Validator::make($request->all(), [
//                'version' => 'required|in:v2'
//            ]);
//            $version = $validator->fails() ? 'v2' : $request->version;
//
//            return view("api.documentation.$version.home")->with([
//                'version' => $version
//            ]);
//        }
//    }
//    public function shipmentIntegration(Request $request){
//
//
//
//        if($request->version=='v3'){
//
//            $validator=\Validator::make($request->all(), [
//                'version'=>'required|in:v3'
//            ]);
//
//            $version = $validator->fails() ? 'v3' : $request->version;
//
//
//            return view("api.documentation.$version.shipment-integration")->with([
//                'version'=>$version
//            ]);
//        }
//    }


}
