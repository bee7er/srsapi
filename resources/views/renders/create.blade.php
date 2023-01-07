@extends('app')

@section('content')

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">New Render Profile</div>
                        <div class="panel-body">

                            {!! Form::open(['url' => 'renders', 'class' => 'form-horizontal']) !!}

                            <div class="form-group">
                                {!! Form::label('first_name', 'First name', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('first_name', null, ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('surname', 'Surname', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('surname', null, ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('email', null, ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('password', 'Password', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::password('password', ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('password_confirmation', 'Confirm password', ['class' => 'col-md-4
                                control-label']) !!}
                                {!! Form::password('password_confirmation', ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('status', 'Status', ['class' => 'col-md-4 control-label']) !!}
                                <select name="status">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}">
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                {!! Form::submit('Submit', ['class' => 'col-md-6 col-md-offset-4 btn btn-primary']) !!}
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
