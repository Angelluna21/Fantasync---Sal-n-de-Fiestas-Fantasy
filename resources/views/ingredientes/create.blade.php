<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Ingrediente · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/ingredientes.css'])
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

        <!-- Volver al Listado -->
        <nav aria-label="Navegación de retorno" class="narrow-container">
            <a href="{{ route('ingredientes.index') }}" class="btn-back-nav">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Catálogo
            </a>
        </nav>

        <!-- Encabezado -->
        <header class="dashboard-header narrow-container">
            <hgroup>
                <p class="eyebrow">Catálogo de Almacén</p>
                <h1 class="dashboard-title">Crear Ingrediente</h1>
                <p class="dashboard-description">Introduce la información básica del ingrediente y asígnalo a las recetas correspondientes.</p>
            </hgroup>
        </header>

        <!-- Formulario -->
        <section aria-label="Formulario de nuevo ingrediente">
            <article class="form-card">
                <form action="{{ route('ingredientes.store') }}" method="POST">
                    @csrf

                    <!-- Campo: Nombre -->
                    <fieldset class="form-group">
                        <legend class="form-label">Nombre del Ingrediente</legend>
                        <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Ej. Jitomate Saladet, Harina de Trigo, Crema Líquida" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <p style="color: var(--accent-magenta); font-size: 0.8rem; font-weight: 700; margin: 0.25rem 0 0 0;">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <!-- Campo: Unidad -->
                    <fieldset class="form-group">
                        <legend class="form-label">Unidad de Medida</legend>
                        <select id="unidad" name="unidad" class="form-input form-select" required>
                            <option value="" disabled selected>Selecciona una presentación...</option>
                            <option value="kg" {{ old('unidad') === 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                            <option value="gr" {{ old('unidad') === 'gr' ? 'selected' : '' }}>Gramos (gr)</option>
                            <option value="l" {{ old('unidad') === 'l' ? 'selected' : '' }}>Litros (l)</option>
                            <option value="ml" {{ old('unidad') === 'ml' ? 'selected' : '' }}>Mililitros (ml)</option>
                            <option value="pz" {{ old('unidad') === 'pz' ? 'selected' : '' }}>Piezas (pz)</option>
                        </select>
                        @error('unidad')
                            <p style="color: var(--accent-magenta); font-size: 0.8rem; font-weight: 700; margin: 0.25rem 0 0 0;">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <!-- Campo: Categoría -->
                    <fieldset class="form-group">
                        <legend class="form-label">Categoría de Almacén</legend>
                        <select id="categoria" name="categoria" class="form-input form-select" required>
                            <option value="" disabled selected>Selecciona una categoría...</option>
                            <option value="Frutas y Verduras" {{ old('categoria') === 'Frutas y Verduras' ? 'selected' : '' }}>Frutas y Verduras</option>
                            <option value="Cremería" {{ old('categoria') === 'Cremería' ? 'selected' : '' }}>Cremería</option>
                            <option value="Abarrotes" {{ old('categoria') === 'Abarrotes' ? 'selected' : '' }}>Abarrotes</option>
                            <option value="Carnes" {{ old('categoria') === 'Carnes' ? 'selected' : '' }}>Carnes</option>
                            <option value="Otros" {{ old('categoria') === 'Otros' ? 'selected' : '' }}>Otros</option>
                        </select>
                        @error('categoria')
                            <p style="color: var(--accent-magenta); font-size: 0.8rem; font-weight: 700; margin: 0.25rem 0 0 0;">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <!-- Campo: Platillos (Asociación Múltiple) -->
                    <fieldset class="form-group">
                        <legend class="form-label">Vincular a Platillos / Recetas</legend>
                        <section class="platillos-multiselect">
                            @forelse($platillos as $platillo)
                                <label class="checkbox-option">
                                    <input type="checkbox" name="platillo_ids[]" value="{{ $platillo->id }}" {{ is_array(old('platillo_ids')) && in_array($platillo->id, old('platillo_ids')) ? 'checked' : '' }}>
                                    <span>{{ $platillo->nombre }}</span>
                                </label>
                            @empty
                                <p style="color: #bcbcbc; font-size: 0.85rem; font-style: italic; margin: 0; padding: 0.5rem 0;">No hay platillos creados actualmente.</p>
                            @endforelse
                        </section>
                        @error('platillo_ids')
                            <p style="color: var(--accent-magenta); font-size: 0.8rem; font-weight: 700; margin: 0.25rem 0 0 0;">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <!-- Acciones del Formulario -->
                    <footer class="form-actions">
                        <a href="{{ route('ingredientes.index') }}" class="btn-cancel">Cancelar</a>
                        <button type="submit" class="btn-save">Guardar Ingrediente</button>
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
