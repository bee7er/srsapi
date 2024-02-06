<?php

namespace App\Http\Controllers\Api;

use App\BlockedUser;
use App\Http\Controllers\Controller;
use App\Team;
use App\TeamMember;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BlockedUsersController extends Controller
{
    const TEAMTOKEN = "teamToken";
    const EMAIL = "email";
    const USERTOKEN = "userToken";
    const USERID = "userId";

    /**
     * Block a user from handling renders for the requesting user in the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function block(Request $request)
    {
        try {
            Log::info('In block user: ' . $request->get(self::EMAIL) . ' ' . $request->get(self::TEAMTOKEN) . ' ' . $request->get(self::USERTOKEN));

            list($user, $team) = $this->checkParams($request);

            // Block the target user, if not already blocked
            $userId = $request->get(self::USERID);
            $blockedUser = BlockedUser::where(['userId'=>$user->id, 'teamId'=>$team->id, 'blockedUserId'=>$userId]) -> first();
            if ($blockedUser) {
                throw new \Exception("User '{$userId}' is already blocked for user '{$user->userName}' and team '{$team->teamName}'");
            }

            $blockedUser = new BlockedUser();
            $blockedUser->userId        = $user->id;
            $blockedUser->teamId        = $team->id;
            $blockedUser->blockedUserId = $userId;
            $blockedUser->save();

        } catch(\Exception $exception) {

            Log::info('error: ' . $exception->getMessage());

            throw new HttpException(400, "Error - {$exception->getMessage()}");
        }
    }

    /**
     * Unblock a user to enable handling of renders for the requesting user in the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unblock(Request $request)
    {
        try {
            Log::info('In unblock user: ' . $request->get(self::EMAIL) . ' ' . $request->get(self::TEAMTOKEN) . ' ' . $request->get(self::USERTOKEN));

            list($user, $team) = $this->checkParams($request);

            // Unblock the target user, if not already unblocked
            $userId = $request->get(self::USERID);
            $blockedUser = BlockedUser::where(['userId'=>$user->id, 'teamId'=>$team->id, 'blockedUserId'=>$userId]) -> first();
            if (!$blockedUser) {
                throw new \Exception("User '{$userId}' is not currently blocked for user '{$user->userName}' and team '{$team->teamName}'");
            }

            $blockedUser->delete();

        } catch(\Exception $exception) {

            Log::info('error: ' . $exception->getMessage());

            throw new HttpException(400, "Error - {$exception->getMessage()}");
        }
    }

    /**
     * Check params are present and valid
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     * @throws \Exception
     */
    private function checkParams(Request $request)
    {
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

            return [$user, $team];

        } else {
            throw new \Exception("Could not find requesting user for email '{$request->get(self::EMAIL)}'");
        }
    }

}
