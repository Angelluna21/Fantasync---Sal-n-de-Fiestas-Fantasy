<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido | FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/welcome.css'])
</head>
<body class="welcome-page">
    <!-- Fondo mágico de estrellas en pantalla completa -->
    <figure class="welcome-background" aria-hidden="true"></figure>

    <main class="welcome-main">
        <header class="welcome-header">
            <!-- Logo principal flotante -->
            <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="welcome-logo">
            
            <hgroup>
                <h1 class="welcome-title">La magia de tus eventos comienza aquí</h1>
                <p class="welcome-subtitle">Bienvenido al sistema de gestión integral de Operadora de Fiestas Fantasy. Organiza salones, menús y contratos en un entorno diseñado para crear experiencias inolvidables.</p>
            </hgroup>
        </header>

        <nav class="welcome-actions" aria-label="Navegación principal">
            <!-- Lógica de Laravel: Si ya inició sesión lo manda al panel, si no, al login -->
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary">Ir al Panel de Control</a>
            @else
                <a href="{{ route('login') }}" class="btn-primary">Iniciar</a>
            @endauth
        </nav>
    </main>

    <footer class="welcome-footer">
        <p>&copy; {{ date('Y') }} Operadora de Fiestas Fantasy. Todos los derechos reservados.</p>
    </footer>
</body>
</html>