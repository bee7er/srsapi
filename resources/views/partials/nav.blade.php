
{{--*/ use App\Http\Controllers\Controller; /*--}}

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation:</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand @if ($menuOption == Controller::PAGE_AREA_HOME)shine @endif" href="/">Bee</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-left">
                @if ($loggedInUser)
                    {{--<li>{!! link_to('resources', 'Res1', $attributes = [], $secure = null) !!}</li>--}}
                    <li class="@if ($menuOption == Controller::PAGE_AREA_RESOURCES)active @endif">{!! link_to_action('ResourcesController@index', 'Resources', $parameters = [], $attributes = []) !!}</li>
                    {{--<li>{!! link_to_route('resources_route', 'Res3', $parameters = [], $attributes = []) !!}</li>--}}
                    {{--<li><a href="{!! url('resources', $parameters = [], $secure = null) !!}">Res4</a></li>--}}
                    {{--<li><a href="{!! route('resources_route', $params = []) !!}">Res5</a></li>--}}
                    {{--<li><a href="{!! action('ResourcesController@index', $params = []) !!}">Res6</a></li>--}}
                    {{--<li><a href="/resources">Res7</a></li>--}}
                    <li class="@if ($menuOption == Controller::PAGE_AREA_USERS)active @endif">{!! link_to_action
                    ('UsersController@index', 'Users', $parameters = [], $attributes = []) !!}</li>
                @endif
                    <li class="@if ($menuOption == Controller::PAGE_AREA_CONTACT)active @endif">{!! link_to_action
                    ('ContactController@index', 'Contact', $parameters = [], $attributes = []) !!}</li>
                    <li class="@if ($menuOption == Controller::PAGE_AREA_PAGES)active @endif"><a
                                href="cookies">Cookies</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                {{--LoggedInUser is needed on every page. Defined in a view composer.--}}
                @if (!$loggedInUser)<li><a href="{!! url('auth/login', $parameters = [], $attributes = [])
                !!}">Login</a></li>@endif
                @if ($loggedInUser)<li>{!! link_to_action('UsersController@show', ('Hej ' .ucfirst($loggedInUser->first_name)), $parameters = ['id' => $loggedInUser->id], $attributes = []) !!}</li>@endif
                @if ($loggedInUser)<li><a href="{!! url('auth/logout', $parameters = [], $attributes = [])
                !!}">Logout</a></li>@endif
                @if (!$loggedInUser)<li><a href="{!! url('auth/register', $parameters = [], $attributes = [])
                !!}">Register</a></li>@endif
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
