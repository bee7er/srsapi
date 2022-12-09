<?php

namespace App\Http\Controllers\Api\Renders;

use App\Http\Controllers\Controller;
use App\Render;
use App\RenderDetail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpSpec\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegistrationsController extends Controller
{
    const  AVAILABILITY_AVAILABLE = 1;
    const  AVAILABILITY_UNAVAILABLE = 2;
    // Parameters
    const ACTIONINSTRUCTION = "action_instruction";
    const AVAILABILITY = "availability";
    const CUSTOMFRAMERANGE = "custom_frame_range";
    const EMAIL = "email";
    const FROM = "from";
    const IPADDRESS = "ip_address";
    const OVERRIDESETTINGS = "override_settings";
    const RENDERDETAILID = "render_detail_id";
    const TO = "to";
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

            Log::info('In register user for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {
                // Update user with ipAddress and availability
                $user->status     = ($request->get(self::AVAILABILITY) == self::AVAILABILITY_AVAILABLE ? 'available': 'unavailable');
                $user->ip_address = $request->get(self::IPADDRESS);
                $user->save();
                Log::info('User saved OK');
            } else {
                // User can only be added by the administrator
                $message = "Could not find user with email: " . $request->get(self::EMAIL);
                Log::info('Error: ' . $message);
                $result = 'Error';
            }

            $received = [
                "email" => $request->get(self::EMAIL),
                "ipAddress" => $request->get(self::IPADDRESS),
                "availability" => $request->get(self::AVAILABILITY),
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
            $renderDetailId = 0;

            Log::info('In available user for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {

                Log::info('Got user for email: ' . $request->get(self::EMAIL));

                // TODO Here we can allocate renders to this slave if there are any

                $result = DB::table('render_details as rd')
                    ->select('rd.id','rd.status','r.id as render_id','r.status as render_status')
                    ->join('renders as r', 'r.id', '=', 'rd.render_id')
                    ->where('r.status', '!=', Render::COMPLETE)
                    ->where('rd.status', RenderDetail::READY)
                    ->first();

                if (null == $result) {
                    $message = "No renders are currently pending";
                    Log::info($message);
                    $result = 'OK';
                } else {
                    Log::info('Render details: ' . print_r($result, true));
                    // Set the render to rendering
                    $render = Render::find($result->render_id);
                    if (!$render) {
                        throw new \Exception("Could not find render record with id '{$result->render_id}'");
                    }
                    $render->status = Render::RENDERING;
                    $render->save();
                    // Allocate the render to the available slave
                    $renderDetailId = $result->id;
                    $renderDetail = RenderDetail::find($renderDetailId);
                    if (!$renderDetail) {
                        throw new \Exception("Could not find render detail record with id '{$result->id}'");
                    }
                    $renderDetail->status = RenderDetail::ALLOCATED;
                    $renderDetail->allocated_to_user_id = $user->id;
                    $renderDetail->save();

                    $actionInstruction = self::AI_DO_RENDER;
                }

                $result = 'OK';

            } else {
                // User can only be added by the administrator
                $message = "Could not find user with email: " . $request->get(self::EMAIL);
                Log::info('Error: ' . $message);
                $result = 'Error';
            }

            $received = [
                self::ACTIONINSTRUCTION => $actionInstruction,
                self::RENDERDETAILID => $renderDetailId,
                "result" => $result,
                "message" => $message,
            ];

            return $received;   // Gets converted to json

        } catch(\Exception $exception) {
            Log::info('Error: ' . $exception->getMessage());
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

            Log::info('In awake user for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {
                // This function is like a heart beat from the slaves, it signals that the slave
                // is active
                // TODO Here we check to see if any earlier requests have been rendered
                // TODO the slave can then pull down the results
                // Looking for render details records which are DONE

                $result = 'OK';

            } else {
                // User can only be added by the administrator
                $message = "Could not find user with email: " . $request->get(self::EMAIL);
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

}
