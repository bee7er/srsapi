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
                        <div class="panel-heading">Team Membership</div>
                        <div class="panel-heading">Team: <span  style="color: #428bca;font-weight: bold;">{!! $user->first_name !!} {!! $user->surname !!}</span></div>
                        <div class="panel-body">

                            <table cellpadding="2" cellspacing="2" width="100%">
                                <tr>
                                    <th>Team Name</th>
                                    <th>Team Status</th>
                                    <th>User Membership Status</th>
                                </tr>

                                <?php
                                $background_color = $grey = '#d8d8d8';
                                $blue = '#a6ffff';
                                ?>
                                @if (0 < count($teams))
                                    @foreach ($teams as $team)
                                        <tr style="background-color: {!! $background_color !!};">
                                            <td class="">{{ $team->name }}</td>
                                            <td class="" style="text-align: center">{{ $team->teamStatus }}</td>
                                            <td class="" style="text-align: center">{{ $team->teamMemberStatus }}</td>
                                        </tr>
                                        <?php $background_color = ($background_color == $grey ? $blue: $grey); ?>
                                    @endforeach
                                @else
                                    <tr style="background-color: {!! $background_color !!};">
                                        <td style="color: #c40000;font-weight: bold;" colspan="3"><p>No team memberships found</p></td>
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
