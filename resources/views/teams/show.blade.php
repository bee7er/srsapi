@extends('app')

@section('content')

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Team</div>
                        <div class="panel-body">

                            {!! Form::open(['class' => 'form-horizontal']) !!}
                            {!! Form::hidden('status', $team->status) !!}

                            <div class="form-group">
                                {!! Form::label('id', 'Team id', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('id', $team->id, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('name', 'Name', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('name', $team->name, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('description', 'Description', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('description', $team->description, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('status', 'Status', ['class' => 'col-md-4 control-label']) !!}
                                <select name="status" disabled="disabled">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ $team->status == $status ? 'selected="selected"' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                {!! link_to_action('TeamsController@edit', 'Edit Team', $parameters = ['id' => $team->id], $attributes = ['class' => 'col-md-6 col-md-offset-4']) !!}
                            </div>

                            {!! Form::close() !!}
                        </div>
                        <div class="panel-heading">
                            <script>
                                document.write('<a href="{{ $goBackTo }}"><- Go back</a>');
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
