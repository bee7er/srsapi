<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
    public static function getSubmissionsAndRenders($selectedUserId = 0, $includeReturned = 0, $renderId = 0)
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
     * Returns submission and render details in an HTML string
     *
     * @param $submittedByUserId
     * @return string
     */
    public static function getSubmissionsAndRendersAsHTML($submittedByUserId)
    {
        list($submissions, $renders) = self::getSubmissionsAndRenders($submittedByUserId);
        $availableUsers = User::getAvailableUsers();
        $html = '<table style="font-size:11px;width:100%;">';
        $html .= '<tr><td colspan="4" style="font-size:16px;font-weight:bold;">Team Members Currently Available</td></tr>';
        $html .= "<tr><th>Surname</th><th>First Name</th><th>Email</th><th>Status</th></tr>";
        $entryCount = 0;

        $color = $grey = '#d8d8d8';
        $blue = '#a6ffff';
        if (is_array($availableUsers) && 0 < count($availableUsers)) {
            foreach ($availableUsers as $availableUser) {
                $entryCount++;
                // Highlight the user when they are available
                if ($availableUser->id == $submittedByUserId) {
                    $color = '#a851d6';
                    $availableUser->surname = 'You';
                    $availableUser->first_name = '';
                }
                // Build a row with this information
                $html .= "<tr style=\"background-color: {$color};\"><td>{$availableUser->surname}</td><td>{$availableUser->first_name}</td><td>{$availableUser->email}</td><td>{$availableUser->status}</td></tr>";
                $color = ($color == $grey ? $blue: $grey);
            }
        } else {
            $html .= "<tr style=\"background-color: {$color};\"><td colspan=\"4\">None</td></tr>";
        }
        $html .= "<tr style=\"background-color: {$color};text-align: right;\"><th colspan=\"4\" style=\"padding-right: 15px;\">Entry count: {$entryCount}</th></tr>";

        $html .= '<tr><td colspan="4" style="font-size:16px;font-weight:bold;">Submitted by You</td></tr>';
        $html .= "<tr><th>Project</th><th>Chunk</th><th>Allocated to</th><th>Status</th></tr>";
        $entryCount = 0;

        $color = $grey = '#d8d8d8';
        $blue = '#a6ffff';
        if (is_array($submissions) && 0 < count($submissions)) {
            foreach ($submissions as $submission) {
                $entryCount++;
                // Build a row with this information
                $html .= "<tr style=\"background-color: {$color};\"><td>{$submission->c4dProjectWithAssets}</td><td>{$submission->from} to {$submission->to}</td><td>{$submission->first_name} {$submission->surname}</td><td>{$submission->detail_status}</td></tr>";
                $color = ($color == $grey ? $blue: $grey);
            }
        } else {
            $html .= "<tr style=\"background-color: {$color};\"><td colspan=\"4\">None</td></tr>";
        }
        $html .= "<tr style=\"background-color: {$color};text-align: right;\"><th colspan=\"4\" style=\"padding-right: 15px;\">Entry count: {$entryCount}</th></tr>";

        $html .= '<tr><td colspan="4" style="font-size:10px;">&nbsp;</td></tr>';
        $html .= '<tr><td colspan="4" style="font-size:16px;font-weight:bold;">Allocated to You</td></tr>';
        $html .= "<tr><th>Project</th><th>Chunk</th><th>Submitted by</th><th>Status</th></tr>";
        $entryCount = 0;

        $color = $grey;
        if (is_array($renders) && 0 < count($renders)) {
            foreach ($renders as $render) {
                $entryCount++;
                // Build a row with this information
                $html .= "<tr style=\"background-color: {$color};\"><td>{$render->c4dProjectWithAssets}</td><td>{$render->from} to {$render->to}</td><td>{$render->first_name} {$render->surname}</td><td>{$render->detail_status}</td></tr>";
                $color = ($color == $grey ? $blue: $grey);
            }
        } else {
            $html .= "<tr style=\"background-color: {$color};\"><td colspan=\"4\">None</td></tr>";
        }
        $html .= "<tr style=\"background-color: {$color};text-align: right;\"><th colspan=\"4\" style=\"padding-right: 15px;\">Entry count: {$entryCount}</th></tr>";

        $html .= '</table>';

        return $html;
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
                'r.id as render_id','r.status as render_status','r.c4dProjectWithAssets','r.outputFormat',
                'u.id as submittedByUserId','u.api_token as submittedByUserApiToken'
            )
            ->join('renders as r', 'r.id', '=', 'rd.render_id')
            ->join('users as u', 'u.id', '=', 'r.submitted_by_user_id')
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
    public static function getOutstandingRenders($submittedByUserId)
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

    /**
     * Returns completed render details which have not been downloaded (returned)
     *
     * @param $submittedByUserId
     * @return mixed
     */
    public static function getOutstandingRenderDetails($submittedByUserId)
    {
        $frameDetails = [];

        $outstandingRenders = self::getOutstandingRenders($submittedByUserId);
        if (is_array($outstandingRenders) && 0 < count($outstandingRenders)) {
            foreach ($outstandingRenders as $outstandingRender) {
                // No change of status, just put the data together
                $c4dProjectWithAssets = $outstandingRender->c4dProjectWithAssets;
                for ($i=$outstandingRender->from; $i<=$outstandingRender->to; $i++) {
                    $frameDetails[] = $c4dProjectWithAssets . sprintf("%03d", $i);

                }
            }
        }

        return [$frameDetails];
    }
}
