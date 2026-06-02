<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Platillo · FantaSync</title>
    <meta name="description" content="Edita la ficha técnica de un platillo — FantaSync">
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/platillos.css'])
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>

    <main class="dashboard-layout">
        <!-- Navegación superior -->
        <nav class="top-nav" aria-label="Menú superior">
            <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Cerrar sesión
                </button>
            </form>
        </nav>

        <!-- Volver al Listado -->
        <nav aria-label="Navegación de retorno" class="narrow-container">
            <a href="{{ route('platillos.index') }}" class="btn-back-nav">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Catálogo
            </a>
        </nav>

        <!-- Encabezado -->
        <header class="dashboard-header narrow-container">
            <hgroup>
                <p class="eyebrow">Administración de Menú</p>
                <h1 class="dashboard-title">Editar Platillo</h1>
                <p class="dashboard-description">Actualiza la ficha técnica del platillo, sus porciones base e insumos requeridos.</p>
            </hgroup>
        </header>

        <!-- Formulario -->
        <section aria-label="Formulario de edición de platillo">
            <article class="form-card narrow-container">
                <form action="{{ route('platillos.update', $platillo->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Campo: Nombre -->
                    <fieldset class="form-group">
                        <legend class="form-label">Nombre del Platillo</legend>
                        <input type="text" id="nombre" name="nombre" class="form-input"
                               placeholder="Ej. Filete de res en salsa pimienta"
                               value="{{ old('nombre', $platillo->nombre) }}" required>
                        @error('nombre')
                            <output class="form-error">{{ $message }}</output>
                        @enderror
                    </fieldset>

                    <!-- Campo: Categoría -->
                    <fieldset class="form-group">
                        <legend class="form-label">Categoría de Menú</legend>
                        <select id="categoria_platillo_id" name="categoria_platillo_id" class="form-input" required>
                            <option value="" disabled>Selecciona una categoría...</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}"
                                    {{ old('categoria_platillo_id', $platillo->categoria_platillo_id) == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_platillo_id')
                            <output class="form-error">{{ $message }}</output>
                        @enderror
                    </fieldset>

                    <!-- Campo: Descripción -->
                    <fieldset class="form-group">
                        <legend class="form-label">Descripción</legend>
                        <textarea id="descripcion" name="descripcion" class="form-input form-textarea"
                                  placeholder="Describe los ingredientes principales y guarniciones">{{ old('descripcion', $platillo->descripcion) }}</textarea>
                        @error('descripcion')
                            <output class="form-error">{{ $message }}</output>
                        @enderror
                    </fieldset>

                    <!-- Campo: Porciones Base -->
                    <fieldset class="form-group">
                        <legend class="form-label">Porciones Base</legend>
                        <input type="number" id="porciones_base" name="porciones_base" class="form-input"
                               placeholder="1" min="1" value="{{ old('porciones_base', $platillo->porciones_base ?? 1) }}">
                        @error('porciones_base')
                            <output class="form-error">{{ $message }}</output>
                        @enderror
                    </fieldset>

                    <!-- Campo: Ingredientes (Asociación Múltiple) -->
                    <fieldset class="form-group">
                        <legend class="form-label">Fórmula / Insumos del Almacén</legend>
                        <section class="ingredientes-multiselect">
                            @forelse($ingredientes as $ingrediente)
                                <label class="checkbox-option">
                                    <input type="checkbox" name="ingrediente_ids[]"
                                           value="{{ $ingrediente->id }}"
                                           {{ (is_array(old('ingrediente_ids')) && in_array($ingrediente->id, old('ingrediente_ids')))
                                              || (!is_array(old('ingrediente_ids')) && $platillo->ingredientes->contains($ingrediente->id))
                                              ? 'checked' : '' }}>
                                    <span>{{ $ingrediente->nombre }} ({{ $ingrediente->unidad }})</span>
                                </label>
                            @empty
                                <p class="multiselect-empty">No hay ingredientes registrados.</p>
                            @endforelse
                        </section>
                        @error('ingrediente_ids')
                            <output class="form-error">{{ $message }}</output>
                        @enderror
                    </fieldset>

                    <!-- Acciones del Formulario -->
                    <footer class="form-actions">
                        <a href="{{ route('platillos.index') }}" class="btn-cancel">Cancelar</a>
                        <button type="submit" class="btn-save">Guardar Cambios</button>
                    </footer>
                </form>
            </article>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>
</html>
