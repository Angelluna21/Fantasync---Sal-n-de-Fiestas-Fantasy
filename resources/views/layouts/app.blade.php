<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FantaSync - Sistema de Gestión</title>

    @vite(['resources/css/fiori.css', 'resources/js/app.js'])

    @stack('styles')

    @livewireStyles
</head>
<body>
    
    <main class="container">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>