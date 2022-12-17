<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Render extends Model
{
    // Render statuses
    const OPEN = 'open';    // Not ready yet
    const READY = 'ready';
    const RENDERING = 'rendering';
    const COMPLETE = 'complete';
    const RETURNED = 'returned';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'submitted_by_user_id','status'
    ];
}
