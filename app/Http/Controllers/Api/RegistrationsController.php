<?php

namespace App\Http\Controllers\Api;

use App\BlockedUser;
use App\Http\Controllers\Controller;
use App\Render;
use App\RenderDetail;
use App\Team;
use App\TeamMember;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class RegistrationsController extends Controller
{
    const  AVAILABILITY_AVAILABLE = 1;
    const  AVAILABILITY_UNAVAILABLE = 2;
    // Parameters
    const ACTIONINSTRUCTION = "actionInstruction";
    const ALLOCATEDTOUSERS = "allocatedToUsers";
    const USERTOKEN = "userToken";
    const AVAILABILITY = "availability";
    const C4DPROJECTWITHASSETS = "c4dProjectWithAssets";
    const C4DPROJECTNAME = "c4dProjectName";
    const CUSTOMFRAMERANGE = "customFrameRange";
    const EMAIL = "email";
    const FILENAME = "fileName";
    const FRAMEDETAILS = "frameDetails";
    const FROM = "from";
    const OUTPUTFORMAT = "outputFormat";
    const OVERRIDESETTINGS = "overrideSettings";
    const RENDERDETAILID = "renderDetailId";
    const RENDERID = "renderId";
    const SUBMISSIONSANDRENDERS = "submissionsAndRenders";
    const SUBMITTEDBYUSERID = "submittedByUserId";
    const SUBMITTEDBYUSERTOKEN = "submittedByUserToken";
    const TEAMTOKEN = "teamToken";
    const TO = "to";

    # Action instruction
    const  AI_DO_RENDER = 'render';
    const  AI_DO_DOWNLOAD = 'download';
    const  AI_DO_DISPLAY_OUTSTANDING = 'outstanding';

    /**
     * Create a new user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function newUser(Request $request)
    {
        $message = "New user created OK";
        $result = 'OK';
        $newUserToken = '';
        $newUserName = '';
        $newUserEmail = '';
        try {
            Log::info('In new user');

            $user = new User();
            $user->status     = User::AVAILABLE;
            $user->role       = User::USER;
            $user->userName   = User::DEFAULTUSERNAME;
            $user->user_token = $user->getNewToken();
            // This generated email s guaranteed to be unique.  User can change it later.
            $user->email      = ($user->user_token . '@' . User::DOMAIN);
            $user->password   = Hash::make(Input::get('password'));
            $user->save();

            $this->checkUserDirectories($user->user_token);

            $newUserToken = $user->user_token;
            $newUserName = $user->userName;
            $newUserEmail = $user->email;

        } catch(\Exception $exception)
        {
            $result = 'Error';
            $message = "Error creating new user: " . $exception->getMessage();
            Log::info('Exception: ' . $message);
        }

        $returnData = [
            "userToken" => $newUserToken,
            "userName" => $newUserName,
            "email" => $newUserEmail,
            "result" => $result,
            "message" => $message,
        ];

        return $returnData;
    }
    
    /**
     * Register a slave user in the team rendering system
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $message = "Registration received OK";
        $result = 'OK';
        try {
            //Log::info('In register user for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {
                $user->checkUserToken($request->get(self::USERTOKEN));

                // Check the team token, is valid and the user is an active member
                $teamToken = $request->get(self::TEAMTOKEN);
                $team = Team::where('token', $teamToken) -> first();
                if (!$team) {
                    throw new \Exception("Team not found for token '{$teamToken}'");
                }
                TeamMember::checkTeamMembership($user->id, $team->id);

                $this->checkUserDirectories($request->get(self::USERTOKEN));

                // Update user with availability
                $user->status = ($request->get(self::AVAILABILITY) == self::AVAILABILITY_AVAILABLE ? 'available': 'unavailable');
                // Treat this as a data changed event so that the user gets the latest data
                $user->data_changed = true;
                $user->save();

                Log::info('User saved OK');
            } else {
                // User can only be added by the administrator
                $message = "Could not find user with email: " . $request->get(self::EMAIL);
                Log::info('Error: ' . $message);
                $result = 'Error';
            }

        } catch(\Exception $exception) {
            $result = 'Error';
            $message = $exception->getMessage();
            Log::info('Exception: ' . $message);
        }

        $returnData = [
            "email" => $request->get(self::EMAIL),
            "availability" => $request->get(self::AVAILABILITY),
            "result" => $result,
            "message" => $message,
        ];

        return $returnData;
    }

    /**
     * Checks that directories needed later exist
     *
     * @param $userToken
     */
    private function checkUserDirectories($userToken)
    {
        // Ensure there is a directory here to contain uploaded projects and renders for this user
        $directory = "uploads/{$userToken}/projects";
        if (!file_exists($directory)) {
            Log::info('In register making directory: ' . $directory);
            mkdir($directory, 0755, true);
        }
        $directory = "uploads/{$userToken}/renders";
        if (!file_exists($directory)) {
            Log::info('In register making directory: ' . $directory);
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Available notification from a slave user.
     *
     * Test:  http://srsapi.test/api1/available?email=contact_bee@yahoo.com&teamToken=jfhdyetryetA&userToken=fl9ltqesXqPi4EkS
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request)
    {
        $message = "Available notification received OK";
        $result = null;
        $actionInstruction = '';
        $renderDetailId = 0;
        $c4dProjectWithAssets = '';
        $c4dProjectName = '';
        $from = 0;
        $to = 0;
        $outputFormat = '';
        $submittedByUserId = '';
        $submittedByUserToken = '';
        $renderId = '';
        try {

            Log::info('In available user for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {
                $user->checkUserToken($request->get(self::USERTOKEN));

                // Check the team token, is valid and the user is an active member
                $teamToken = $request->get(self::TEAMTOKEN);
                $team = Team::where('token', $teamToken) -> first();
                if (!$team) {
                    throw new \Exception("Team not found for token '{$teamToken}'");
                }
                TeamMember::checkTeamMembership($user->id, $team->id);

                $results = DB::table('render_details as rd')
                    ->select(
                        'rd.id', 'rd.status','rd.from','rd.to',
                        'r.id as render_id','r.status as render_status','r.submitted_by_user_id','r.c4dProjectWithAssets','r.c4dProjectName','r.outputFormat',
                        'u.id as submittedByUserId','u.user_token as submittedByUserToken'
                    )
                    ->join('renders as r', 'r.id', '=', 'rd.render_id')
                    ->join('users as u', 'u.id', '=', 'r.submitted_by_user_id')
                    ->where('r.status', '!=', Render::COMPLETE)
                    ->where('rd.status', RenderDetail::READY)
                    ->orderBy('render_id', 'ASC')
                    ->orderBy('rd.id', 'ASC')
                    ->get();

                if (null == $results) {
                    $message = "No renders are currently pending";
                    //Log::info($message);
                    $result = 'OK';
                } else {
                    foreach ($results as $result) {
                        // If the available user is blocked, then check subsequent render details
                        if (BlockedUser::where(['userId'=>$result->submitted_by_user_id,'teamId'=>$team->id, 'blockedUserId'=>$user->id])->exists()) {
                            // Available user is blocked by the user who submitted the render request
                            Log::info('Available user for email: ' . $request->get(self::EMAIL) . ' is blocked for submitting user id ' . $result->submitted_by_user_id);
                            continue;
                        }

                        // OK, set the render to rendering
                        $render = Render::find($result->render_id);
                        if (!$render) {
                            throw new \Exception("Could not find render record with id '{$result->render_id}'");
                        }
                        $render->status = Render::RENDERING;
                        $render->save();

                        $renderId = $result->render_id;
                        // Allocate the render to the available slave
                        $renderDetailId = $result->id;
                        $renderDetail = RenderDetail::find($renderDetailId);
                        if (!$renderDetail) {
                            throw new \Exception("Could not find render detail record with id '{$result->id}'");
                        }
                        $renderDetail->status = RenderDetail::ALLOCATED;
                        $renderDetail->allocated_to_user_id = $user->id;
                        $renderDetail->save();

                        // User's data has changed for this render
                        Render::dataHasChanged($render->id, $user->id);

                        $c4dProjectWithAssets = $result->c4dProjectWithAssets;
                        $c4dProjectName = $result->c4dProjectName;
                        $from = $result->from;
                        $to = $result->to;
                        $outputFormat = $result->outputFormat;
                        $submittedByUserId = $result->submittedByUserId;
                        $submittedByUserToken = $result->submittedByUserToken;

                        $actionInstruction = self::AI_DO_RENDER;

                        //Log::info('Instructing user for email: ' . $request->get(self::EMAIL) . ' do render detail id ' . $renderDetailId);

                        break;
                    }

                    if (self::AI_DO_RENDER != $actionInstruction) {
                        $message = "User is blocked on pending renders";
                    }
                }

                $result = 'OK';

            } else {
                // User can only be added by the administrator
                $message = "Could not find user with email: " . $request->get(self::EMAIL);
                Log::info('Error: ' . $message);
                $result = 'Error';
            }

        } catch(\Exception $exception) {
            $result = 'Error';
            $message = $exception->getMessage();
            Log::info('Error: ' . $message);
        }

        Log::info('Result message: ' . $message);

        $returnData = [
            self::ACTIONINSTRUCTION => $actionInstruction,
            self::RENDERDETAILID => $renderDetailId,
            self::C4DPROJECTWITHASSETS => $c4dProjectWithAssets,
            self::C4DPROJECTNAME => $c4dProjectName,
            self::FROM => $from,
            self::TO => $to,
            self::OUTPUTFORMAT => $outputFormat,
            self::SUBMITTEDBYUSERID => $submittedByUserId,
            self::SUBMITTEDBYUSERTOKEN => $submittedByUserToken,
            self::RENDERID => $renderId,
            "result" => $result,
            "message" => $message,
        ];

        return $returnData;   // Gets converted to json
    }

    /**
     * Awake notification from a slave user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function awake(Request $request)
    {
        $message = "Awake notification received OK";
        $result = 'Error';
        $actionInstruction = '';
        $c4dProjectWithAssets = '';
        $submissionsAndRenders = '';
        $frameDetails = [];
        $allocatedToUsers = [];
        try {

            //Log::info('In awake user for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {
                $user->checkUserToken($request->get(self::USERTOKEN));

                // Check the team token, is valid and the user is an active member
                $teamToken = $request->get(self::TEAMTOKEN);
                $team = Team::where('token', $teamToken) -> first();
                if (!$team) {
                    throw new \Exception("Team not found for token '{$teamToken}'");
                }
                TeamMember::checkTeamMembership($user->id, $team->id);

                // Heart beat from the slaves, it signals that the slave is active
                //Log::info('Got user for email: ' . $request->get(self::EMAIL));

                // Here we check to see if any earlier requests from this user have been rendered;
                // the slave user can then pull down the results
                $results = RenderDetail::getCompletedRenderDetails($user->id);

                if (null == $results) {
                    $message = "No renders are currently available for download";
                    //Log::info($message);
                } else {
                    if ($results && 0 < count($results)) {

                        foreach ($results as $result) {
                            // Render detail now set to returned
                            $renderDetail = RenderDetail::find($result->id);
                            if (!$renderDetail) {
                                throw new \Exception("Could not find render detail record with id '{$result->id}'");
                            }
                            $renderDetail->status = RenderDetail::RETURNED;
                            $renderDetail->save();

                            $c4dProjectWithAssets = $result->c4dProjectWithAssets;
                            // NB We accumulate frame details for each render id we encounter
                            $renderId = $renderDetail->render_id;
                            if (!isset($frameDetails[$renderId])) {
                                $frameDetails[$renderId] = [];
                            }

                            $dir = "uploads/{$result->submittedByUserToken}/renders/{$renderId}";
                            if (!is_dir($dir)) {
                                throw new \Exception("Required directory '$dir' does not exist");
                            }

                            // Get all the images from the renders directory, so we can find out the actual name generated
                            $images = array_diff(
                                scandir(
                                    $dir,
                                    SCANDIR_SORT_DESCENDING),
                                    array('.', '..','.DS_Store')
                            );
                            // Add an entry for each render that has occurred in the range from and to
                            for ($i=$renderDetail->from; $i<=$renderDetail->to; $i++) {
                                // NB we have to find the actual name in the directory using the frame number, which is reliable.
                                $fileName = $this->getArrayValue($images, sprintf("%04d", $i) . "." . $result->outputFormat);
                                if (null !== $fileName) {
                                    $frameDetails[$renderId][] = $fileName;
                                }
                            }

                            // User's data has changed for this render, and the original user, too
                            Render::dataHasChanged($renderId, $renderDetail->allocated_to_user_id);
                        }

                        if ($frameDetails && 0 < count($frameDetails)) {
                            // NB There could be more than one render completed
                            foreach(array_keys($frameDetails) as $renderId) {
                                // Render now set to returned and it is fully processed
                                $render = Render::find($renderId);
                                if (!$render) {
                                    throw new \Exception("Could not find render record with id '{$renderId}'");
                                }
                                $render->status = Render::RETURNED;
                                $render->save();
                            }
                        }
                    }

                    $actionInstruction = self::AI_DO_DOWNLOAD;
                }

                if (self::AI_DO_DOWNLOAD != $actionInstruction) {
                    // We are not instructing the slave to do downloads, so if data has changed for this
                    // user then check for outstanding renders
                    if ($user->data_changed) {
                        // Get both submissions and renders as a displayable string
                        $submissionsAndRenders = RenderDetail::getSubmissionsAndRendersAsHTML($user->id, $team->id);

                        $actionInstruction = self::AI_DO_DISPLAY_OUTSTANDING;

                        // Only display the user's data once, until the next time it has changed
                        $user->data_changed = false;
                        $user->save();
                    }
                }

                $result = 'OK';

            } else {
                // User can only be added by the administrator
                $message = "Could not find user with email: " . $request->get(self::EMAIL);
                Log::info('Error: ' . $message);
                $result = 'Error';
            }

        } catch(\Exception $exception) {
            $result = 'Error';
            $message = $exception->getMessage() . ' on line ' . $exception->getLine();
            Log::info('Error exception: ' . $message);
        }

        $returnData = [
            self::ACTIONINSTRUCTION => $actionInstruction,
            self::C4DPROJECTWITHASSETS => $c4dProjectWithAssets,
            self::FRAMEDETAILS => $frameDetails,
            self::ALLOCATEDTOUSERS => $allocatedToUsers,
            self::SUBMISSIONSANDRENDERS => $submissionsAndRenders,
            "result" => $result,
            "message" => $message,
        ];

        return $returnData;   // Gets converted to json
    }

    /**
     * Search array for string and return the array value
     *
     */
    private function getArrayValue(array $stringAry, $searchStr)
    {
        foreach ($stringAry as $elem) {
            if (false !== strpos($elem, $searchStr)) {
                return $elem;
            }
        }

        return null;
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
            $message = "Status request received OK\n";
            $result = 'Error';
            //Log::info('In status request for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {
                $user->checkUserToken($request->get(self::USERTOKEN));

                // Check the team token, is valid and the user is an active member
                $teamToken = $request->get(self::TEAMTOKEN);
                $team = Team::where('token', $teamToken) -> first();
                if (!$team) {
                    throw new \Exception("Team not found for token '{$teamToken}'");
                }
                TeamMember::checkTeamMembership($user->id, $team->id);

                $renderResults = DB::table('render_details as rd')
                    ->select(
                        'rd.id','rd.status as detail_status','rd.allocated_to_user_id','rd.from','rd.to',
                        'r.id as render_id','r.status as render_status','r.c4dProjectWithAssets','r.outputFormat'
                    )
                    ->join('renders as r', 'r.id', '=', 'rd.render_id')
                    ->where('r.submitted_by_user_id', '=', $user->id)
                    ->where('r.status', '!=', Render::RETURNED)
                    ->orderBy('render_id', 'ASC')
                    ->orderBy('rd.id', 'ASC')
                    ->get();

                if (null == $renderResults) {
                    $message = "No renders are currently outstanding";
                    //Log::info($message);
                } else {
                    //Log::info('Render details available for downloads: ' . print_r($renderResults, true));
                    if ($renderResults) {
                        $sep = "";
                        foreach ($renderResults as $render) {
                            $allocatedUserEmail = 'Not yet allocated';
                            if (0 < $render->allocated_to_user_id) {
                                $allocatedUser = User::where('id', $render->allocated_to_user_id)->first();
                                if ($allocatedUser) {
                                    $allocatedUserEmail = "with {$allocatedUser->email}";
                                } else {
                                    $allocatedUserEmail = "Allocated to unknown with id {$render->allocated_to_user_id}";
                                }
                            }
                            $message .= (
                                $sep .
                                "{$render->c4dProjectWithAssets} {$render->render_status} {$render->from} - {$render->to} {$render->detail_status} {$allocatedUserEmail}"
                            );
                            $sep = "\n";
                        }
                    }
                }

                $result = 'OK';

            } else {
                $message = "Could not find user with email: " . $request->get(self::EMAIL);
                Log::info('Error: ' . $message);
                $result = 'Error';
            }

        } catch(\Exception $exception) {
            $result = 'Error';
            $message = $exception->getMessage();
            Log::info('Error: ' . $message);
        }

        $returnData = [
            "result" => $result,
            "message" => $message,
        ];

        return $returnData;   // Gets converted to json
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

            //Log::info('In rendering for email: ' . $request->get(self::EMAIL));
            // Do something

            $result = 'OK';

        } catch(\Exception $exception) {
            $result = 'Error';
            $message = $exception->getMessage();
            Log::info('Error: ' . $message);
        }

        $returnData = [
            "message" => $message,
            "result" => $result
        ];

        return $returnData;   // Gets converted to json
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

            Log::info('In complete for email: ' . $request->get(self::EMAIL) . ' and render details id: ' . $request->get(self::RENDERDETAILID));
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
                    ->select('rd.id', 'rd.status')
                    ->where('rd.render_id', $renderDetail->render_id)
                    ->where('rd.status', '!=', RenderDetail::DONE)
                    ->get();
                if (0 >= count($result)) {
                    // All render detail chunks are DONE, set the render to COMPLETE and do some housekeeping
                    $render = Render::find($renderDetail->render_id);
                    if (!$render) {
                        throw new \Exception("Could not find render record with id '{$renderDetail->render_id}'");
                    }
                    $render->status = Render::COMPLETE;
                    $render->completed_at = date('Y-m-d H:i:s');
                    $render->save();
                    //Log::info('Render is COMPLETE with id: ' . $renderDetail->render_id);
                }

                // User's data has changed for this render
                Render::dataHasChanged($renderDetail->render_id, $renderDetail->allocated_to_user_id);
            }

            $result = 'OK';

        } catch(\Exception $exception) {
            $result = 'Error';
            $message = $exception->getMessage();
            Log::info('Error: ' . $message);
        }

        $returnData = [
            "message" => $message,
            "result" => $result
        ];

        return $returnData;   // Gets converted to json
    }

    /**
     * Image downloaded notification from a slave user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function downloaded(Request $request)
    {
        $result = null;
        try {
            $message = "Downloaded notification received OK";

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

                $fileNameFullPath = "uploads/{$user->user_token}/renders/{$request->get(self::RENDERID)}/{$request->get(self::FILENAME)}";
                if (unlink($fileNameFullPath)) {
                    //Log::info("File: {$fileNameFullPath} successfully deleted");
                } else {
                    throw new \Exception("Could not delete file '{$fileNameFullPath}'");
                }
            }

            $result = 'OK';

        } catch(\Exception $exception) {
            $result = 'Error';
            $message = $exception->getMessage();
            Log::info('Error: ' . $message);
        }

        $returnData = [
            "message" => $message,
            "result" => $result
        ];

        return $returnData;   // Gets converted to json
    }

    /**
     * Get the details of a render request and do the completion housekeeping
     */
    public function completionHousekeeping($renderId)
    {
        try {
            //Log::info("Housekeeping for render id: {$renderId}");
            $results = DB::table('renders as r')
                ->select(
                    'r.id as render_id','r.status as render_status','r.c4dProjectWithAssets','r.outputFormat',
                    'rd.id as render_detail_id','rd.status as detail_status','rd.allocated_to_user_id','rd.from','rd.to',
                    'u.id as submittedByUserId','u.user_token as submittedByUserToken'
                )
                ->join('render_details as rd', 'rd.render_id', '=', 'r.id')
                ->join('users as u', 'u.id', '=', 'r.submitted_by_user_id')
                ->where('r.id', '=', $renderId)
                ->where('r.status', '=', Render::RETURNED)
                ->orderBy('render_id', 'ASC')
                ->orderBy('rd.id', 'ASC')
                ->get();

            if (count($results) > 0) {

                foreach ($results as $result) {
                    // Render detail now set to returned
                    $renderDetail = RenderDetail::find($result->render_detail_id);
                    if (!$renderDetail) {
                        throw new \Exception("Could not find render detail record with id '{$result->render_detail_id}'");
                    }

                    //Log::info('Housekeeping render detail: ' . $result->render_detail_id);

                    // Get all the images from the renders directory, so we can delete those for this render
                    $dir = "uploads/{$result->submittedByUserToken}/renders";
                    if (!is_dir($dir)) {
                        throw new \Exception("Required directory '$dir' does not exist");
                    }

                    $images = array_diff(
                        scandir($dir, SCANDIR_SORT_DESCENDING),
                            array('.', '..','.DS_Store')
                    );
                    // Add an entry for each render that has occured in the range from and to
                    for ($i=$renderDetail->from; $i<=$renderDetail->to; $i++) {
                        // NB we have to find the actual name in the directory using the frame number, which is reliable.
                        $fileName = $this->getArrayValue($images, sprintf("%04d", $i) . "." . $result->outputFormat);
                        if (null !== $fileName) {
                            if (unlink("uploads/{$result->submittedByUserToken}/renders/{$result->render_id}/{$fileName}")) {
                                Log::info("File: {$fileName} successfully deleted");
                            } else {
                                throw new \Exception("Could not delete file '{$fileName}'");
                            }
                        }
                    }
                }

            }

        } catch(\Exception $exception) {
            $result = 'Error';
            $message = $exception->getMessage();
            Log::info('Error: ' . $message);
        }
    }

}
