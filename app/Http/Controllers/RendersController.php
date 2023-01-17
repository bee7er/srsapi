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
            $builder = DB::table('users as u');
            if (!Auth::user()->isAdmin()) {
                // Not admin so only show the logged in user in the selection box
                $builder->where('u.id', Auth::user()->id);
            }
            $users = $builder
                ->orderBy('u.surname', 'u.first_name')
                ->get();

            list($submissions, $renders) = RenderDetail::getSubmisionsAndRenders($selectedUserId, $includeReturned);

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
