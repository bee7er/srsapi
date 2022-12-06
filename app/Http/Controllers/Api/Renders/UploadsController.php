<?php

namespace App\Http\Controllers\Api\Renders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UploadsController extends Controller
{
    /**
     * Uploads request from a slave user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleUploads(Request $request)
    {
        try {
            if($request->file()) {
                if (is_array($request->file())) {
                    foreach ($request->file() as $file) {
                        //print("File name: " . $file->getClientOriginalName());
                        //print("File path: " . $file->getPathname());
                        // TODO: generate unique names for uploads
                        $file->move("uploads/renders", $file->getClientOriginalName());
                    }
                } else {
                    throw new \Exception("Received file is not an array");
                }
            } else {
                throw new \Exception("Could not receive file");
            }
        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

}
