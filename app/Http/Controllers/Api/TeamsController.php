<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Team;
use App\TeamMember;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeamsController extends Controller
{
    const EMAIL = "email";
    const USERTOKEN = "userToken";

    /**
     * Creates a new team and returns the token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function newTeam(Request $request)
    {
        $message = "New team created OK";
        $result = 'OK';
        $newTeamToken = '';
        try {
            Log::info('In create new team');

            $user = User::where('email', $request->get(self::EMAIL)) -> first();
            if ($user) {
                $user->checkUserToken($request->get(self::USERTOKEN));

                $team = new Team();
                $team->setTeamToken();
                $team->teamName        = "Team {$team->token}";
                $team->createdByUserId = $user->id;
                $team->description     = "";
                $team->status          = Team::ACTIVE;
                $team->save();

                $newTeamToken = $team->token;

                Log::info('Team created OK');

                // Add the user as a member of their new team
                $teamMember = new TeamMember();
                $teamMember->teamId = $team->id;
                $teamMember->userId = $user->id;
                $teamMember->status = TeamMember::ACTIVE;
                $teamMember->save();

                Log::info('Team member ' . $user->id . ' added to team ' . $team->id);

            } else {
                throw new \Exception("Could not find user with email: " . $request->get(self::EMAIL));
            }

        } catch(\Exception $exception) {
            $result = 'Error';
            $message = $exception->getMessage();
            Log::info('Exception: ' . $message);
        }

        $returnData = [
            "newTeamToken" => $newTeamToken,
            "result" => $result,
            "message" => $message,
        ];

        return $returnData;
    }

}
