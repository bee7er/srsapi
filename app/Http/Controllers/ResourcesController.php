<?php

namespace App\Http\Controllers;

use App\Resource;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ResourcesController extends Controller
{

    /**
     * Constructor
     */
    public function __construct()
    {
        // Make sure that the current user is logged in if they want to access
        // the create function
        $this->middleware('auth', ['only'=>'create']);
        // Could do the opposite, all excluded except one or more
        //$this->middleware('auth', ['except'=>'index']);
    }

    /**
     * Displays a list of resources
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Add a record
//        $obj = Resource::create([
//            'seq'=>1.0,
//            'name'=>'My image',
//            'description'=>'My special image',
//            'type'=>'IMAGE',
//            'url'=>'',
//            'status'=>'ACTIVE',
//            'image'=>'chalky.jpg',
//            'thumb'=>'chalky.jpg',
//            'created_at'=>'2015-10-20 21:30:13',
//            'updated_at'=>'2015-10-20 21:30:13'
//        ]);
//        $obj->save();

        $resources = Resource::all();

        // This would return json, as if for a basic API
//        return $resources;

        // Here we rely on a template to format the data
        return view('resources.index', compact('resources'));
    }

    /**
     * Shows a particular resource
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        //abort('404');
        //$resource = Resource::find($id);

        // To throw an exception
        //$resource = Resource::findOrFail($id);
        $resource = Resource::find($id);
        if ( ! $resource) {
            // I think findOPrFail is equivalent
            abort(404);     # @TODO Check what happens here and compare with the findOrFail
        }
        //dd($resource);
        return view('resources.show', compact('resource'));
    }

    /**
     * Create a new resource
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('resources.create');
    }
}
