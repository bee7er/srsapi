<?php

namespace App\Http\Controllers\Api\Renders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Render;

class RendersController extends Controller
{
    const OVERRIDESETTINGS = "OVERRIDESETTINGS";
    const CUSTOMFRAMERANGE = "CUSTOMFRAMERANGE";
    const FROM = "FROM";
    const TO = "TO";

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

            // Do something

            $result = 'OK';

            $received = [
                "overridesettings" => $request->get(self::OVERRIDESETTINGS),
                "customframerange" => $request->get(self::CUSTOMFRAMERANGE),
                "from" => $request->get(self::FROM),
                "to" => $request->get(self::TO),
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

            Log::info('In rendering for email: ' . $request->get('email'));
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

            Log::info('In complete for email: ' . $request->get('email'));
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







    // **************************************
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $render = Render::findOrfail($id);

        return $render; //new RenderResource($render);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$id) {
            throw new HttpException(400, "Invalid id");
        }

        try {
           $render = Render::find($id);
           $render->fill($request->validated())->save();

           return $render; //new RenderResource($render);

        } catch(\Exception $exception) {
           throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $render = Render::findOrfail($id);
        $render->delete();

        return response()->json(null, 204);
    }
}