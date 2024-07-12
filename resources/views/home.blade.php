@extends('layouts.app')

@section('content')

<div class="container">

    <section id="titulo-descripcion" class="row seccion-titulo-descripcion py-4">
        <div class="col-12 text-center">
            <h1 class="titulo centrar-texto h2">
                ¡El tiempo de nuestra comunidad!
            </h1>
            <p class="descripcion-titulo centrar-texto" style="color: #646F79;">
                Heave this scurvy copyfiller fer yar next adventure and cajol 
                yar clients into walking the plank with evry layout! 
            </p>
        </div>
    </section>

    <section id="buscador-ciudades" class="row">
        <div class="col d-flex justify-content-center">
            <form action="{{ route('meteo.municipio.show') }}" method="GET" class="row g-3" id="selector-municipios">
                @csrf

                {{-- carregar el select amb les ciutats del api --}}
                <select form="selector-municipios" class="col" name="municipio" id="municipio" required>
                    <option value="">Selecciona el Municipio</option>
                    @foreach ($municipios as $municipio)
                        <option value="{{ $municipio->id_old }}">{{ $municipio->nombre }} (id_old: {{ $municipio->id_old }})</option>
                    @endforeach
                </select>
            
                <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-dark" form="selector-municipios">Confirmar</button>
                </div>
            </form>
        </div>
    </section>

    {{-- para seguimiento durante el desarrollo --}}
    @foreach ($municipios as $municipio)
        {{ $municipio }}
        <hr>
    @endforeach
    {{-- /para seguimiento durante el desarrollo --}}

</div>


@endsection
