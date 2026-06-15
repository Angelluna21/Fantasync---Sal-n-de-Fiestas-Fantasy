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

                    <!-- Sucursal Autoseleccionada (Vicente Villada por defecto) -->
                    <input type="hidden" name="sucursal_id" value="{{ $sucursales->first()->id ?? 1 }}">

                    <!-- Nombre -->
                    <fieldset class="form-group">
                        <label for="nombre" class="form-label">Nombre del Salón</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            id="nombre" 
                            class="form-input @error('nombre') form-input-error @enderror"
                            placeholder="Ej: Salón Real"
                            value="{{ old('nombre') }}"
                            required
                        >
                        @error('nombre')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Alias / Sobrenombre -->
                    <fieldset class="form-group">
                        <label for="alias" class="form-label">Sobrenombre / Alias</label>
                        <input 
                            type="text" 
                            name="alias" 
                            id="alias"
                            class="form-input @error('alias') form-input-error @enderror"
                            placeholder="Ej: Jardín Secreto, Terraza Cristal"
                            value="{{ old('alias') }}"
                        >
                        <p class="form-hint">Un nombre corto o alternativo para identificar el espacio.</p>
                        @error('alias')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Capacidad -->
                    <fieldset class="form-group">
                        <label for="capacidad" class="form-label">Capacidad Máxima (personas)</label>
                        <input 
                            type="number" 
                            name="capacidad" 
                            id="capacidad"
                            class="form-input @error('capacidad') form-input-error @enderror"
                            placeholder="Ej: 250"
                            min="0"
                            value="{{ old('capacidad') }}"
                        >
                        @error('capacidad')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Dirección -->
                    <fieldset class="form-group">
                        <label for="direccion" class="form-label">Dirección Física del Salón</label>
                        <input 
                            type="text" 
                            name="direccion" 
                            id="direccion"
                            class="form-input @error('direccion') form-input-error @enderror"
                            placeholder="Ej: Av. Juárez 105, Centro Histórico"
                            value="{{ old('direccion') }}"
                        >
                        <p class="form-hint">Se utilizará para mostrar la ubicación interactiva en Google Maps.</p>
                        @error('direccion')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Estado -->
                    <fieldset class="form-group">
                        <label for="estado" class="form-label">Estado del Salón</label>
                        <select 
                            name="estado" 
                            id="estado"
                            class="form-input @error('estado') form-input-error @enderror"
                            required
                        >
                            <option value="activo" {{ old('estado') === 'activo' ? 'selected' : '' }}>🟢 Activo / Disponible</option>
                            <option value="mantenimiento" {{ old('estado') === 'mantenimiento' ? 'selected' : '' }}>🟡 En Mantenimiento</option>
                            <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>🔴 Inactivo / Cerrado</option>
                        </select>
                        @error('estado')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <!-- Descripción -->
                    <fieldset class="form-group">
                        <label for="descripcion" class="form-label">Descripción y Amenidades</label>
                        <textarea 
                            name="descripcion" 
                            id="descripcion"
                            class="form-input form-textarea @error('descripcion') form-input-error @enderror"
                            placeholder="Ej: Cuenta con pista de baile, área de jardín para ceremonias, camerino para festejados y estacionamiento privado."
                            rows="4"
                        >{{ old('descripcion') }}</textarea>
                        @error('descripcion')
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
