<?php

namespace App\Http\Controllers\Api\Renders;

use App\Http\Controllers\Controller;
use App\Render;
use App\RenderDetail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UploadsController extends Controller
{
    const APITOKEN = "apiToken";
    const EMAIL = "email";

    /**
     * Uploads render results from a slave user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleUploadResults(Request $request)
    {
        try {
            Log::info('In handle upload results: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL))->first();
            if ($user) {
                $user->checkApiToken($request->get(self::APITOKEN));

                // We need the apiToken of the user who submitted the job, to use their directory for the upload
                $apiToken = null;
                // OK, which render is this user processing?
                $renderDetail = RenderDetail::where('status', RenderDetail::ALLOCATED)
                    ->where('allocated_to_user_id', $user->id)->first();
                if ($renderDetail) {
                    // Get the render record
                    $render = Render::where('id', $renderDetail->render_id)->first();
                    if ($render) {
                        // Find the user that submitted it
                        $submittedByUser = User::where('id', $render->submitted_by_user_id)->first();
                        if ($submittedByUser) {
                            $apiToken = $submittedByUser->api_token;
                        }
                    }
                }
                if (!$apiToken) {
                    throw new \Exception("Could not find the submitted by user details");
                }

                if ($request->file()) {
                    if (is_array($request->file())) {
                        foreach ($request->file() as $file) {
                            //                        Log::info("handleUploadResults YYY File name: " . $file->getClientOriginalName());
                            //                        Log::info("handleUploadResults YYY File path: " . $file->getPathname());
                            //                        Log::info("handleUploadResults YYY params: " . print_r($request->all(), true));

                            $file->move("uploads/{$apiToken}/renders", $file->getClientOriginalName());
                        }
                    } else {
                        throw new \Exception("Received file is not an array");
                    }
                } else {
                    throw new \Exception("Could not receive file");
                }
            } else {
                throw new \Exception("Could not find requesting user");
            }
        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

    /**
     * Uploads project with assets from a slave user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleUploadProjects(Request $request)
    {
        try {
            Log::info('In handle upload projects: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL))->first();
            if ($user) {
                $user->checkApiToken($request->get(self::APITOKEN));

                if ($request->file()) {
                    if (is_array($request->file())) {
                        foreach ($request->file() as $file) {
//                            Log::info("File name: " . $file->getClientOriginalName());
//                            Log::info("File path: " . $file->getPathname());
//                            Log::info("Move to: uploads/{$user->api_token}/projects");
                            $file->move("uploads/{$user->api_token}/projects", $file->getClientOriginalName());
                        }
                    } else {
                        throw new \Exception("Received file is not an array");
                    }
                } else {
                    throw new \Exception("Could not receive file");
                }
            } else {
                throw new \Exception("Could not find requesting user");
            }
        } catch(\Exception $exception) {
            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

}
