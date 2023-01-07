<?php

namespace App\Http\Controllers;

use App\Render;
use App\Http\Requests;
use App\RenderDetail;
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
        // Get all the current renders
        $submissions = DB::table('render_details as rd')
            ->select(
                'rd.id as render_detail_id','rd.status','rd.from','rd.to',
                'r.id as render_id','r.status as render_status','r.c4dProjectWithAssets','r.outputFormat',
                'r.created_at','r.completed_at',
                'u.surname','u.first_name'
            )
            ->join('renders as r', 'r.id', '=', 'rd.render_id')
            ->leftjoin('users as u', 'u.id', '=', 'rd.allocated_to_user_id')
            ->where('r.submitted_by_user_id', '=', Auth::id())
            ->orderBy('r.status', 'r.created_at', 'r.completed_at')
            ->get();

        $renders = DB::table('render_details as rd')
            ->select(
                'rd.id as render_detail_id','rd.status','rd.from','rd.to',
                'r.id as render_id','r.status as render_status','r.c4dProjectWithAssets','r.outputFormat',
                'r.created_at','r.completed_at',
                'u.surname','u.first_name'
            )
            ->join('renders as r', 'r.id', '=', 'rd.render_id')
            ->join('users as u', 'u.id', '=', 'r.submitted_by_user_id')
            ->where('rd.allocated_to_user_id', '=', Auth::id())
            ->orderBy('r.status', 'r.created_at', 'r.completed_at')
            ->get();

        return view('renders.index', compact('submissions', 'renders'));
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
