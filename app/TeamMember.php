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
}
