@extends('app')

@section('content')

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">List of Users</div>
                        <div class="panel-body">
                            <div class="">{!! link_to_action('UsersController@create', 'New User', $parameters = [], $attributes = []) !!}</div>

                            <table cellpadding="2" cellspacing="2" width="100%">
                                <tr>
                                    <th>Name</th>
                                    <th>Render Status</th>
                                    <th>Action</th>
                                </tr>

                                <?php
                                $background_color = $grey = '#d8d8d8';
                                $blue = '#a6ffff';
                                ?>
                                @if (0 < count($users))
                                    @foreach ($users as $user)
                                        <tr style="background-color: {!! $background_color !!};">
                                            <td class="">{{ $user->userName  }}</td>
                                            <td class="{!! $user->status !!}">{!! $user->status !!}</td>
                                            <td style="text-align: center;">
                                                {!! link_to_action('TeamMembersController@membership', 'team membership', $parameters = ['userId' => $user->id], $attributes = []) !!}
                                                -
                                                {!! link_to_action('UsersController@show', 'details', $parameters = ['id' => $user->id], $attributes = []) !!}
                                            </td>
                                        </tr>
                                        <?php $background_color = ($background_color == $grey ? $blue: $grey); ?>
                                    @endforeach
                                @else
                                    <tr style="background-color: {!! $background_color !!};">
                                        <td style="color: #c40000;font-weight: bold;font-size: 70%;" colspan="3"><p>No users found</p></td>
                                    </tr>
                                @endif
                            </table>

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
