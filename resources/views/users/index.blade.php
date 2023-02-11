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
                                <?php
                                $color = $grey = '#d8d8d8';
                                $blue = '#a6ffff';
                                ?>
                                <ul>
                                    @foreach ($users as $user)
                                        <li style="background-color: {!! $color !!};">
                                            {!! link_to_action('UsersController@show', ($user->first_name . ' ' . $user->surname . '(' . $user->status . ')'), $parameters = ['id' => $user->id], $attributes = []) !!}
                                        </li>
                                        <?php $color = ($color == $grey ? $blue: $grey); ?>
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
