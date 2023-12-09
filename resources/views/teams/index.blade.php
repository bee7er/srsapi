<?php
use App\Team;
?>

@extends('app')

@section('content')

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">List of Teams</div>
                        <div class="panel-body">
                            <div class="">{!! link_to_action('TeamsController@create', 'New Team', $parameters = [], $attributes = []) !!}</div>

                            <table cellpadding="2" cellspacing="2" width="100%">
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>

                                <?php
                                $background_color = $grey = '#d8d8d8';
                                $blue = '#a6ffff';
                                ?>
                                @if (0 < count($teams))
                                    @foreach ($teams as $team)
                                        <tr style="background-color: {!! $background_color !!};">
                                            <td class="">{!! $team->name !!}</td>
                                            <td class="">{!! $team->status !!}</td>
                                            <td style="text-align: center;">
                                                {!! link_to_action('TeamMembersController@index', 'Team Members', $parameters = ['id' => $team->id], $attributes = []) !!}
                                                -
                                                {!! link_to_action('TeamsController@show', 'Details', $parameters = ['id' => $team->id], $attributes = []) !!}
                                            </td>
                                        </tr>
                                        <?php $background_color = ($background_color == $grey ? $blue: $grey); ?>
                                    @endforeach
                                @else
                                    <tr style="background-color: {!! $background_color !!};">
                                        <td style="color: #c40000;font-weight: bold;" colspan="3"><p>No teams found</p></td>
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
