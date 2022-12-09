<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RenderDetail extends Model
{
    // Render detail statuses
    const READY = 'ready';
    const ALLOCATED = 'allocated';
    const DONE = 'done';
    const RETURNED = 'returned';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'allocated_to_user_id','status'
    ];
}
