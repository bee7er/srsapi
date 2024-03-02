<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeamMember extends Model
{
    // Team Member statuses
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userId','status'
    ];

    /**
     * The statuses a team can adopt.
     *
     * @var array
     */
    public static $statuses = ['active', 'inactive'];

    /**
     * Check the user is an active team member
     *
     * @param $userId
     * @param $teamId
     * @throws \Exception
     */
    public static function checkTeamMembership($userId, $teamId)
    {
        $teamMember = self::where(['userId'=>$userId, 'teamId'=>$teamId]) -> first();
        if (!$teamMember) {
            throw new \Exception("Team membership not found for user id '{$userId}' and team id '{$teamId}'");
        }
        if ($teamMember->status !== self::ACTIVE) {
            throw new \Exception("Team membership id not active for user id '{$userId}' and team id '{$teamId}'");
        }
    }

    /**
     * Gets a list of teams to which this user belongs, including other team
     * members and whether they are blocked
     *
     * @return \Illuminate\View\View
     */
    public static function getTeamsAndMembers($userId, $teamId=null)
    {
        $builder = DB::table('team_members as tm')
            ->select(
                'tm.id as teamMemberId','tm.teamId','tm.userId','tm.status as teamMemberStatus',
                't.id as teamId','t.status as teamStatus','t.teamName',
                'u.id as userId','u.userName','u.email','u.user_token as userToken','u.status'
            )
            ->join('teams as t', 't.id', '=', 'tm.teamId')
            ->leftjoin('users as u', 'u.id', '=', 'tm.userId')
            ->where(['tm.userId' => $userId]);

        if (null !== $teamId) {
            // Filter for a specific team id
            $builder->where(['t.id' => $teamId]);
        }

        $teams = $builder
            ->orderBy('t.teamName', 'ASC')
            ->get();

        // Add other members for each team
        foreach ($teams as $team) {
            $builder = DB::table('team_members as tm')
                ->select('tm.id as teamMemberId','tm.teamId','u.id as userId','u.userName','u.email','u.user_token as userToken','u.status')
                ->leftjoin('users as u', 'u.id', '=', 'tm.userId')
                ->where(['tm.teamId' => $team->teamId])
                ->where('u.id','!=', $userId);

            $otherTeamMembers = $builder
                ->orderBy('u.userName', 'ASC')
                ->get();

            foreach ($otherTeamMembers as &$otherTeamMember) {
                $otherTeamMember->isBlocked = false;
                $otherTeamMember->blockedStatus = 'not blocked';
                if (BlockedUser::where(['teamId' => $otherTeamMember->teamId,'userId' => $userId,'blockedUserId' => $otherTeamMember->userId])->exists()) {
                    $otherTeamMember->isBlocked = true;
                    $otherTeamMember->blockedStatus = 'blocked';
                }
            }

            $team->otherTeamMembers = $otherTeamMembers;
        }

        return $teams;
    }
}
