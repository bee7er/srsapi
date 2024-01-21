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

                            @if (Auth::user()->isAdmin() && Auth::user()->id != $user->id)
                                {{--Update role only for admin, and you can't remove admin role from  yourself--}}
                                <div class="form-group">
                                    {!! Form::label('role', 'Role', ['class' => 'col-md-4 control-label']) !!}
                                    <select name="role">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}" {{ $user->role == $role ? 'selected="selected"' : '' }}>
                                                {{ $role }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if (Auth::user()->isAdmin() && Auth::user()->id == $user->id)
                                <div class="form-group">
                                    {!! Form::label('role', 'Role', ['class' => 'col-md-4 control-label']) !!}
                                    {!! Form::text('role', $user->role, ['disabled' => 'disabled', 'class' => 'col-md-6']) !!}
                                </div>
                            @endif

                            <div class="form-group">
                                {!! Form::label('userName', 'User name', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('userName', $user->userName, ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('email', 'Email', ['class' => 'col-md-4 control-label']) !!}
                                {!! Form::text('email', $user->email, ['class' => 'col-md-6']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('status', 'Render Status', ['class' => 'col-md-4 control-label']) !!}
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
