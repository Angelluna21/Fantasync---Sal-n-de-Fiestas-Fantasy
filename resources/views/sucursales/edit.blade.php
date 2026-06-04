<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sucursal · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/forms.css'])
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <!-- Navegación superior -->
        <nav class="top-nav" aria-label="Menú superior">
            <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>

            <x-user-menu />
        </nav>

        <!-- Encabezado -->
        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">Ubicaciones</p>
                <h1 class="dashboard-title">Editar Sucursal</h1>
                <p class="dashboard-description">Modifica la información de la sucursal o ubicación de tu negocio.</p>
            </hgroup>
        </header>

        <!-- Formulario -->
        <section class="form-section" aria-label="Formulario de edición de sucursal">
            <article class="form-container">
                <form method="POST" action="{{ route('sucursales.update', $sucursal->id) }}" class="form-card">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <fieldset class="form-group">
                        <label for="nombre" class="form-label">Nombre de la Sucursal</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            id="nombre" 
                            class="form-input @error('nombre') form-input-error @enderror"
                            placeholder="Ej: Sucursal Centro"
                            value="{{ old('nombre', $sucursal->nombre) }}"
                            required
                        >
                        @error('nombre')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Ubicación / Dirección -->
                    <fieldset class="form-group">
                        <label for="direccion" class="form-label">Ubicación / Dirección</label>
                        <textarea 
                            name="direccion" 
                            id="direccion"
                            class="form-input form-textarea @error('direccion') form-input-error @enderror"
                            placeholder="Ingresa la dirección completa de la sucursal"
                            rows="3"
                        >{{ old('direccion', $sucursal->direccion) }}</textarea>
                        @error('direccion')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Botones de acción -->
                    <menu class="form-actions">
                        <a href="{{ route('sucursales.index') }}" class="btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            Guardar Cambios
                        </button>
                    </menu>
                </form>
            </article>
        </section>
    </main>

    <footer class="page-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>
</html>
