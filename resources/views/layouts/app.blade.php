<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('cabecera') - Orden Track</title>
    <link rel="shortcut icon" href="{{ asset('img/logo.ico') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('estilos')

</head>
<body>
    <div>
        @include("layouts/header")

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <section>
                        <h1>@yield('cabecera')</h1>
                    </section>
                    <section>
                        @yield('cuerpo')
                    </section>
                </div>
            </div>
        </div>

        <footer class="footer"> 
            <div class="container text-center"> 
                <span class="text-body-secondary">Copyright &copy; 2025</span>
            </div> 
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
    @yield('scripts')

</body>
</html>