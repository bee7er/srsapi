<?php

namespace App\Http\Controllers;

use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance
     *
     * @return void
     */
    public function __construct() {

        // Illuminate/Support::helpers.php  is where dd() is defined, plus lots of others
    }

    /**
     * Manages the home page
     *
     * @return \Illuminate\View\View
     */
    protected function index()
    {
        flash()->success('You have come home');

        return view('home');
    }
}
