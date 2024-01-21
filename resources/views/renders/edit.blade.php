@extends('app')

@section('content')

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Edit Render Profile</div>
                        <div class="panel-body">

                            {!! Form::open(['route' => 'renders.update', 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            {!! Form::hidden('id', $render->id) !!}

                            <div class="form-group">
                                {!! Form::label('userName', 'User name', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('userName', $render->userName, ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('email', $render->email, ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('status', 'Status', ['class' => 'col-md-4 control-label']) !!}
                                <select name="status">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ $render->status == $status ? 'selected="selected"' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                {!! Form::submit('Delete', ['class' => 'col-md-2 col-md-offset-4 btn btn-primary']) !!}

                                {!! Form::submit('Update', ['class' => 'col-md-2 col-md-offset-2 btn btn-primary']) !!}
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
