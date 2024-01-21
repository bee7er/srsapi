<?php

namespace App\Http\Controllers;

use App\Team;
use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TeamsController extends Controller
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');      # User must be logged in
    }

    /**
     * Displays a list of teams
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        // Get all the current teams
        $teams = Team::all()->sortBy(['teamName']);
        $goBackTo = '/';

        return view('teams.index', compact('teams','goBackTo'));
    }

    /**
     * Show the form for creating a new team.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        $statuses = Team::$statuses;
        $goBackTo = '/teams';

        return view('teams.create', compact('statuses','goBackTo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        // Read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'teamName'        => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('teams/create')
                ->withErrors($validator);
        } else {
            $team = new Team();
            $team->setTeamToken();
            $team->teamName        = Input::get('teamName');
            $team->createdByUserId = Auth::user()->id;
            $team->description     = Input::get('description');
            $team->status          = Input::get('status');
            $team->save();

            Session::flash('flash_message', 'Successfully created a new team');
            Session::flash('flash_type', 'alert-success');
            return redirect('teams');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        $team = Team::where('id', $id)->first();
        $statuses = Team::$statuses;
        $createdByUser = User::where('id', $team->createdByUserId)->first();
        $goBackTo = '/teams';

        return view('teams.show', compact('team', 'statuses', 'createdByUser', 'goBackTo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        $team = Team::where('id', $id)->first();
        $statuses = Team::$statuses;
        $createdByUser = User::where('id', $team->createdByUserId)->first();
        $goBackTo = '/teams';

        return view('teams.edit', compact('team', 'statuses', 'createdByUser', 'goBackTo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        // Read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'teamName'        => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        $id = Input::get('id');
        if ($validator->fails()) {
            return redirect("teams/$id/edit")
                ->withErrors($validator);
        } else {
            $team = Team::find($id);
            if (!$team) {
                return redirect("teams/$id/edit")
                    ->withErrors($validator);
            }
            $team->teamName    = Input::get('teamName');
            $team->description = Input::get('description');
            $team->status      = Input::get('status');
            $team->save();

            Session::flash('flash_message', 'Successfully updated team');
            Session::flash('flash_type', 'alert-success');
            return redirect('teams');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        dd('destroy got id='.$id);
        // Make the team inactive
        $id = Input::get('id');
        if (!$id) {
            dd('destroy has no id');
        } else {
            $team = Team::find($id);
            if (!$team) {
                dd('destroy could not find team for id=' . $id);
            }
            $team->status = Team::INACTIVE;
            $team->save();

            Session::flash('flash_message', 'Successfully updated team to inactive');
            Session::flash('flash_type', 'alert-success');
        }
        return redirect('teams');
    }

    /**
     * Toggles the status of a given team
     *
     * @return \Illuminate\View\View
     */
    public function toggleTeamStatus()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }

        $id = Input::get('id');
        $team = Team::find($id);
        if (!$team) {
            Session::flash('flash_message', 'Could not find team for id ' . $id);
            Session::flash('flash_type', 'alert-danger');
            return redirect("teams");
        }

        // Toggle team status
        $newStatus = $team->status == Team::ACTIVE ? Team::INACTIVE : Team::ACTIVE;
        $team->status = $newStatus;
        $team->save();

        Session::flash('flash_message', "Successfully changed team status to {$newStatus} for team {$team->teamName}");
        Session::flash('flash_type', 'alert-success');

        return redirect("teams");
    }
}
