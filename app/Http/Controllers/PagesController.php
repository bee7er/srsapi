<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    /**
     * Create a new controller instance
     * Renders the various static pages for the application
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware('guest');
    }

    /**
     * Displays information relating to cookies
     *
     * @return \Illuminate\View\View
     */
    protected function cookies()
    {
        return view('pages/cookies');
    }


}
