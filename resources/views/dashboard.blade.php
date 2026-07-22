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
        <div class="top-nav" style="align-items: flex-start; margin-bottom: 2rem; padding-bottom: 0;">
            <!-- Lado Izquierdo: Logo -->
            <div style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ url('/') }}" aria-label="Volver al inicio" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
            </div>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 3rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Bienvenido a FantaSync</p>
                    <h1 class="dashboard-title" style="font-size: 2.5rem; margin-top: 0.2rem;">Gestor Fantasy</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.05rem;">Sistema de gestión de eventos, menús y contratos. Administra salones y platillos para crear experiencias culinarias y eventos inolvidables de forma ágil.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <div style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </div>
        </div>

        <section class="dashboard-actions" aria-label="Secciones de administración del panel" style="margin-top: 5rem;">
            <!-- 1. Contratos -->
            <article class="dashboard-card highlight-card">
                <span class="card-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </span>
                <h2>Contratos</h2>
                <p>Genera, edita y previsualiza los borradores de contratos y cotizaciones de eventos.</p>
                <a href="{{ route('contratos.index') }}">Generar contrato</a>
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
                <a href="{{ route('categorias.index') }}">Ver categorías</a>
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
            <article class="dashboard-card" style="border-color: var(--primary-purple); background: #faf5fc;">
                <span class="card-icon" aria-hidden="true" style="background: var(--primary-purple); color: white; border: none;">
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