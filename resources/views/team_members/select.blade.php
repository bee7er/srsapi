<?php
use App\Team;
use App\TeamMember;
?>

@extends('app')

@section('content')

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Select Team Members</div>
                        <div class="panel-heading">Team: <span  style="color: #428bca;font-weight: bold;">{!! $team->name !!}</span></div>
                        <div class="panel-body">

                            <table cellpadding="2" cellspacing="2" width="100%">
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>

                                <?php
                                $background_color = $grey = '#d8d8d8';
                                $blue = '#a6ffff';
                                ?>
                                @if (0 < count($users))
                                    @foreach ($users as $user)
                                        <tr style="background-color: {!! $background_color !!};">
                                            <td class="">{{ $user->first_name }} {{ $user->surname  }}</td>
                                            <td style="text-align: center;">
                                                {!! link_to_action('TeamMembersController@add', 'Add to Team', $parameters = ['id' => $user->id, 'teamId' => $teamId], $attributes = []) !!}
                                            </td>
                                        </tr>
                                        <?php $background_color = ($background_color == $grey ? $blue: $grey); ?>
                                    @endforeach
                                @else
                                    <tr style="background-color: {!! $background_color !!};">
                                        <td style="color: #c40000;font-weight: bold;" colspan="3"><p>No team members found</p></td>
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
