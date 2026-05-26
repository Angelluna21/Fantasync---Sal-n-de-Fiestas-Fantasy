<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control · Cocina Fantasy</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
</head>
<body>
    <main class="dashboard-layout">
        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">Bienvenido</p>
                <h1 class="dashboard-title">Cocina Fantasy</h1>
                <p class="dashboard-description">Sistema de gestión de eventos, menús y contratos. Desde aquí puedes administrar salones, eventos, platillos, ingredientes, sucursales y servicios gastronómicos para crear experiencias culinarias únicas de forma ágil.</p>
            </hgroup>
        </header>

        <section class="dashboard-actions" aria-label="Secciones de administración del panel">
            <article class="dashboard-card">
                <h2>Eventos</h2>
                <p>Administra los eventos programados y asigna salones y platillos para cada ocasión.</p>
                <a href="{{ route('eventos.index') }}">Ver eventos</a>
            </article>

            <article class="dashboard-card">
                <h2>Platillos</h2>
                <p>Crea y organiza menús, categorías e ingredientes para cada platillo.</p>
                <a href="{{ route('platillos.index') }}">Ver platillos</a>
            </article>

            <article class="dashboard-card">
                <h2>Ingredientes</h2>
                <p>Administra el catálogo de ingredientes, presentaciones y unidades para las recetas.</p>
                <a href="{{ route('ingredientes.index') }}">Ver ingredientes</a>
            </article>

            <article class="dashboard-card">
                <h2>Salones</h2>
                <p>Consulta la disponibilidad de salones y revisa las configuraciones de capacidad.</p>
                <a href="{{ route('salones.index') }}">Ver salones</a>
            </article>

            <article class="dashboard-card">
                <h2>Categorías</h2>
                <p>Clasifica los platillos por tiempos (guisados, bebidas, infantil, guarniciones, etc.).</p>
                <a href="{{ route('categoria-platillos.index') }}">Ver categorías</a>
            </article>

            <article class="dashboard-card">
                <h2>Servicios Gastronómicos</h2>
                <p>Gestiona los servicios adicionales y gastronomía contratados para los eventos.</p>
                <a href="{{ route('servicios-gastronomicos.index') }}">Ver servicios</a>
            </article>

            <article class="dashboard-card">
                <h2>Sucursales</h2>
                <p>Administra las sucursales donde se ubican los diferentes salones de eventos.</p>
                <a href="{{ route('sucursales.index') }}">Ver sucursales</a>
            </article>

            <article class="dashboard-card">
                <h2>Contratos</h2>
                <p>Genera, edita y previsualiza los borradores de contratos y cotizaciones de eventos.</p>
                <a href="{{ route('contratos.crear') }}">Generar contrato</a>
            </article>

            <article class="dashboard-card">
                <h2>Sesión</h2>
                <p>Cierra la sesión activa del panel de administración actual de forma segura.</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dashboard-card-button">Cerrar sesión</button>
                </form>
            </article>
        </section>
    </main>
</body>
</html>
