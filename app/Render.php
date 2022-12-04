<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Render extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'submitted_by_user_id','status'
    ];
}