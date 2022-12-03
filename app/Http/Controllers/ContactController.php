<?php

namespace App\Http\Controllers;

class ContactController extends Controller
{
    /*
     * Contact Controller
     *
     * Renders the contact page for the application.
     *
     */

    /**
     * Create a new controller instance
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware('guest');     # Would configure to only allow guests
    }

    /**
     * Manages the contact page
     *
     * @return \Illuminate\View\View
     */
    protected function index()
    {
        $email = '<span style="color:#c40000;">betheridge@gmail.com</span>';

        return view('contact.index', compact('email'));
    }
}
