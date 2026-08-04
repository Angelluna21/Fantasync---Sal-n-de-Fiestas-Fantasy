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
        <!-- Navegación superior y Encabezado Unificado -->
        <nav class="top-nav top-nav-dashboard">
            <!-- Lado Izquierdo: Logo -->
            <section class="nav-brand-col">
                <a href="{{ url('/') }}" aria-label="Volver al inicio" class="logo-link logo-link-fit">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo nav-logo-lg">
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header header-center-col">
                <hgroup>
                    <p class="eyebrow eyebrow-no-margin">Bienvenido a FantaSync</p>
                    <h1 class="dashboard-title title-hero">Gestor Fantasy</h1>
                    <p class="dashboard-description desc-hero">Sistema de gestión de eventos, menús y contratos. Administra salones y platillos para crear experiencias culinarias y eventos inolvidables de forma ágil.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <aside class="nav-user-col">
                <x-user-menu />
            </aside>
        </nav>

        <section class="dashboard-actions dashboard-actions-mt" aria-label="Secciones de administración del panel">
            <!-- 1. Contratos -->
            <article class="dashboard-card highlight-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </span>
                <h2>Contratos</h2>
                <p>Genera, edita y previsualiza los borradores de contratos y cotizaciones de eventos.</p>
                <a href="{{ route('contratos.index') }}">Ver contratos</a>
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
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke-width="2" d="M3 18h18" />
                        <path stroke-width="2" d="M5 18a7 7 0 0 1 14 0" />
                        <circle cx="12" cy="8" r="2" stroke-width="2" />
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

            <!-- 6. Vendedoras -->
            <article class="dashboard-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                <h2>Vendedoras</h2>
                <p>Administra al personal de ventas, su información de contacto y estado activo.</p>
                <a href="{{ route('vendedoras.index') }}">Ver vendedoras</a>
            </article>

            <!-- 7. Servicios Gastronómicos -->
            <article class="dashboard-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Pot body -->
                        <path stroke-width="2" d="M5 11v6a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3v-6" />
                        <!-- Lid -->
                        <path stroke-width="2" d="M3 11h18" />
                        <path stroke-width="2" d="M7 11L9 7h6l2 4" />
                        <!-- Handle -->
                        <path stroke-width="2" d="M10 7V5h4v2" />
                    </svg>
                </span>
                <h2>Servicios Gastronómicos</h2>
                <p>Gestiona los servicios adicionales y gastronomía contratados para los eventos.</p>
                <a href="{{ route('servicios-gastronomicos.index') }}">Ver servicios</a>
            </article>

            <!-- 8. Nóminas -->
            <article class="dashboard-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </span>
                <h2>Nóminas</h2>
                <p>Calcula y registra los pagos y asistencia del personal por cada evento.</p>
                <a href="{{ route('nominas.index') }}">Ver nóminas</a>
            </article>

            @if(auth()->user()->isSuperadmin())
            <!-- 9. Gestión de Usuarios (Admin Only) -->
            <article class="dashboard-card card-admin">
                <span class="card-icon icon-admin" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </span>
                <h2>Gestión de Usuarios</h2>
                <p>Crea y administra a los usuarios normales que tendrán acceso al sistema.</p>
                <a href="{{ route('users.index') }}">Ver usuarios</a>
            </article>

            <!-- 9. Bitácora de Actividades (Admin Only) -->
            <article class="dashboard-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </span>
                <h2>Bitácora de Actividades</h2>
                <p>Revisa el historial de cambios y modificaciones realizados por otros usuarios.</p>
                <a href="{{ route('logs.activity') }}">Ver bitácora</a>
            </article>
            @endif
        </section>
    </main>

    <!-- Pie de página -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>

</body>

</html>