@extends('app')

@section('content')

    <?php
    use App\RenderDetail;
    use Illuminate\Support\Str;
    ?>

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">List of Renders</div>
                        <div style="text-align: center">
                            @if ($users)
                                {!! Form::open(['route' => 'renders.index', 'method' => 'get', 'class' => 'form-horizontal']) !!}

                                <div class="form-group" style="margin-top: 5px;">
                                    {!! Form::label('selectedUserId', 'Select user: ', ['class' => 'col-md-4 control-label srs-label']) !!}
                                    <select id="selectedUserId" name="selectedUserId" class="col-md-4" onchange="this.form.submit()">
                                        @if (Auth::user()->isAdmin())
                                            <option value="0" @if (0 == $selectedUserId) selected @endif>All</option>
                                        @endif
                                        @foreach ($users as $optionUser)
                                            <option value="{!! $optionUser->id !!}" @if ($optionUser->id == $selectedUserId) selected @endif>{!! $optionUser->userName !!}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('includeReturned', 'Include returned renders: ', ['class' => 'col-md-4
                                    control-label srs-label']) !!}
                                    <input type="checkbox" name="includeReturned" id="includeReturned" class="col-md-1" value="1" @if (isset($includeReturned)) checked="checked" @endif onchange="this.form.submit()" />
                                </div>

                                <div class="form-group">
                                    {!! Form::submit('Refresh', ['class' => 'col-md-2 col-md-offset-4 btn btn-primary']) !!}
                                </div>

                                {!! Form::close() !!}
                            @else
                                <div>No users found</div>
                            @endif
                        </div>
                        <div class="panel-body">

                                <table cellpadding="2" cellspacing="2">
                                    <tr>
                                        <th colspan="5" style="font-size: 16px;">Renders Submitted by @if (isset($user)) {!! $user->getName() !!} @else 'All users' @endif</th>
                                    </tr>
                                    <tr>
                                        <th>Render Id</th>
                                        <th>Project</th>
                                        <th>Render Sts</th>
                                        <th>Chunk</th>
                                        <th>Chunk Sts</th>
                                        <th>Allocated to</th>
                                        <th>Submitted at</th>
                                        <th>Completed at</th>
                                        <th>Action</th>
                                    </tr>

                                    <?php
                                    $color = $grey = '#d8d8d8';
                                    $blue = '#a6ffff';
                                    ?>
                                    @if ($submissions)
                                        @foreach ($submissions as $render)
                                            <tr style="background-color: {!! $color !!};">
                                                <td class="srs-id">{!! $render->render_id !!}</td>
                                                <td class="">{!! $render->c4dProjectWithAssets !!}</td>
                                                <td class="{!! $render->render_status !!}">{!! $render->render_status !!}</td>
                                                <td class="">{!! $render->from !!}-{!! $render->to !!}</td>
                                                <td class="{!! $render->detail_status !!}">{!! $render->detail_status !!}</td>
                                                <td>{!! $render->userName !!}</td>
                                                <td>{!! date('d/m/Y H:i', strtotime($render->created_at)) !!}</td>
                                                <td>{!! ($render->completed_at ? date('d/m/Y H:i', strtotime($render->completed_at)): '') !!}</td>
                                                <td style="text-align: center;" title="Render detail id: {!! $render->render_detail_id !!}">
                                                    @if ($render->detail_status == RenderDetail::ALLOCATED)
                                                    {!! link_to_action('RendersController@reassign', 'Reassign', $parameters = ['render_detail_id' => $render->render_detail_id], $attributes = []) !!}
                                                    @elseif ($render->detail_status == RenderDetail::READY)
                                                        {!! link_to_action('RendersController@cancel', 'Cancel', $parameters = ['render_detail_id' => $render->render_detail_id], $attributes = []) !!}
                                                    @else
                                                        <span title="Render detail id: {!! $render->render_detail_id !!}">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <?php $color = ($color == $grey ? $blue: $grey); ?>
                                        @endforeach
                                    @else
                                        <tr style="background-color: {!! $color !!};">
                                            <td style="color: #c40000;font-weight: bold;font-size: 70%;" colspan="9"><p>No renders found</p></td>
                                        </tr>
                                    @endif
                                </table>
                            <br>
                                <table cellpadding="2" cellspacing="2">
                                    <tr>
                                        <th colspan="5" style="font-size: 16px;">Renders Allocated to @if (isset($user)) {!! $user->getName() !!} @else 'All users' @endif</th>
                                    </tr>
                                    <tr>
                                        <th>Render Id</th>
                                        <th>Project</th>
                                        <th>Render Sts</th>
                                        <th>Chunk</th>
                                        <th>Chunk Sts</th>
                                        <th>Submitted by</th>
                                        <th>Submitted at</th>
                                        <th>Completed at</th>
                                        <th>Action</th>
                                    </tr>

                                    <?php
                                    $color = $grey = '#d8d8d8';
                                    $blue = '#a6ffff';
                                    ?>
                                    @if ($renders)
                                        @foreach ($renders as $render)
                                            <tr style="background-color: {!! $color !!};">
                                                <td class="srs-id">{!! $render->render_id !!}</td>
                                                <td class="">{!! $render->c4dProjectWithAssets !!}</td>
                                                <td class="{!! $render->render_status !!}">{!! $render->render_status !!}</td>
                                                <td class="">{!! $render->from !!}-{!! $render->to !!}</td>
                                                <td class="{!! $render->detail_status !!}">{!! $render->detail_status !!}</td>
                                                <td>{!! $render->userName !!}</td>
                                                <td>{!! date('d/m/Y H:i', strtotime($render->created_at)) !!}</td>
                                                <td>{!! ($render->completed_at ? date('d/m/Y H:i', strtotime($render->completed_at)): '') !!}</td>
                                                <td style="text-align: center;" title="Render detail id: {!! $render->render_detail_id !!}">
                                                    @if ($render->detail_status == RenderDetail::ALLOCATED)
                                                        {!! link_to_action('RendersController@reassign', 'Reassign', $parameters = ['render_detail_id' => $render->render_detail_id], $attributes = []) !!}
                                                    @elseif ($render->detail_status == RenderDetail::READY)
                                                        {!! link_to_action('RendersController@cancel', 'Cancel', $parameters = ['render_detail_id' => $render->render_detail_id], $attributes = []) !!}
                                                    @else
                                                        <span title="Render detail id: {!! $render->render_detail_id !!}">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                                <?php $color = ($color == $grey ? $blue: $grey); ?>
                                        @endforeach
                                    @else
                                        <tr style="background-color: {!! $color !!};">
                                            <td style="color: #c40000;font-weight: bold;font-size: 70%;" colspan="9"><p>No renders found</p></td>
                                        </tr>
                                    @endif
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
