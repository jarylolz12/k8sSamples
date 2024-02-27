<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    //

    public function download(Request $request)
    {

        if ($request->has('link')) {
            $file = urldecode(strval($request->link));
            return Storage::download(
                'public/'.$file
            );
          /* $headers = [
              'Content-type'        => 'application/octet-stream',
              'Content-Disposition' => 'attachment; filename='."$file",
          ];
          return Storage::download(
              'public/'.$file,
              $file,
              $headers
          ); */
          /* return response()->download('public/'.$file, $file, $headers); */
        }
    }
}
