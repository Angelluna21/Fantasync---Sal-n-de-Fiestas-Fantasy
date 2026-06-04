<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Salón · FantaSync</title>
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
                <p class="eyebrow">Gestión de Espacios</p>
                <h1 class="dashboard-title">Crear Salón</h1>
                <p class="dashboard-description">Agrega un nuevo salón o espacio disponible para eventos.</p>
            </hgroup>
        </header>

        <!-- Formulario -->
        <section class="form-section" aria-label="Formulario de creación de salón">
            <section class="form-container">
                <a href="{{ route('salones.index') }}" class="btn-back-nav">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a la lista de salones
                </a>
                <form method="POST" action="{{ route('salones.store') }}" class="form-card">
                    @csrf

                    <!-- Sucursal -->
                    <fieldset class="form-group">
                        <label for="sucursal_id" class="form-label">Sucursal</label>
                        <select 
                            name="sucursal_id" 
                            id="sucursal_id"
                            class="form-input @error('sucursal_id') form-input-error @enderror"
                            required
                        >
                            <option value="">Seleccionar sucursal...</option>
                            @forelse($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}" @selected(old('sucursal_id', request()->query('sucursal_id')) == $sucursal->id)>
                                    {{ $sucursal->nombre }}
                                </option>
                            @empty
                                <option value="" disabled>No hay sucursales disponibles</option>
                            @endforelse
                        </select>
                        @error('sucursal_id')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Nombre -->
                    <fieldset class="form-group">
                        <label for="nombre" class="form-label">Nombre del Salón</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            id="nombre" 
                            class="form-input @error('nombre') form-input-error @enderror"
                            placeholder="Ej: Salón Principal"
                            value="{{ old('nombre') }}"
                            required
                        >
                        @error('nombre')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Alias -->
                    <fieldset class="form-group">
                        <label for="alias" class="form-label">Alias (Código Corto)</label>
                        <input 
                            type="text" 
                            name="alias" 
                            id="alias"
                            class="form-input @error('alias') form-input-error @enderror"
                            placeholder="Ej: SP-01"
                            value="{{ old('alias') }}"
                        >
                        <p class="form-hint">Opcional: Una abreviatura para referencia rápida</p>
                        @error('alias')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Botones de acción -->
                    <menu class="form-actions">
                        <a href="{{ route('salones.index') }}" class="btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            Crear Salón
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
