@extends('app')

@section('content')

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">List of Users</div>
                        <div class="panel-body">
                            <div class="">{!! link_to_action('UsersController@create', 'New User',
                            $parameters = [], $attributes = []) !!}</div>
                            @if ($users)
                                <ul>
                                    @foreach ($users as $user)
                                        <li>
                                            {!! link_to_action('UsersController@show', ($user->first_name . ' ' . $user->surname . '(' . $user->status . ')'), $parameters = ['id' => $user->id], $attributes = []) !!}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No users found</p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
