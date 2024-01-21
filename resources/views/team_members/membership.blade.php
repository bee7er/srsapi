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
                        <div class="panel-heading">User name: <span  style="color: #428bca;font-weight: bold;">{!! $user->userName !!}</span></div>
                        <div class="panel-body">

                            <table cellpadding="2" cellspacing="2" width="100%">
                                <tr>
                                    <th>Team Name</th>
                                    <th>Team Status</th>
                                    <th>Member Status</th>
                                </tr>

                                <?php
                                $background_color = $grey = '#d8d8d8';
                                $blue = '#a6ffff';
                                ?>
                                @if (0 < count($teams))
                                    @foreach ($teams as $team)
                                        <tr style="background-color: {!! $background_color !!};">
                                            <td class="">{!! link_to_action('TeamMembersController@index', $team->teamName, $parameters = ['id' => $team->teamId], $attributes = []) !!}</td>
                                            <td class="" style="text-align: center">{{ $team->teamStatus }}</td>
                                            <td class="" style="text-align: center">{!! link_to_action('TeamMembersController@toggleMembershipStatus', $team->teamMemberStatus, $parameters = ['teamMemberId' => $team->teamMemberId, 'teamId' => $team->teamId], $attributes = ['title' => 'toggle']) !!} &nbsp;-&nbsp;
                                                <a href='javascript:showOtherMembers("{!! $team->teamId !!}");'>other members</a></td>
                                        </tr>

                                        <tr style="background-color: #fff;display: none;" id="{!! $team->teamId !!}" class="otherMembersRow">
                                            <td colspan="3">
                                            <table cellpadding="2" cellspacing="2" width="70%" style="float: right;" border="1">
                                                <tr>
                                                    <th>Member Name</th>
                                                    <th>Action</th>
                                                </tr>

                                                @if (0 < count($team->otherTeamMembers))
                                                    @foreach ($team->otherTeamMembers as $teamMember)
                                                        <tr style="background-color: #fff;">
                                                            <td class="" style="text-align: left">{{ $teamMember->userName }}</td>
                                                            <td class="" style="text-align: center">{!! link_to_action('TeamMembersController@toggleBlockedUserStatus', ($teamMember->isBlocked ? 'unblock':'block'), $parameters = ['userId' => $user->id, 'teamId' => $team->teamId, 'blockedUserId' => $teamMember->userId], $attributes = ['title' => 'toggle']) !!}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr style="background-color: #fff;" class="">
                                                        <td style="color: #c40000;font-weight: bold;font-size: 70%;" colspan="2"><p>No other team members found</p></td>
                                                    </tr>
                                                @endif
                                            </table>
                                            </td>
                                        </tr>

                                        <?php $background_color = ($background_color == $grey ? $blue: $grey); ?>
                                    @endforeach
                                @else
                                    <tr style="background-color: {!! $background_color !!};">
                                        <td style="color: #c40000;font-weight: bold;font-size: 70%;" colspan="3"><p>No team memberships found</p></td>
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

<script>
    function showOtherMembers(elemId) {
        if ($('#' + elemId).is(":visible")) {
            // Just hide it
            $("#" + elemId).hide(300);
        } else {
            // Close any open ones, and show it
            $(".otherMembersRow").hide(100);
            $("#" + elemId).show(300);
        }
    }
</script>