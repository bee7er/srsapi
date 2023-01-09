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
    const C4DPROJECTWITHASSETS = "c4dProjectWithAssets";
    const OUTPUTFORMAT = "outputFormat";
    const OVERRIDESETTINGS = "overrideSettings";
    const FROM = "from";
    const TO = "to";
    const CUSTOMFRAMERANGES = "customFrameRanges";
    const RENDERDETAILID = "renderDetailId";
    const RENDERID = "renderId";

    const OVERRIDE = 1;
    const USESETTINGS = 2;

    const CHUNK = 5;

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
            Log::info('In render for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL))->first();
            if ($user) {
                // Create a new Render record
                $render = new Render();
                $render->submitted_by_user_id = $user->id;
                $render->status = Render::OPEN;
                $render->c4dProjectWithAssets = $request->get(self::C4DPROJECTWITHASSETS);
                $render->outputFormat = $request->get(self::OUTPUTFORMAT);
                $render->from = $request->get(self::FROM);
                $render->to = $request->get(self::TO);
                $render->overrideSettings = $request->get(self::OVERRIDESETTINGS);
                $render->customFrameRanges = $request->get(self::CUSTOMFRAMERANGES);
                $render->save();
                // Create the detail records
                if (self::OVERRIDE == $render->overrideSettings) {
                    $this->handleCustomRenderDetails($request, $render);
                } else {
                    $this->handleRenderDetails($render->id, $render->from, $render->to);
                }
                // Ok, Render is now ready
                $render->status = Render::READY;
                $render->save();

                $result = 'OK';

            } else {
                // User can only be added by the administrator
                $message = "Could not find user with email: " . $request->get(self::EMAIL);
                Log::info('Error: ' . $message);
                $result = 'Error';
            }

            $returnData = [
                self::EMAIL => $request->get(self::EMAIL),
                self::C4DPROJECTWITHASSETS => $request->get(self::C4DPROJECTWITHASSETS),
                self::OUTPUTFORMAT => $request->get(self::OUTPUTFORMAT),
                self::OVERRIDESETTINGS => $request->get(self::OVERRIDESETTINGS),
                self::CUSTOMFRAMERANGES => $request->get(self::CUSTOMFRAMERANGES),
                self::FROM => $request->get(self::FROM),
                self::TO => $request->get(self::TO),
                self::RENDERID => $renderId,
                "message" => $message,
                "result" => $result
            ];

            return $returnData;   // Gets converted to json

        } catch(\Exception $exception) {
            Log::info('Error: ' . $exception->getMessage());
            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

    /**
     * Iterate custom frame ranges and output render details
     *
     */
    private function handleCustomRenderDetails(Request $request, Render $render)
    {
        $ranges = explode(',', $request->get(self::CUSTOMFRAMERANGES));
        foreach ($ranges as $rangelet) {
            $range = explode('-', $rangelet);
            $this->handleRenderDetails($render->id, $range[0], $range[1]);
        }
    }

    /**
     * Iterate from and to frame range and output render details
     *
     */
    private function handleRenderDetails($renderId, $from, $to)
    {
        // Chunk it up
        Log::info("Chunking render details $from - $to");
        while (($to - $from) >= 0) {
            $topOfRange = $from + self::CHUNK - 1;
            if ($topOfRange > $to) {
                $topOfRange = $to;
            }
            //Log::info("Chunk FROM - TO: $from - $topOfRange");
            $this->outputRenderDetails($renderId, $from, $topOfRange);

            $from += self::CHUNK;
        }
    }

    /**
     * Iterate from and to frame range and output render details
     *
     */
    private function outputRenderDetails($renderId, $from, $to)
    {
        $renderDetail = new RenderDetail();
        $renderDetail->render_id = $renderId;
        $renderDetail->from = $from;
        $renderDetail->to = $to;
        $renderDetail->status = RenderDetail::READY;
        $renderDetail->save();
    }

}
