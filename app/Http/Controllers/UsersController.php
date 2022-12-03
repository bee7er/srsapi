<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
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
        // Get all the current users
        $users = User::all()->sortBy(['surname','first_name']);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'first_name'            => 'required',
            'surname'               => 'required',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => ''
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect('users/create')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            $user = new User();
            $user->status     = (Input::get('status') ? 'active': 'inactive');
            $user->first_name = Input::get('first_name');
            $user->surname    = Input::get('surname');
            $user->email      = Input::get('email');
            $user->password   = Input::get('password');
            $user->save();

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

        return view('users.show', compact('user'));
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

        return view('users.edit', compact('user'));
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
        $rules = array(
            'first_name'            => 'required',
            'surname'               => 'required',
            'email'                 => 'required|email'
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
            $user->status     = (Input::get('status') ? 'active': 'inactive');
            $user->first_name = Input::get('first_name');
            $user->surname    = Input::get('surname');
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
        //
        // @TODO finish this off
        dd('not done yet');
        Session::flash('flash_message', 'User has been deleted');
        Session::flash('flash_type', 'alert-success');
    }
}
