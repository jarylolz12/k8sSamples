<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class CustomException extends \Exception implements HttpExceptionInterface
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    /* public function render($request)
    {

        return response()->json([
            'exception' => $this
        ]);

        //return parent::render($request);
    } */

    public function getStatusCode()
    {
        return 500;
    }

   /**
    * Returns response headers.
    *
    * @return array Response headers
    */
    public function getHeaders()
    {
        return [];
    }
}
