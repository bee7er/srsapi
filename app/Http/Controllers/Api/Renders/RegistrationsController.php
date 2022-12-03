<?php

namespace App\Http\Controllers\Api\Renders;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegistrationsController extends Controller
{
   const  AVAILABILITY_AVAILABLE = 1;

    /**
     * Register a slave user in the team rendering system
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $message = "Registration received OK";
            $result = 'OK';

            Log::info('In register user');

            $user = User::where('email', $request->get('email')) -> first();
            if ($user) {
                // Update user with ipAddress and availability
                $user->status     = ($request->get('status') == self::AVAILABILITY_AVAILABLE ? 'available': 'unavailable');
                $user->ip_address = $request->get('ipAddress');
                $user->save();
                Log::info('User saved OK');
            } else {
                // User can only be added by the administrator
                $message = "Could not find user with email: " . $request->get('email');
                Log::info('Error: ' . $message);
                $result = 'Error';
            }

            $received = [
                "email" => $request->get('email'),
                "ipAddress" => $request->get('ipAddress'),
                "availability" => $request->get('availability'),
                "result" => $result,
                "message" => $message,
            ];

            return $received;

        } catch(\Exception $exception) {
            Log::info('Exception: ' . $exception->getMessage());

            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

}
