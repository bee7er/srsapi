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
    const ACTIONINSTRUCTION = "actionInstruction";
    const AVAILABILITY = "availability";
    const C4DPROJECTWITHASSETS = "c4dProjectWithAssets";
    const CUSTOMFRAMERANGE = "customFrameRange";
    const EMAIL = "email";
    const FROM = "from";
    const IPADDRESS = "ipAddress";
    const OUTPUTFORMAT = "outputFormat";
    const OVERRIDESETTINGS = "overrideSettings";
    const RENDERDETAILID = "renderDetailId";
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
            $c4dProjectWithAssets = '';
            $from = 0;
            $to = 0;
            $outputFormat = '';

            Log::info('In available user for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {

                Log::info('Got user for email: ' . $request->get(self::EMAIL));

                // TODO Here we can allocate renders to this slave if there are any

                $result = DB::table('render_details as rd')
                    ->select(
                        'rd.id','rd.status','rd.from','rd.to',
                        'r.id as render_id','r.status as render_status','r.c4dProjectWithAssets','r.outputFormat'
                    )
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

                    $c4dProjectWithAssets = $result->c4dProjectWithAssets;
                    $from = $result->from;
                    $to = $result->to;
                    $outputFormat = $result->outputFormat;

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
                self::C4DPROJECTWITHASSETS => $c4dProjectWithAssets,
                self::FROM => $from,
                self::TO => $to,
                self::OUTPUTFORMAT => $outputFormat,
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

    /**
     * Status request from a slave user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        try {
            $message = "Status request received OK";
            $result = 'Error';

            Log::info('In status request for email: ' . $request->get(self::EMAIL));

            // Do something

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
     * Rendering notification from a slave user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function rendering(Request $request)
    {
        try {
            $message = "Rendering notification received OK";
            $result = 'Error';

            Log::info('In rendering for email: ' . $request->get(self::EMAIL));
            // Do something

            $result = 'OK';

            $received = [
                "message" => $message,
                "result" => $result
            ];

            return $received;   // Gets converted to json

        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

    /**
     * Complete notification from a slave user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request)
    {
        try {
            $message = "Complete notification received OK";
            $result = 'Error';

            Log::info('In complete for email: ' . $request->get(self::EMAIL));
            Log::info('In complete for render details id: ' . $request->get(self::RENDERDETAILID));
            // Update the detail record to DONE
            $renderDetailId = $request->get(self::RENDERDETAILID);
            $renderDetail = RenderDetail::find($renderDetailId);
            if (!$renderDetail) {
                throw new \Exception("Could not find render detail record with id '{$renderDetailId}'");
            }
            $renderDetail->status = RenderDetail::DONE;
            $renderDetail->save();
            // If all details are DONE then the render is COMPLETE
            $result = DB::table('render_details as rd')
                ->select('rd.id','rd.status')
                ->where('rd.render_id', $renderDetail->render_id)
                ->where('rd.status', '!=', RenderDetail::DONE)
                ->get();
            if (0 >= count($result)) {
                $render = Render::find($renderDetail->render_id);
                if (!$render) {
                    throw new \Exception("Could not find render record with id '{$renderDetail->render_id}'");
                }
                $render->status = Render::COMPLETE;
                $render->completed_at = date('Y-m-d H:i:s');
                $render->save();
                Log::info('Render is COMPLETE with id: ' . $renderDetail->render_id);
            }
            $result = 'OK';

            $received = [
                "message" => $message,
                "result" => $result
            ];

            return $received;   // Gets converted to json

        } catch(\Exception $exception) {
            Log::info('Error: ' . $exception->getMessage());
            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

}
