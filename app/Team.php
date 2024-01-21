<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Team extends Model
{
    // Team statuses
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'teams';

    /**
     * Have the date fields set automatically
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['token', 'teamName', 'description', 'status'];

    /**
     * The statuses a team can adopt.
     *
     * @var array
     */
    public static $statuses = ['active', 'inactive'];

    /**
     * Returns arrays of teams who are currently available
     *
     * @return array
     */
    public static function getAvailableTeams()
    {
        $builder = DB::table('teams as t')
            ->select(
                't.id','t.token','t.createdByUserId','t.teamName','t.description','t.status'
            )
            ->where('t.status', '=', 'active');

        $teams = $builder
            ->orderBy('t.teamName', 'ASC')
            ->get();

        return $teams;
    }

    /**
     * Set the team token.
     */
    public function setTeamToken()
    {
        $this->token = $this->getNewToken();
    }

    /**
     * Get a new, unique team token.
     */
    private function getNewToken()
    {
        $token = null;
        // Try to get a unique token
        for ($i=0; $i<10; $i++) {
            $token = Str::random(16);
            // Check the token is unique
            $team = Team::where('token', $token)->first();
            if (!$team) {
                return $token;  // Ok, is unique
            }
        }
        throw new \Exception("Could not generate a unique token for new team");
    }
}
