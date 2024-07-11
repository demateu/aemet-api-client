
@extends('layouts.head')

@section('cuerpo')

<body>
    <!-- Navbar fijo -->
    <header class="sticky-top" id="navbar-header">
        {{-- Franja publicidad --}}
        @include('includes.header.publicidad')

        @include('includes.header.navbar') 
    </header> 

    <main class="mx-auto container-fluid p-0">
        {{-- test mensajes de error--}}

        <div>
            {{-- Mensajes de error cuando puede haber varios --}}
            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger text-center">
                    <ul class="m-0">
                        @foreach($errors->all() as $error)
                            <li class="text-center">{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- Mensajes para 1 solo error  --}}
            @if (session()->has('error'))
            <div class="alert alert-danger text-center">
                <span>
                    {{ session()->get('error') }}
                </span>
            </div>
            @endif
            {{-- Mensaje de success; solo debería de haber uno --}}
            @if (session('success'))
                <div class="alert alert-success text-center">
                    <span>
                        {{ session()->get('success') }}
                    </span>
                </div>
            @endif
            {{-- Mensaje general; solo debería de haber uno --}}
            @if (session('mensaje'))
            <div class="alert alert-primary text-center">
                <span>
                    {{ session()->get('mensaje') }}
                </span>
            </div>
            @endif
            @if (session()->has('message'))
            <div class="alert alert-danger text-center">
                {{ session()->get('message') }}
            </div>
            @endif
            {{-- DEFINIR EL NOMBRE DE LOS MENSAJES SEGUN EL COLOR DEL ALERT EN BOOTSTRAP --}}
            @if (session()->has('warning'))
            <div class="alert alert-warning text-center">
                {{ session()->get('warning') }}
            </div>
            @endif
            {{-- ... --}}
        </div>
        {{-- !test mensajes de error--}}

        @yield('content')

    </main>


    <!-- Enlaces CDN para Popper.js y su dependencia, Tippy.js -->
    {{-- si usamos bootstrap bundle ya incluye @popperjs
    <!--script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script-->
    <!--script src="https://cdn.jsdelivr.net/npm/tippy.js@6.3.3/dist/tippy-bundle.umd.min.js"></script-->
    --}}

    <!-- Bootstrap JS bundle (incluye Popper.js) -->
    <!--script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous" defer></script-->

    <!-- Scripts de Laravel Mix -->
    {{-- 
    <script src="{{ mix('/js/app.js') }}" defer></script><!--en teoria incluye dist/app.js -->
    <!--script src="{{ mix('dist/app.js') }}" defer></script-->
    --}}


    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script-->


    {{-- 
        despues en las vistas hijas se usa @push('scripts') 
        Aqui se cargaran todos lo que hay en @push('scripts') 
    --}}
    @stack('scripts')

</body>

@endsection

