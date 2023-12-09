<?php

namespace App\Http\Controllers;

use App\User;
use App\Team;
use App\TeamMember;
use App\Http\Requests;
use App\Providers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class TeamMembersController extends Controller
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
     * Displays a list of team members
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        // Get all the team members
        $teamId = Input::get('id');
        $team = Team::find($teamId);
        if (!$team) {
            Session::flash('flash_message', 'Could not find team for id ' . $teamId);
            Session::flash('flash_type', 'alert-danger');
            return redirect("teams");
        }

        $builder = DB::table('team_members as tm')
            ->select(
                'tm.id as teamMemberId','tm.teamId','tm.userId','tm.status as teamMemberStatus',
                't.id as teamId','t.status as teamStatus',
                'u.surname','u.first_name'
            )
            ->join('teams as t', 't.id', '=', 'tm.teamId')
            ->leftjoin('users as u', 'u.id', '=', 'tm.userId')
            ->where(['tm.teamId' => $teamId]);

        $teamMembers = $builder
            ->orderBy('u.surname', 'ASC')
            ->orderBy('u.first_name', 'ASC')
            ->get();
        $goBackTo = '/teams';

        return view('team_members.index', compact('teamId', 'team', 'teamMembers', 'goBackTo'));
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
        $teamId = Input::get('id');
        $team = Team::find($teamId);
        if (!$team) {
            return redirect("team_members?id=$teamId");
        }

        $statuses = [User::UNAVAILABLE];
        $roles = [User::USER];
        $goBackTo = '/team_members?id=' . $teamId;

        return view('team_members.create', compact('teamId', 'team', 'statuses', 'roles', 'goBackTo'));
    }

    /**
     * Remove the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        $teamMemberId = Input::get('id');
        $teamMember = TeamMember::find($teamMemberId);
        if (!$teamMember) {
            return redirect("team_members?id=$teamMemberId");
        }
        // Remove this user as a member to the team
        $teamMember->delete();
        Session::flash('flash_message', 'Successfully removed a user from the team');
        Session::flash('flash_type', 'alert-success');

        $teamId = Input::get('teamId');
        return redirect("team_members?id=$teamId");
    }

    /**
     * Displays a list of users with ability to select a new member
     *
     * @return \Illuminate\View\View
     */
    public function select()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }

        // Get all the users who are not members already to this team
        $teamId = Input::get('teamId');
        $team = Team::find($teamId);
        if (!$team) {
            return redirect("teams/$teamId/edit");
        }

        $builder = DB::table('users as u')
            ->select(
                'u.id', 'u.surname','u.first_name'
            )
            ->whereNotIn('id', objectsAttributeToArray(DB::table('team_members')->select('userId')->where('teamId', '=', $teamId)->get(), 'userId'));

        $users = $builder
            ->orderBy('u.surname', 'ASC')
            ->orderBy('u.first_name', 'ASC')
            ->get();
        $goBackTo = '/team_members?id=' . $teamId;

        return view('team_members.select', compact('teamId', 'team', 'users', 'goBackTo'));
    }

    /**
     * Add the selected user to the team.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        $userId = Input::get('id');
        $teamId = Input::get('teamId');
        $team = Team::find($teamId);
        if (!$team) {
            Session::flash('flash_message', 'Could not find team for id ' . $teamId);
            Session::flash('flash_type', 'alert-danger');
            return redirect("team_members?id=$teamId");
        }

        $teamMember = TeamMember::where(['userId' => $userId, 'teamId'  => $teamId]);
        if (!$teamMember) {
            Session::flash('flash_message', 'Team member already exists for user id ' . $userId . ' in team id ' . $teamId);
            Session::flash('flash_type', 'alert-danger');
            return redirect("team_members?id=$teamId");
        }
        // Add this user as a member to the team
        $teamMember = new TeamMember();
        $teamMember->teamId = $teamId;
        $teamMember->userId = $userId;
        $teamMember->status = TeamMember::ACTIVE;
        $teamMember->save();

        Session::flash('flash_message', 'Successfully created a new user for team');
        Session::flash('flash_type', 'alert-success');

        return redirect("team_members?id=$teamId");
    }

    /**
     * Displays a list of teams to which this user belongs
     *
     * @return \Illuminate\View\View
     */
    public function membership()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }

        $userId = Input::get('userId');
        $user = User::find($userId);
        if (!$user) {
            Session::flash('flash_message', 'Could not find user for user id ' . $userId);
            Session::flash('flash_type', 'alert-danger');
            return redirect("users");
        }

        $builder = DB::table('team_members as tm')
            ->select(
                'tm.id as teamMemberId','tm.teamId','tm.userId','tm.status as teamMemberStatus',
                't.id as teamId','t.status as teamStatus','t.name',
                'u.surname','u.first_name'
            )
            ->join('teams as t', 't.id', '=', 'tm.teamId')
            ->leftjoin('users as u', 'u.id', '=', 'tm.userId')
            ->where(['tm.userId' => $userId]);

        $teams = $builder
            ->orderBy('t.name', 'ASC')
            ->get();
        $goBackTo = '/users';

        return view('team_members.membership', compact('teams', 'user', 'goBackTo'));
    }


}
