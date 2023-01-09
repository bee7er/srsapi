<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * Returns arrays of submissions and renders
     *
     * @param int $selectedUserId
     * @param int $includeReturned
     * @param int $renderId
     * @return array
     */
    public static function getSubmisionsAndRenders($selectedUserId = 0, $includeReturned = 0, $renderId = 0)
    {
        // Get all the current renders
        $builder = DB::table('render_details as rd')
            ->select(
                'rd.id as render_detail_id','rd.status as detail_status','rd.allocated_to_user_id','rd.from','rd.to',
                'r.id as render_id','r.status as render_status','r.c4dProjectWithAssets','r.outputFormat',
                'r.created_at','r.completed_at',
                'u.surname','u.first_name'
            )
            ->join('renders as r', 'r.id', '=', 'rd.render_id')
            ->leftjoin('users as u', 'u.id', '=', 'rd.allocated_to_user_id');
        // If selected user id not null or zero
        if (0 != $selectedUserId) {
            $builder->where('r.submitted_by_user_id', '=', $selectedUserId);
        }
        // If include returned is not yes exclude them
        if (!isset($includeReturned)) {
            $builder->where('r.status', '!=', Render::RETURNED);
        }
        // If a specific render is needed
        if (0 < $renderId) {
            $builder->where('r.id', '=', $renderId);
        }
        $submissions = $builder
            ->orderBy('r.id', 'ASC')
            ->orderBy('render_status', 'ASC')
            ->get();

        $builder = DB::table('render_details as rd')
            ->select(
                'rd.id as render_detail_id','rd.status as detail_status','rd.allocated_to_user_id','rd.from','rd.to',
                'r.id as render_id','r.status as render_status','r.c4dProjectWithAssets','r.outputFormat',
                'r.created_at','r.completed_at',
                'u.surname','u.first_name'
            )
            ->join('renders as r', 'r.id', '=', 'rd.render_id')
            ->join('users as u', 'u.id', '=', 'r.submitted_by_user_id');
        // If selected user id not null or zero
        if (0 != $selectedUserId) {
            $builder->where('rd.allocated_to_user_id', '=', $selectedUserId);
        }
        // If include returned is not yes exclude them
        if (!isset($includeReturned)) {
            $builder->where('r.status', '!=', Render::RETURNED);
        }
        // If a specific render is needed
        if (0 < $renderId) {
            $builder->where('r.id', '=', $renderId);
        }
        $renders = $builder
            ->orderBy('r.id', 'ASC')
            ->orderBy('render_status', 'ASC')
            ->get();

        return [$submissions, $renders];
    }

    /**
     * Returns completed render details which have not been downloaded (returned)
     *
     * @param $submittedByUserId
     * @return mixed
     */
    public static function getCompletedRenderDetails($submittedByUserId)
    {
        return DB::table('render_details as rd')
            ->select(
                'rd.id','rd.status','rd.allocated_to_user_id','rd.from','rd.to',
                'r.id as render_id','r.status as render_status','r.c4dProjectWithAssets','r.outputFormat'
            )
            ->join('renders as r', 'r.id', '=', 'rd.render_id')
            ->where('r.submitted_by_user_id', '=', $submittedByUserId)
            ->where('r.status', '=', Render::COMPLETE)
            ->orderBy('render_id', 'ASC')
            ->orderBy('rd.id', 'ASC')
            ->get();
    }

    /**
     * Returns completed render details which have not been downloaded (returned)
     *
     * @param $submittedByUserId
     * @return mixed
     */
    public static function getOutstandingRenderDetails($submittedByUserId)
    {
        return DB::table('render_details as rd')
            ->select(
                'rd.id','rd.status','rd.allocated_to_user_id','rd.from','rd.to',
                'r.id as render_id','r.status as render_status','r.c4dProjectWithAssets','r.outputFormat'
            )
            ->join('renders as r', 'r.id', '=', 'rd.render_id')
            ->where('r.submitted_by_user_id', '=', $submittedByUserId)
            ->whereIn('r.status', [Render::READY, Render::RENDERING])
            ->orderBy('render_id', 'ASC')
            ->orderBy('rd.id', 'ASC')
            ->get();
    }
}
