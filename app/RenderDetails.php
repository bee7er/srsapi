<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RenderDetails extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'allocated_to_user_id','status'
    ];
}
