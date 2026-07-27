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
        <!-- Navegación superior y Encabezado Unificado -->
        <section class="top-nav" aria-label="Menú superior" style="align-items: flex-start;">
            <!-- Lado Izquierdo: Logo y Botón Volver -->
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                <a href="{{ route('servicios-gastronomicos.index') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Servicios
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 2rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Administración de Menú</p>
                    <h1 class="dashboard-title" style="font-size: 2.8rem; margin-top: 0.2rem;">Crear Servicio</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.1rem;">Dale un nombre al nuevo tipo de servicio gastronómico.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <section style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </section>
        </section>

        <section class="platillos-section form-section-narrow" aria-label="Formulario de servicio" style="margin-top: 7rem;">
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

                    <footer class="form-actions" style="display: flex; gap: 1rem; width: 100%; justify-content: center; margin-top: 2rem;">
                        <a href="{{ route('servicios-gastronomicos.index') }}" class="btn-cancel" style="flex: 1; text-align: center; max-width: 300px;">Cancelar</a>
                        <button type="submit" class="btn-save" style="flex: 1; text-align: center; max-width: 300px;">Guardar Servicio</button>
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