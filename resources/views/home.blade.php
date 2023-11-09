@extends('app')

@section('content')

    <div class="content">
        <h1>Welcome to the Shared Render Service</h1>
        <p>The <span class="" style="font-weight: bold;">Shared Render Service</span> is designed to help animators manage the rendering of their animations created in <span class="" style="font-weight: bold;">Cinema 4D</span>.</p>
        <p>A team can consist of one or more animators.</p>
        <p>If a team member has some spare capacity or a powerful machine they make themselves available to render their or the other team members' renders.</p>
        <p>Render requests are submitted across the web to a server which is managing all renders for the team.</p>
        <p>A given render request is divided into manageable chunks and distributed around the available team members.</p>

        <h2>Registration Plugin</h2>
        <p>A plugin running in the background of each C4D instance communicates with the remote server.  It performs the following operations:</p>
        <ul>
            <li><span class="" style="font-weight: bold;">Awake</span>: notifies the remote server that you are active</li>
            <li><span class="" style="font-weight: bold;">Available</span>: notifies the server that you are able to conduct renders in the background</li>
            <li><span class="" style="font-weight: bold;">Rendering</span>: notifies the server that you are currently rendering a chunk of frames</li>
            <li><span class="" style="font-weight: bold;">Complete</span>: notifies the server that the chunk of frames has been rendered and can be returned to the team member who submitted the render request</li>
        </ul>

        <h2>Render Submission Plugin</h2>
        <p>A plugin that enables the team member to submit one or more frames, or frame ranges to be rendered.</p>
        <p>The details provided are:</p>
        <ul>
            <li><span class="" style="font-weight: bold;">Project</span>: the name of the project with assets from which frames are to be rendered.</li>
            <li><span class="" style="font-weight: bold;">Whether to use project settings</span>:
                    <div>For the frame range to render</div>
                    <div>For the output format</div>
            </li>
            <li><span class="" style="font-weight: bold;">Whether to use custom settings</span>:
                    <div>For one or more individual frames to render</div>
                    <div>For one or more frame ranges to render</div>
            </li>
        </ul>

        <h2>Shared Render Service API</h2>
        <p>The SRS API receives render requests from the various team members originating from the Render Submission plugin.</p>
        <p>The API is responsible for chunking the set of frames to be rendered and allocating each chunk to an available team member.</p>
        <p>All communication from both the Registration and Render Submission plugins is via the API.  The API assesses the status of the team member clients and their renders and instructs them on what action to take.  Since the API only passes instructions across to clients the movement of files between the clients and the server API is initiated by the clients with pull requests.</p>
    </div>

@stop
