
{{--*/ use App\Http\Controllers\Controller; /*--}}

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand @if ($menuOption == Controller::PAGE_AREA_HOME)shine @endif srs-title" href="/">Shared Render Service</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-left">
                @if ($loggedInUser)
                    <li class="@if ($menuOption == Controller::PAGE_AREA_RENDERS)active @endif srs-link">{!! link_to_action
                    ('RendersController@index', 'Renders', $parameters = [], $attributes = []) !!}</li>
                    @if (Auth::user()->isAdmin())
                        <li class="@if ($menuOption == Controller::PAGE_AREA_TEAMS)active @endif srs-link" srs-link>{!! link_to_action
                        ('TeamsController@index', 'Teams', $parameters = [], $attributes = []) !!}</li>
                        <li class="@if ($menuOption == Controller::PAGE_AREA_USERS)active @endif srs-link" srs-link>{!! link_to_action
                        ('UsersController@index', 'Users', $parameters = [], $attributes = []) !!}</li>
                    @endif
                @endif
                <li class="@if ($menuOption == Controller::PAGE_AREA_PAGES)active @endif srs-link">{!! link_to_action
                    ('PagesController@cookies', 'Cookies', $parameters = [], $attributes = []) !!}</li>
            </ul>
            <ul class="nav navbar-nav navbar-right srs-link">
                {{--LoggedInUser is needed on every page. Defined in a view composer.--}}
                @if (!$loggedInUser)<li><a href="{!! url('auth/login', $parameters = [], $attributes = [])
                !!}">Login</a></li>@endif
                @if ($loggedInUser)<li>{!! link_to_action('UsersController@show', (ucfirst($loggedInUser->first_name)), $parameters = ['id' => $loggedInUser->id], $attributes = []) !!}</li>@endif
                @if ($loggedInUser)<li><a href="{!! url('auth/logout', $parameters = [], $attributes = [])
                !!}">Logout</a></li>@endif
                {{--@if (!$loggedInUser)<li><a href="{!! url('auth/register', $parameters = [], $attributes = [])--}}
                {{--!!}">Register</a></li>@endif--}}
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
