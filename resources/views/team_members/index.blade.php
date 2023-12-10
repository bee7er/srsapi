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
                        <div class="panel-heading">List of Team Members</div>
                        <div class="panel-heading">Team: <span  style="color: #428bca;font-weight: bold;">{!! $team->name !!}</span></div>
                        <div class="panel-heading">Status: <span  style="color: #428bca;font-weight: bold;">{!! $team->status !!}</span></div>
                        <div class="panel-body">
                            <div class="">{!! link_to_action('TeamMembersController@create', 'New Team Member', $parameters = ['id' => $teamId], $attributes = []) !!}</div>

                            <table cellpadding="2" cellspacing="2" width="100%">
                                <tr>
                                    <th>Name</th>
                                    <th>Member Status</th>
                                    <th>Action</th>
                                </tr>

                                <?php
                                $background_color = $grey = '#d8d8d8';
                                $blue = '#a6ffff';
                                ?>
                                @if (0 < count($teamMembers))
                                    @foreach ($teamMembers as $teamMember)
                                        <tr style="background-color: {!! $background_color !!};">
                                            <td class="">{!! link_to_action('TeamMembersController@membership', $teamMember->first_name . ' ' . $teamMember->surname, $parameters = ['userId' => $teamMember->userId], $attributes = []) !!}</td>
                                            <td class="" style="text-align: center;">{!! link_to_action('TeamMembersController@toggleTeamMemberStatus', $teamMember->teamMemberStatus, $parameters = ['teamMemberId' => $teamMember->teamMemberId, 'teamId' => $teamMember->teamId], $attributes = ['title' => 'toggle']) !!}</td>
                                            <td style="text-align: center;">
                                                {!! link_to_action('TeamMembersController@remove', 'Remove', $parameters = ['id' => $teamMember->teamMemberId, 'teamId' => $teamMember->teamId], $attributes = []) !!}
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

                            <br />
                            {!! link_to_action('TeamMembersController@select', 'Select Member', $parameters = ['teamId' => $teamId], $attributes = []) !!}

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
