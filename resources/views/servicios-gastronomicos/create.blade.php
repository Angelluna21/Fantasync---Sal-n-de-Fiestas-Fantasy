<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Servicio · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/platillos.css'])
</head>

<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>

    <main class="dashboard-layout">
        <nav class="top-nav" aria-label="Menú superior">
            <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>
            <x-user-menu />
        </nav>

        <nav aria-label="Navegación de retorno" class="platillos-section">
            <a href="{{ route('servicios-gastronomicos.index') }}" class="btn-back-nav">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Servicios
            </a>
        </nav>

        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">Administración de Menú</p>
                <h1 class="dashboard-title">Crear Servicio</h1>
                <p class="dashboard-description">Dale un nombre al nuevo tipo de servicio gastronómico.</p>
            </hgroup>
        </header>

        <section class="platillos-section form-section-narrow" aria-label="Formulario de servicio">
            <article class="platillo-card card-padded">
                <form action="{{ route('servicios-gastronomicos.store') }}" method="POST">
                    @csrf

                    <label for="nombre" class="form-label-primary">
                        Nombre del servicio
                    </label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="{{ old('nombre') }}"
                        placeholder="Ej. Servicio de Banquetes"
                        class="search-bar-input form-input-full"
                        autofocus
                        required
                    >

                    @error('nombre')
                        <p class="form-error-msg">{{ $message }}</p>
                    @enderror

                    <footer class="form-footer-actions">
                        <a href="{{ route('servicios-gastronomicos.index') }}" class="btn-back-nav">Cancelar</a>
                        <button type="submit" class="btn-create">Guardar Servicio</button>
                    </footer>
                </form>
            </article>
        </section>
    </main>

    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>

</html>