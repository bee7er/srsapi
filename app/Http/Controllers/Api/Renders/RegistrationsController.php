<?php

namespace App\Http\Controllers\Api\Renders;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegistrationsController extends Controller
{
    /**
     * Register a slave user in the team rendering system
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $received = [
                "userName" => $request->get('userName'),
                "email" => $request->get('email'),
                "ipAddress" => $request->get('ipAddress'),
                "availability" => $request->get('availability'),
                "message" => "Registration received OK",
            ];

            // Add/Update user record
            // return a message/return code

            //$render = new User();
            //$render->fill($request->validated())->save();

            return $received;

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

}
