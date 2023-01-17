@extends('app')

@section('content')

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">User Profile</div>
                        <div class="panel-body">

                            {!! Form::open(['class' => 'form-horizontal']) !!}
                            {!! Form::hidden('status', $user->status) !!}

                            <div class="form-group">
                                {!! Form::label('id', 'User id', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('id', $user->id, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                            </div>

                            @if (Auth::user()->isAdmin())
                                <div class="form-group">
                                    {!! Form::label('role', 'Role', ['class' => 'col-md-4 control-label']) !!}
                                    {!! Form::text('role', $user->role, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                                </div>
                            @endif

                            <div class="form-group">
                                {!! Form::label('first_name', 'First name', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('first_name', $user->first_name, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('surname', 'Surname', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('surname', $user->surname, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('email', $user->email, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('api_token', 'API token', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('api_token', $user->api_token, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('status', 'Status', ['class' => 'col-md-4 control-label']) !!}
                                <select name="status" disabled="disabled">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ $user->status == $status ? 'selected="selected"' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                {!! link_to_action('UsersController@edit', 'Edit profile', $parameters = ['id' => $user->id], $attributes = ['class' => 'col-md-6 col-md-offset-4']) !!}
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
