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
    const ALLOCATEDTOUSERS = "allocatedToUsers";
    const APITOKEN = "apiToken";
    const AVAILABILITY = "availability";
    const C4DPROJECTWITHASSETS = "c4dProjectWithAssets";
    const CUSTOMFRAMERANGE = "customFrameRange";
    const EMAIL = "email";
    const FRAMERANGES = "frameRanges";
    const FROM = "from";
    const OUTPUTFORMAT = "outputFormat";
    const OVERRIDESETTINGS = "overrideSettings";
    const RENDERDETAILID = "renderDetailId";
    const TO = "to";

    # Action instruction
    const  AI_DO_RENDER = 'render';
    const  AI_DO_DOWNLOAD = 'download';
    const  AI_DO_DISPLAY_OUTSTANDING = 'outstanding';

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
            Log::info('In register user for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {
                $user->checkApiToken($request->get(self::APITOKEN));

                // Update user with availability
                $user->status     = ($request->get(self::AVAILABILITY) == self::AVAILABILITY_AVAILABLE ? 'available': 'unavailable');
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
                $user->checkApiToken($request->get(self::APITOKEN));

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
                    ->orderBy('render_id', 'ASC')
                    ->orderBy('rd.id', 'ASC')
                    ->first();

                if (null == $result) {
                    $message = "No renders are currently pending";
                    Log::info($message);
                    $result = 'OK';
                } else {
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

        } catch(\Exception $exception) {
            $result = 'Error';
            $message = $exception->getMessage();
            Log::info('Error: ' . $message);
        }

        $returnData = [
            self::ACTIONINSTRUCTION => $actionInstruction,
            self::RENDERDETAILID => $renderDetailId,
            self::C4DPROJECTWITHASSETS => $c4dProjectWithAssets,
            self::FROM => $from,
            self::TO => $to,
            self::OUTPUTFORMAT => $outputFormat,
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
        try {
            $message = "Awake notification received OK";
            $result = 'Error';
            $actionInstruction = '';
            $c4dProjectWithAssets = '';
            $frameRanges = [];
            $allocatedToUsers = [];

            Log::info('In awake user for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {
                $user->checkApiToken($request->get(self::APITOKEN));

                // Heart beat from the slaves, it signals that the slave is active
                Log::info('Got user for email: ' . $request->get(self::EMAIL));

                // Here we check to see if any earlier requests from this user have been rendered;
                // the slave user can then pull down the results
                $results = RenderDetail::getCompletedRenderDetails($user->id);

                if (null == $results) {
                    $message = "No renders are currently available for download";
                    Log::info($message);
                } else {
                    $count = 1;
                    if (is_array($results)) {
                        $count = count($results);
                    }
                    Log::info('Render details available for downloads: ' . $count);
                    foreach ($results as $result) {
                        // Render detail now set to returned
                        $renderDetail = RenderDetail::find($result->id);
                        if (!$renderDetail) {
                            throw new \Exception("Could not find render detail record with id '{$result->id}'");
                        }
                        $renderDetail->status = RenderDetail::RETURNED;
                        $renderDetail->save();

                        $c4dProjectWithAssets = $result->c4dProjectWithAssets;
                        $frameRanges[] = "{$result->from}-{$result->to}";
                    }
                    // Render now set to returned and it is fully processed
                    $render = Render::find($result->render_id);
                    if (!$render) {
                        throw new \Exception("Could not find render record with id '{$result->render_id}'");
                    }
                    $render->status = Render::RETURNED;
                    $render->save();

                    $actionInstruction = self::AI_DO_DOWNLOAD;
                }

                if (self::AI_DO_DOWNLOAD != $actionInstruction) {
                    // We are not instructing the slave to do downloads, so check for outstanding renders
                    $results = RenderDetail::getOutstandingRenderDetails($user->id);
                    // Return the data so that it can be displayed to the slave user
                    foreach ($results as $result) {
                        // No change of status, just put the data together
                        $c4dProjectWithAssets = $result->c4dProjectWithAssets;
                        $frameRanges[] = "{$result->from}-{$result->to}";
                        if (0 < $result->allocated_to_user_id) {
                            $allocatedUser = User::where('id', $result->allocated_to_user_id) -> first();
                            if ($allocatedUser) {
                                $allocatedToUsers[] = $allocatedUser->getName();
                            }
                        } else {
                            $allocatedToUsers[] = "None";
                        }
                    }

                    $actionInstruction = self::AI_DO_DISPLAY_OUTSTANDING;
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
            self::FRAMERANGES => $frameRanges,
            self::ALLOCATEDTOUSERS => $allocatedToUsers,
            "result" => $result,
            "message" => $message,
        ];

        return $returnData;   // Gets converted to json
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
            Log::info('In status request for email: ' . $request->get(self::EMAIL));

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {
                $user->checkApiToken($request->get(self::APITOKEN));

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
                    Log::info($message);
                } else {
//                    Log::info('Render details available for downloads: ' . print_r($renderResults, true));
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

            Log::info('In rendering for email: ' . $request->get(self::EMAIL));
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

}
