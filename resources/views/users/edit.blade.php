@extends('app')

@section('content')

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Edit User Profile</div>
                        <div class="panel-body">

                            {!! Form::open(['route' => 'users.update', 'method' => 'put', 'class' => 'form-horizontal']) !!}
                            {!! Form::hidden('id', $user->id) !!}

                            <div class="form-group">
                                {!! Form::label('first_name', 'First name', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('first_name', $user->first_name, ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('surname', 'Surname', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('surname', $user->surname, ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('email', $user->email, ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('status', 'Status', ['class' => 'col-md-4 control-label']) !!}
                                <select name="status">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ $user->status == $status ? 'selected="selected"' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                {{--Delete only for admin, and you can't delete yourself--}}
                                @if (Auth::user()->isAdmin() && Auth::user()->id != $user->id)
                                    {!! Form::submit('Delete', ['class' => 'col-md-2 col-md-offset-4 btn btn-primary']) !!}
                                @endif

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
