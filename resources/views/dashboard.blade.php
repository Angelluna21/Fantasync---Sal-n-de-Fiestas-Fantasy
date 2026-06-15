<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
</head>

<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>

    <main class="dashboard-layout">
        <!-- Navegación superior y Header unificado -->
        <header class="top-nav header-unified" aria-label="Menú superior">
            <!-- Elace al logo -->
            <a href="{{ url('/') }}" aria-label="Volver al inicio" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>

            <!-- TÍTULOS CENTRALES -->
            <hgroup class="header-titles">
                <p class="eyebrow">Bienvenido a FantaSync</p>
                <h1 class="dashboard-title">Gestor Fantasy</h1>
            </hgroup>

            <!-- MENÚ DE USUARIO -->
            <nav aria-label="Opciones de usuario">
                <x-user-menu />
            </nav>
        </header>

        <!-- Descripción del sistema -->
        <section class="system-description-wrapper" aria-label="Acerca del sistema">
            <article class="system-description-card">
                <figure class="system-icon-wrapper" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="system-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </figure>
                <p class="dashboard-description">
                    Sistema de gestión de eventos, menús y contratos. Administra salones y platillos para crear experiencias culinarias y eventos inolvidables de forma ágil.
                </p>
            </article>
        </section>

        <section class="dashboard-actions" aria-label="Secciones de administración del panel">
            <!-- 1. Contratos -->
            <article class="dashboard-card highlight-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </span>
                <h2>Contratos</h2>
                <p>Genera, edita y previsualiza los borradores de contratos y cotizaciones de eventos.</p>
                <a href="{{ route('contratos.crear') }}">Generar contrato</a>
            </article>

            <!-- 2. Eventos -->
            <article class="dashboard-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </span>
                <h2>Eventos</h2>
                <p>Administra los eventos programados y asigna salones y platillos para cada ocasión.</p>
                <a href="{{ route('eventos.index') }}">Ver eventos</a>
            </article>

            <!-- 3. Platillos -->
            <article class="dashboard-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2v10z"></path>
                    </svg>
                </span>
                <h2>Platillos</h2>
                <p>Crea y organiza menús, categorías e ingredientes para cada platillo.</p>
                <a href="{{ route('platillos.index') }}">Ver platillos</a>
            </article>

            <!-- 4. Ingredientes -->
            <article class="dashboard-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </span>
                <h2>Ingredientes</h2>
                <p>Administra el catálogo de ingredientes, presentaciones y unidades para las recetas.</p>
                <a href="{{ route('ingredientes.index') }}">Ver ingredientes</a>
            </article>

            <!-- 5. Salones -->
            <article class="dashboard-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </span>
                <h2>Salones</h2>
                <p>Consulta la disponibilidad de salones y revisa las configuraciones de capacidad.</p>
                <a href="{{ route('salones.index') }}">Ver salones</a>
            </article>

            <!-- 6. Categorías -->
            <article class="dashboard-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </span>
                <h2>Categorías</h2>
                <p>Clasifica los platillos por tiempos (guisados, bebidas, infantil, guarniciones, etc.).</p>
                <a href="{{ route('categoria-platillos.index') }}">Ver categorías</a>
            </article>

            <!-- 7. Servicios Gastronómicos -->
            <article class="dashboard-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </span>
                <h2>Servicios Gastronómicos</h2>
                <p>Gestiona los servicios adicionales y gastronomía contratados para los eventos.</p>
                <a href="{{ route('servicios-gastronomicos.index') }}">Ver servicios</a>
            </article>
        </section>
    </main>

    <!-- Pie de página -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>

</body>

</html>