<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Render;
use App\RenderDetail;
use App\Team;
use App\TeamMember;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UploadsController extends Controller
{
    const TEAMTOKEN = "teamToken";
    const EMAIL = "email";
    const USERTOKEN = "userToken";
    const C4DPROJECTNAME = "c4dProjectName";
    const RENDERID = "renderId";
    const SUBMITTEDBYUSERTOKEN = "submittedByUserToken";

    /**
     * Uploads render results from a slave user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleUploadResults(Request $request)
    {
        try {
            //Log::info('In handle upload results: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL))->first();
            if ($user) {
                $user->checkUserToken($request->get(self::USERTOKEN));

                // Check the team token, is valid and the user is an active member
                $teamToken = $request->get(self::TEAMTOKEN);
                $team = Team::where('token', $teamToken) -> first();
                if (!$team) {
                    throw new \Exception("Team not found for token '{$teamToken}'");
                }
                TeamMember::checkTeamMembership($user->id, $team->id);

                // We use the userToken of the user who submitted the job, to use their directory for the upload
                $submittedByUserToken = $request->get(self::SUBMITTEDBYUSERTOKEN);

                if ($request->file()) {
                    if (is_array($request->file())) {
                        $saveToDir = "uploads/{$submittedByUserToken}/renders/{$request->get(self::RENDERID)}";
                        if (!is_dir($saveToDir) && !mkdir($saveToDir)){
                            throw new \Exception("Error creating folder {$saveToDir}");
                        }
                        foreach ($request->file() as $file) {
                            //                        Log::info("handleUploadResults YYY File name: " . $file->getClientOriginalName());
                            //                        Log::info("handleUploadResults YYY File path: " . $file->getPathname());
                            //                        Log::info("handleUploadResults YYY params: " . print_r($request->all(), true));

                            $file->move($saveToDir, $file->getClientOriginalName());
                        }
                    } else {
                        throw new \Exception("Received file is not an array");
                    }
                } else {
                    throw new \Exception("Could not receive file");
                }
            } else {
                throw new \Exception("Upload results could not find requesting user for email '{$request->get(self::EMAIL)}'");
            }
        } catch(\Exception $exception) {

            Log::info('error: ' . $exception->getMessage());

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
            Log::info('In handle upload projects: ' . print_r($request->all(), true));

            $user = User::where('email', $request->get(self::EMAIL))->first();
            if ($user) {
                $user->checkUserToken($request->get(self::USERTOKEN));

                // Check the team token, is valid and the user is an active member
                $teamToken = $request->get(self::TEAMTOKEN);
                $team = Team::where('token', $teamToken) -> first();
                if (!$team) {
                    throw new \Exception("Team not found for token '{$teamToken}'");
                }
                TeamMember::checkTeamMembership($user->id, $team->id);

                if ($request->file()) {
                    Log::info("Trying to receive uploaded file");

                    if (is_array($request->file())) {
                        Log::info("Uploaded file seems to be there");

                        $saveToDir = "uploads/{$user->user_token}/projects/{$request->get(self::RENDERID)}";
                        if (!is_dir($saveToDir) && !mkdir($saveToDir)){
                            Log::info("Could not make directory: {$saveToDir}");

                            throw new \Exception("Error creating folder {$saveToDir}");
                        }
                        foreach ($request->file() as $file) {
                            Log::info("File name: " . $file->getClientOriginalName());
                            Log::info("File path: " . $file->getPathname());
                            Log::info("Move to: {$saveToDir}");
                            $file->move($saveToDir, $file->getClientOriginalName());
                        }
                        Log::info("Move to succeeded: {$saveToDir}");
                    } else {
                        throw new \Exception("Received file is not an array");
                    }
                } else {
                    throw new \Exception("Could not receive file");
                }
            } else {
                throw new \Exception("Upload project could not find requesting user for email '" . self::EMAIL . "', {$request->get(self::EMAIL)}'");
            }
        } catch(\Exception $exception) {
            Log::info('error: ' . $exception->getMessage());

            throw new HttpException(400, "Invalid data - {$exception->getMessage()}");
        }
    }

}
