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
   # Action instruction
   const  AI_DO_RENDER = 'render';

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

            Log::info('In register user for email: ' . $request->get('email'));

            $user = User::where('email', $request->get('email')) -> first();
            if ($user) {
                // Update user with ipAddress and availability
                $user->status     = ($request->get('availability') == self::AVAILABILITY_AVAILABLE ? 'available': 'unavailable');
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

    /**
     * Awake notification from a slave user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function awake(Request $request)
    {
        try {
            $message = "Awake notification received OK";
            $result = 'Error';

            Log::info('In awake user for email: ' . $request->get('email'));

            $user = User::where('email', $request->get('email')) -> first();
            if ($user) {
                // This function is like a heart beat from the slaves, it signals that the slave
                // is active but not available
                // Not sure if we need to do anything here atm
                $result = 'OK';

            } else {
                // User can only be added by the administrator
                $message = "Could not find user with email: " . $request->get('email');
                Log::info('Error: ' . $message);
                $result = 'Error';
            }

            $received = [
                "result" => $result,
                "message" => $message,
            ];

            return $received;   // Gets converted to json

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

    /**
     * Available notification from a slave user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request)
    {
        try {
            $message = "Available notification received OK";
            $result = null;
            $actionInstruction = '';


            Log::info('In available user for email: ' . $request->get('email'));

            $user = User::where('email', $request->get('email')) -> first();
            if ($user) {
                // This function is like a heart beat from the slaves
                // TODO Here we can allocate renders to this slave if there are any

                //$actionInstruction = self::AI_DO_RENDER;
                $result = 'OK';

            } else {
                // User can only be added by the administrator
                $message = "Could not find user with email: " . $request->get('email');
                Log::info('Error: ' . $message);
                $result = 'Error';
            }

            $received = [
                "actionInstruction" => $actionInstruction,
                "result" => $result,
                "message" => $message,
            ];

            return $received;   // Gets converted to json

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

}
