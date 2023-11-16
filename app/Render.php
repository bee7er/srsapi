<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Render extends Model
{
    // Render statuses
    const OPEN = 'open';    // Not ready yet
    const READY = 'ready';
    const RENDERING = 'rendering';
    const COMPLETE = 'complete';
    const RETURNED = 'returned';
    const CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'submitted_by_user_id','status'
    ];

    /**
     * A render or render detail has changed, update the user
     * @param $renderId
     * @param null $allocatedToUserId
     * @throws Exception
     */
    public static function dataHasChanged($renderId, $allocatedToUserId=null)
    {
        $render = Render::find($renderId);
        if (!$render) {
            throw new Exception("Could not find render with id $renderId");
        }
        $user = User::where('id', $render->submitted_by_user_id)->first();
        if (!$user) {
            throw new Exception("Could not find user with id $render->submitted_by_user_id");
        }
        $user->data_changed = true;
        $user->save();
        // If the allocated to user id is different then update that user also
        if ($allocatedToUserId && $user->id != $allocatedToUserId) {
            $user = User::where('id', $allocatedToUserId)->first();
            if (!$user) {
                throw new Exception("Could not find user with id $allocatedToUserId");
            }
            $user->data_changed = true;
            $user->save();
        }
    }
}
