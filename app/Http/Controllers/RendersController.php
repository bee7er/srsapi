<?php

namespace App\Http\Controllers;

use App\Render;
use App\Http\Requests;
use App\RenderDetail;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RendersController extends Controller
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');      # Render must be logged in
    }

    /**
     * Displays a list of renders
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $submissions = $renders = $users = $user = null;
        $selectedUserId = $includeReturned = 0;
        try {
            // Check if we are selecting by user
            $selectedUserId = Input::get('selectedUserId');
            $includeReturned = Input::get('includeReturned');
            $user = null;

            if (0 != $selectedUserId) {
                $user = User::where('id', $selectedUserId)->first();
            }
            $users =  $builder = DB::table('users as u')
                ->orderBy('u.surname', 'u.first_name')
                ->get();

            // Get all the current renders
            $builder = DB::table('render_details as rd')
                ->select(
                    'rd.id as render_detail_id','rd.status as detail_status','rd.from','rd.to',
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
            $submissions = $builder
                ->orderBy('r.id', 'ASC')
                ->orderBy('render_status', 'ASC')
                ->get();

            $builder = DB::table('render_details as rd')
                ->select(
                    'rd.id as render_detail_id','rd.status as detail_status','rd.from','rd.to',
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
            $renders = $builder
                ->orderBy('r.id', 'ASC')
                ->orderBy('render_status', 'ASC')
                ->get();

        } catch(Exception $e) {
            Session::flash('flash_message', 'Error gathering render data: ' . $e->getMessage());
            Session::flash('flash_type', 'alert-danger');
        }

        return view('renders.index', compact('submissions', 'renders', 'users', 'selectedUserId', 'user', 'includeReturned'));
    }

    /**
     * Reassign a render detail record to another slave
     *
     * @return \Illuminate\Http\Response
     */
    public function reassign()
    {
        try {
            $renderDetailsId = Input::get('render_detail_id');

            $renderDetail = RenderDetail::find($renderDetailsId);
            if (!$renderDetail) {
                throw new Exception("Could not find render detail with id $renderDetailsId");
            }
            $renderDetail->allocated_to_user_id = 0;
            $renderDetail->status = RenderDetail::READY;
            $renderDetail->save();

            Session::flash('flash_message', 'Successfully updated render detail');
            Session::flash('flash_type', 'alert-success');

        } catch(Exception $e) {
            Session::flash('flash_message', 'Error updating render detail: ' . $e->getMessage());
            Session::flash('flash_type', 'alert-danger');
        }

        return redirect('renders');
    }
}
