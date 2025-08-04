@extends('layouts.app')

@section('cabecera')  @endsection

@section('estilos')
@endsection

@section('cuerpo')

<div class="container my-5">
    <div class="p-5 text-center bg-body-tertiary rounded-3">

        <h1 class="text-body-emphasis">Consulta del Estado de su Orden</h1>
        <p class="col-lg-8 mx-auto fs-5 text-muted">
            Ingrese el código de su orden para consultar el estado actual del trámite y revisar cualquier documento o archivo adjunto relacionado. Esta herramienta está diseñada para brindarle información actualizada y transparente sobre el proceso.
        </p>
        <div class="d-inline-flex gap-2 mb-5">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="inputNumeroDoc" class="col-form-label">Código</label>
                </div>
                <div class="col-auto">
                    <input type="text" id="inputNumeroDoc" class="form-control">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <th>OCAM</th> <!-- nro_orden -->
                        <th>ENTIDAD</th> <!-- nombre_entidad -->
                        <th>EMPRESA</th> <!-- nombre_empresa -->
                        <th>FECHA PUBLICACION</th> <!-- fecha_publicacion -->
                        <th>FECHA INICIO ENTREGA</th> <!-- inicio_entrega -->
                        <th>FECHA FIN ENTREGA</th> <!-- fecha_entrega -->
                        <th>FECHA DESPACHO</th> <!-- fecha_guia -->
                        <th>GUIA</th> <!-- guia -->
                        <th>FECHA ENTREGA REAL</th> <!-- fecha_entrega_real -->
                    </thead>  
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

@endsection