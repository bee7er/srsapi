@extends('app')

@section('content')

    <?php
    use App\RenderDetail;
    ?>

    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">List of Renders</div>
                        <div class="panel-body">
                                <table cellpadding="2" cellspacing="2">
                                    <tr>
                                        <th colspan="5">Renders Submitted by You</th>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <th>Allocated to</th>
                                        <th>Submitted</th>
                                        <th>Completed</th>
                                        <th>Action</th>
                                    </tr>

                                    @if ($submissions)
                                        @foreach ($submissions as $render)
                                            <tr>
                                                <td class="{!! $render->status !!}">{!! $render->status !!}</td>
                                                <td>{!! $render->first_name !!} {!! $render->surname !!}</td>
                                                <td>{!! date('d/m/Y H:i', strtotime($render->created_at)) !!}</td>
                                                <td>{!! date('d/m/Y H:i', strtotime($render->completed_at)) !!}</td>
                                                <td title="Render detail id: {!! $render->render_detail_id !!}">
                                                    @if ($render->status == RenderDetail::ALLOCATED)
                                                    {!! link_to_action('RendersController@reassign', 'Reassign', $parameters = ['render_detail_id' => $render->render_detail_id], $attributes = []) !!}
                                                    @else
                                                        <span title="Render detail id: {!! $render->render_detail_id !!}">None</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5"><p>No renders found</p></td>
                                        </tr>
                                    @endif
                                </table>
                            <br>
                                <table cellpadding="2" cellspacing="2">
                                    <tr>
                                        <th colspan="5">Renders Allocated to You</th>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <th>Submitted by</th>
                                        <th>Submitted</th>
                                        <th>Completed</th>
                                        <th>Action</th>
                                    </tr>

                                    @if ($renders)
                                        @foreach ($renders as $render)
                                            <tr>
                                                <td class="{!! $render->status !!}">{!! $render->status !!}</td>
                                                <td>{!! $render->first_name !!} {!! $render->surname !!}</td>
                                                <td>{!! date('d/m/Y H:i', strtotime($render->created_at)) !!}</td>
                                                <td>{!! date('d/m/Y H:i', strtotime($render->completed_at)) !!}</td>
                                                <td title="Render detail id: {!! $render->render_detail_id !!}">
                                                    @if ($render->status == RenderDetail::ALLOCATED)
                                                        {!! link_to_action('RendersController@reassign', 'Reassign', $parameters = ['render_detail_id' => $render->render_detail_id], $attributes = []) !!}
                                                    @else
                                                        <span title="Render detail id: {!! $render->render_detail_id !!}">None</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5"><p>No renders found</p></td>
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
