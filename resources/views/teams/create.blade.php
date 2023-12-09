@extends('app')

@section('content')

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">New Team</div>
                        <div class="panel-body">

                            {!! Form::open(['url' => 'teams', 'class' => 'form-horizontal']) !!}

                            <div class="form-group">
                                {!! Form::label('name', 'Name', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('name', null, ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('description', 'Description', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('description', null, ['class' => 'col-md-6']) !!}
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
