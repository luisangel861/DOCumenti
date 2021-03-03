<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DOCumenti') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">                            
                <a class="navbar-brand" href="{{ url('/') }}">                    
                    <!-- {{ config('app.name', 'DOCumenti') }} -->
                    <img src="{{asset('images/Logo_DOCUMENTI.png')}}" width="90" alt="" loading="lazy  ">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <!-- @yield('content') -->

            <div class="container">
              <form method="post" action="{{ route('registrar_lote') }}" enctype="multipart/form-data" accept-charset="UTF-8">
                {{csrf_field() }}
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nombre del lote</label>
                    <input type="text" class="form-control" name="nombre">
                    @if($errors->has('nombre'))
                        <label class="text-danger">{{ $errors->first('nombre') }}</label>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Número de lote</label>
                    <input class="form-control" name="numero">
                    @if($errors->has('numero'))
                        <label class="text-danger">{{ $errors->first('numero') }}</label>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Fecha de entrega</label>
                    <input type="date" class="form-control" name="fecha">
                    @if($errors->has('fecha'))
                        <label class="text-danger">{{ $errors->first('fecha') }}</label>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlFile1">Archivo</label>
                    <input type="file" class="form-control-file" name="archivo">
                    @if($errors->has('archivo'))
                        <label class="text-danger">{{ $errors->first('archivo') }}</label>
                    @endif
                  </div>
                  <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
