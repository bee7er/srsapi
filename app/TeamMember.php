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
}
