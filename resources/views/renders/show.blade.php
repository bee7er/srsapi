@extends('app')

@section('content')

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Render Profile</div>
                        <div class="panel-body">

                            {!! Form::open(['class' => 'form-horizontal']) !!}
                            {!! Form::hidden('status', $render->status) !!}

                            <div class="form-group">
                                {!! Form::label('id', 'Render id', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('id', $render->id, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('userName', 'User name', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('userName', $render->userName, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('email', $render->email, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('status', 'Status', ['class' => 'col-md-4 control-label']) !!}
                                <select name="status" disabled="disabled">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ $render->status == $status ? 'selected="selected"' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                {!! link_to_action('RendersController@edit', 'Edit profile', $parameters = ['id' => $render->id], $attributes = ['class' => 'col-md-6 col-md-offset-4']) !!}
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
