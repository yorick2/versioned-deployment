<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/projects') }}">Projects</a>
                    </li>
                @endguest
            </ul>
            @auth
                <div class="dropdown navbar-nav">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle pl-0" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav >
@auth
    <div class="container">
        <ol class="breadcrumb bg-transparent pl-0 pr-0 mb-0">
            <?php $segments = ''; ?>
            <?php $i=0; ?>
            <?php $requestSegments = Request::segments(); ?>
            <?php if(count($requestSegments) > 1) : ?>
                <?php foreach($requestSegments as $segment): ?>
                    <?php $segments .= '/'.$segment; ?>
                    <li>
                        <a class="text-dark" href="{{ url($segments) }}"><?php if($i!=0): ?>&nbsp;&gt;&nbsp;<?php endif; ?>{{$segment}}</a>
                    </li>
                    <?php $i++; ?>
                <?php endforeach ?>
            <?php endif; ?>
        </ol>
    </div>
@endauth