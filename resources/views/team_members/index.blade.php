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
                        <div class="panel-heading">Team: <span  style="color: #428bca;font-weight: bold;">{!! $team->teamName !!}</span></div>
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
                                            <td class="">{!! link_to_action('TeamMembersController@membership', $teamMember->userName, $parameters = ['userId' => $teamMember->userId], $attributes = []) !!}</td>
                                            <td class="" style="text-align: center;">{!! link_to_action('TeamMembersController@toggleTeamMemberStatus', $teamMember->teamMemberStatus, $parameters = ['teamMemberId' => $teamMember->teamMemberId, 'teamId' => $teamMember->teamId], $attributes = ['title' => 'toggle']) !!}</td>
                                            <td style="text-align: center;">
                                                {!! link_to_action('TeamMembersController@remove', 'Remove', $parameters = ['id' => $teamMember->teamMemberId, 'teamId' => $teamMember->teamId], $attributes = []) !!}
                                                &nbsp;-&nbsp;
                                                <a href='javascript:showUserTokens("{!! $teamMember->user_token !!}");'>API Tokens</a>
                                            </td>
                                        </tr>
                                        <tr style="background-color: #fff;display: none;" id="{!! $teamMember->user_token !!}" class="tokensRow">
                                            <td colspan="3">
                                                <div style="color: #c40000;">API connection details for '<b>{!! $teamMember->userName !!}</b>':</div>
                                                <table cellpadding="2" cellspacing="2" width="100%" style="">
                                                    <tr>
                                                        <td style=""><b>teamToken</b>&nbsp;=&nbsp;<b>{!! $team->token !!}</b><br /></td>
                                                    </tr>
                                                    <tr>
                                                        <td style=""><b>email</b>&nbsp;=&nbsp;<b>{!! $teamMember->email !!}</b><br /></td>
                                                    </tr>
                                                    <tr>
                                                        <td style=""><b>userToken</b>&nbsp;=&nbsp;<b>{!! $teamMember->user_token !!}</b><br /></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php $background_color = ($background_color == $grey ? $blue: $grey); ?>
                                    @endforeach
                                @else
                                    <tr style="background-color: {!! $background_color !!};">
                                        <td style="color: #c40000;font-weight: bold;font-size: 70%;" colspan="3"><p>No team members found</p></td>
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

<script>
    function showUserTokens(elemId) {
        if ($('#' + elemId).is(":visible")) {
            // Just hide it
            $("#" + elemId).hide(300);
        } else {
            // Close any open ones, and show it
            $(".tokensRow").hide(100);
            $("#" + elemId).show(300);
        }
    }
</script>