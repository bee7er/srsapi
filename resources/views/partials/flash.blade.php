@if ( Session::has('flash_message') )

    <div class="alert {{ Session::get('flash_type') }}">
        <h4>{{ Session::get('flash_message') }}</h4>
    </div>

@endif