@extends('layouts.app')

@section('cabecera') 

@section('estilos')
@endsection

@section('cuerpo')

<div class="container my-5">
    <div class="p-5 text-center bg-body-tertiary rounded-3">
        <h1 class="text-body-emphasis">Consulta del Estado de su Orden</h1>
        <p class="col-lg-8 mx-auto fs-5 text-muted">
            Ingrese el código de su orden para consultar el estado actual del trámite y revisar cualquier documento o archivo adjunto relacionado. Esta herramienta está diseñada para brindarle información actualizada y transparente sobre el proceso.
        </p>

        {{-- Formulario de búsqueda --}}
        <form method="GET" action="{{ route('home') }}">
            <div class="d-inline-flex gap-2 mb-5">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="inputNumeroDoc" class="col-form-label">Código</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" name="codigo" id="inputNumeroDoc" class="form-control" value="{{ request('codigo') }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Tabla con headers --}}
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>OCAM</th>
                            <th>ENTIDAD</th>
                            <th>EMPRESA</th>
                            <th>FECHA PUBLICACION</th>
                            <th>FECHA INICIO ENTREGA</th>
                            <th>FECHA FIN ENTREGA</th>
                            <th>FECHA DESPACHO</th>
                            <th>GUIA</th>
                            <th>FECHA ENTREGA REAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($ordenes) && count($ordenes) > 0)
                            @foreach ($ordenes as $orden)
                                <tr>
                                    <td>{{ $orden['nro_orden'] }}</td>
                                    <td>{{ $orden['nombre_entidad'] }}</td>
                                    <td>{{ $orden['nombre_empresa'] }}</td>
                                    <td>{{ $orden['fecha_publicacion'] }}</td>
                                    <td>{{ $orden['inicio_entrega'] }}</td>
                                    <td>{{ $orden['fecha_entrega'] }}</td>
                                    <td>{{ $orden['fecha_guia'] }}</td>
                                    <td class="text-center">
                                        @if ($orden->guia)
                                            <a href="{{ route('descargar-guia', $orden->id)  }}"  title="Descargar guía">
                                                <i class="fa-solid fa-file-pdf fa-lg text-danger"></i>
                                            </a>
                                        @else
                                            <i class="fa-solid fa-file-pdf fa-lg text-secondary" title="Guía no disponible"></i>
                                        @endif
                                    </td>
                                    <td>{{ $orden['fecha_entrega_real'] }}</td>
                                </tr>
                            @endforeach
                        @elseif(request('codigo'))
                            <tr>
                                <td colspan="9" class="text-center">
                                    No se encontraron resultados para el código: <strong>{{ request('codigo') }}</strong>.
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    Ingrese un código y presione "Buscar" para ver resultados.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
@endsection
