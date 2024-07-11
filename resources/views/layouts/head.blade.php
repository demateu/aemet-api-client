<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Test timming sesion -->
        <meta http-equiv="refresh" content="{{ config('session.lifetime') * 60 }}">

        <title>{{ config('app.name', 'Aemet') }}</title>

        <!--favicon-->
        <link rel="icon" href="{{ asset('img/favicon/favicon.ico') }}"> 

        <!-- jquery -->
        <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script-->

        
        {{-- 
            Fonts  estan cargadas mediante Laravel mix
                <link rel="dns-prefetch" href="//fonts.gstatic.com">
                <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        --}}

        {{-- Bootstrap CSS --}}
        <!--link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css"-->
        
        {{--    Bootstrap Latest compiled and minified CSS --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        
        
        {{--
        <!-- Bootstrap Latest compiled JavaScript -->
        <!--script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous" defer></script-->   
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous" defer></script>
        --}}

        {{-- 
            Styles de Laravel Mix :
            Fuentes, Font Awesome Free 6.5.1, Select2, Bootstrap v5.3.2
        <!--link rel="stylesheet" href="{{ mix('/css/estilos_compilados.css') }}"-->
        --}}
        
        {{-- despues en las vistas hijas se usa @push('stylesheets') --}}
        @stack('stylesheets')
    </head>

    @yield('cuerpo')

    </html>
