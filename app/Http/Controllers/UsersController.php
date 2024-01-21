<?php

namespace App\Http\Controllers;

use App\TeamMember;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
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
     * Displays a list of users
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        // Get all the current users
        $users = User::all()->sortBy(['userName']);
        $goBackTo = '/';

        return view('users.index', compact('users', 'goBackTo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }
        $statuses = User::$statuses;
        $roles = User::ROLES;
        $goBackTo = '/users';

        return view('users.create', compact('statuses', 'roles', 'goBackTo'));
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
        $teamId = Input::get('teamId');
        // Read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'userName'              => 'required',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => ''
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            // NB We are sharing the user form between create for team members and normal user create
            if (isset($teamId) && 0 < $teamId) {
                return redirect('team_members/create?id=' . $teamId)
                    ->withErrors($validator)
                    ->withInput(Input::except('password'));
            } else {
                return redirect('users/create')
                    ->withErrors($validator)
                    ->withInput(Input::except('password'));
            }
        } else {
            $user = new User();
            $user->status     = Input::get('status');
            $user->role       = Input::get('role');
            $user->userName   = Input::get('userName');
            $user->user_token = $user->getNewToken();
            // This generated email s guaranteed to be unique.  User can change it later.
            $user->email      = ($user->user_token . '@' . User::DOMIAN);
            $user->password   = Hash::make(Input::get('password'));
            $user->save();

            if (isset($teamId) && 0 < $teamId) {
                // This user was created for a team, add as a member to the team
                $teamMember = new TeamMember();
                $teamMember->teamId = $teamId;
                $teamMember->userId = $user->id;
                $teamMember->status = TeamMember::ACTIVE;
                $teamMember->save();

                Session::flash('flash_message', 'Successfully created a new user for team');
                Session::flash('flash_type', 'alert-success');
                return redirect("team_members?id={$teamId}");
            }

            Session::flash('flash_message', 'Successfully created a new user');
            Session::flash('flash_type', 'alert-success');
            return redirect('users');
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
        $user = User::where('id', $id)->first();
        $statuses = User::$statuses;
        $goBackTo = '/users';

        return view('users.show', compact('user', 'statuses', 'goBackTo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //dd('got-='.$id);
        $user = User::where('id', $id)->first();
        $statuses = User::$statuses;
        $roles = User::ROLES;
        $goBackTo = '/users';

        return view('users.edit', compact('user', 'statuses', 'roles', 'goBackTo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Read more on validation at http://laravel.com/docs/validation
        // User can change their email, but it needs to be unique
        $rules = array(
            'userName'              => 'required',
            'email'                 => 'required|email|unique:users,email'
        );
        $validator = Validator::make(Input::all(), $rules);

        $id = Input::get('id');
        if ($validator->fails()) {
            return redirect("users/$id/edit")
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            $user = User::find($id);
            if (!$user) {
                return redirect("users/$id/edit")
                    ->withErrors($validator)
                    ->withInput(Input::except('password'));
            }
            $user->status     = Input::get('status');
            $user->role       = (Input::get('role') ? : $user->role);
            $user->userName   = Input::get('userName');
            $user->email      = Input::get('email');
            $user->save();

            Session::flash('flash_message', 'Successfully updated user');
            Session::flash('flash_type', 'alert-success');
            return redirect('users');
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
        //
        // @TODO finish this off
        dd('not done yet');
        Session::flash('flash_message', 'User has been deleted');
        Session::flash('flash_type', 'alert-success');
    }
}
