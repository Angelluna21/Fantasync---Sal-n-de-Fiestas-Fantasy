<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Vendedora · FantaSync</title>
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
                <p class="eyebrow">Gestión de Personal</p>
                <h1 class="dashboard-title">Registrar Vendedora</h1>
                <p class="dashboard-description">Añade a una nueva vendedora al sistema.</p>
            </hgroup>
        </header>

        <!-- Formulario -->
        <section class="form-section" aria-label="Formulario de registro de vendedora">
            <section class="form-container">
                <a href="{{ route('vendedoras.index') }}" class="btn-back-nav">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="nav-icon-large"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a la lista de vendedoras
                </a>
                <form method="POST" action="{{ route('vendedoras.store') }}" class="form-card">
                    @csrf

                    <!-- Nombre -->
                    <fieldset class="form-group">
                        <label for="nombre" class="form-label">Nombre(s)</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            id="nombre" 
                            class="form-input @error('nombre') form-input-error @enderror"
                            placeholder="Ej: Ana María"
                            value="{{ old('nombre') }}"
                            pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+"
                            title="El nombre solo debe contener letras y espacios."
                            required
                        >
                        @error('nombre')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Apellidos -->
                    <fieldset class="form-group">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input 
                            type="text" 
                            name="apellidos" 
                            id="apellidos" 
                            class="form-input @error('apellidos') form-input-error @enderror"
                            placeholder="Ej: Pérez García"
                            value="{{ old('apellidos') }}"
                            pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+"
                            title="Los apellidos solo deben contener letras y espacios."
                            required
                        >
                        @error('apellidos')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Teléfono -->
                    <fieldset class="form-group">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input 
                            type="text" 
                            name="telefono" 
                            id="telefono"
                            class="form-input @error('telefono') form-input-error @enderror"
                            placeholder="Ej: 55 1234 5678"
                            value="{{ old('telefono') }}"
                        >
                        @error('telefono')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Email -->
                    <fieldset class="form-group">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            class="form-input @error('email') form-input-error @enderror"
                            placeholder="Ej: ana.perez@ejemplo.com"
                            value="{{ old('email') }}"
                        >
                        @error('email')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Estado -->
                    <fieldset class="form-group">
                        <label for="estado" class="form-label">Estado</label>
                        <select 
                            name="estado" 
                            id="estado"
                            class="form-input @error('estado') form-input-error @enderror"
                            required
                        >
                            <option value="activo" {{ old('estado') === 'activo' ? 'selected' : '' }}>🟢 Activa</option>
                            <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>🔴 Inactiva</option>
                        </select>
                        @error('estado')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Botones de acción -->
                    <menu class="form-actions">
                        <a href="{{ route('vendedoras.index') }}" class="btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            Registrar Vendedora
                        </button>
                    </menu>
                </form>
            </section>
        </section>
    </main>

    <footer class="page-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>
</html>
