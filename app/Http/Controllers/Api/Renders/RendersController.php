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
use Symfony\Component\HttpKernel\Exception\HttpException;

class RendersController extends Controller
{
    // Parameters
    const EMAIL = "email";
    const CUSTOMFRAMERANGE = "custom_frame_range";
    const OVERRIDESETTINGS = "override_settings";
    const FROM = "from";
    const TO = "to";
    const RENDERDETAILID = "render_detail_id";
    const RENDERID = "render_id";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $renders = Render::all();

        // Returning json, for a basic API
        return $renders;
    }

    /**
     * Render request from a slave user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render(Request $request)
    {
        try {
            $message = "Data received OK";
            $result = 'Error';
            $renderId = 0;

            // Write header and detail records to db
            // TODO Check if an outstanding request for the same frames is present?
            Log::info('In render for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {
                // Create a new Render record
                $render = new Render();
                $render->submitted_by_user_id = $user->id;
                $render->status = Render::OPEN;
                $render->save();
                $renderId = $render->id;
                // Create the detail records
                $renderDetail = new RenderDetail();
                $renderDetail->render_id = $render->id;
                $renderDetail->status = RenderDetail::READY;
                $renderDetail->save();
                // Ok, Render is now ready
                $render->status = Render::READY;
                $render->save();

                Log::info('Render details: ' . print_r($render, true));

                $result = 'OK';

            } else {
                // User can only be added by the administrator
                $message = "Could not find user with email: " . $request->get(self::EMAIL);
                Log::info('Error: ' . $message);
                $result = 'Error';
            }

            $received = [
                self::EMAIL => $request->get(self::EMAIL),
                self::OVERRIDESETTINGS => $request->get(self::OVERRIDESETTINGS),
                self::CUSTOMFRAMERANGE => $request->get(self::CUSTOMFRAMERANGE),
                self::FROM => $request->get(self::FROM),
                self::TO => $request->get(self::TO),
                self::RENDERID => $renderId,
                "message" => $message,
                "result" => $result
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
            Log::info('In complete for render details: ' . $request->get(self::RENDERDETAILID));
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
            Log::info('Render details: ' . print_r($result, true));

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
