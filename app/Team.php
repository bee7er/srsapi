<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    protected $fillable = ['name', 'description', 'status'];

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
                't.id','t.name','t.description','t.status'
            )
            ->where('t.status', '=', 'active');

        $teams = $builder
            ->orderBy('t.name', 'ASC')
            ->get();

        return $teams;
    }
}
