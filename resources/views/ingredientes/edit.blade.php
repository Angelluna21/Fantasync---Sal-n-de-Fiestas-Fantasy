<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ingrediente · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/ingredientes.css'])
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <!-- Navegación superior y Encabezado Unificado -->
        <section class="top-nav" style="align-items: flex-start; margin-bottom: 2rem; padding-bottom: 0;">
            <!-- Lado Izquierdo: Logo y Botón Volver -->
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                <a href="{{ route('ingredientes.index') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Catálogo
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 3rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Catálogo de Almacén</p>
                    <h1 class="dashboard-title" style="font-size: 2.5rem; margin-top: 0.2rem;">Editar Ingrediente</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.05rem;">Actualiza la información del ingrediente y gestiona su vinculación con las recetas.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <section style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </section>
        </section>

        <!-- Formulario -->
        <section aria-label="Formulario de edición de ingrediente" style="margin-top: 7rem;">
            <article class="form-card">
                <form action="{{ route('ingredientes.update', $ingrediente->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Campo: Nombre -->
                    <fieldset class="form-group">
                        <legend class="form-label">Nombre del Ingrediente</legend>
                        <input type="text" id="nombre" name="nombre" class="form-input" placeholder="Ej. Jitomate Saladet, Harina de Trigo" value="{{ old('nombre', $ingrediente->nombre) }}" required>
                        @error('nombre')
                            <p class="form-error-msg">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <!-- Campo: Unidad -->
                    <fieldset class="form-group">
                        <legend class="form-label">Unidad de Medida</legend>
                        <select id="unidad" name="unidad" class="form-input form-select" required>
                            <option value="" disabled>Selecciona una presentación...</option>
                            <option value="kg" {{ old('unidad', $ingrediente->unidad) === 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                            <option value="gr" {{ old('unidad', $ingrediente->unidad) === 'gr' ? 'selected' : '' }}>Gramos (gr)</option>
                            <option value="l" {{ old('unidad', $ingrediente->unidad) === 'l' ? 'selected' : '' }}>Litros (l)</option>
                            <option value="ml" {{ old('unidad', $ingrediente->unidad) === 'ml' ? 'selected' : '' }}>Mililitros (ml)</option>
                            <option value="pz" {{ old('unidad', $ingrediente->unidad) === 'pz' ? 'selected' : '' }}>Piezas (pz)</option>
                        </select>
                        @error('unidad')
                            <p class="form-error-msg">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <!-- Campo: Categoría -->
                    <fieldset class="form-group">
                        <legend class="form-label">Categoría de Almacén</legend>
                        <select id="categoria" name="categoria" class="form-input form-select" required>
                            <option value="" disabled>Selecciona una categoría...</option>
                            <option value="Frutas y Verduras" {{ old('categoria', $ingrediente->categoria) === 'Frutas y Verduras' ? 'selected' : '' }}>Frutas y Verduras</option>
                            <option value="Cremería" {{ old('categoria', $ingrediente->categoria) === 'Cremería' ? 'selected' : '' }}>Cremería</option>
                            <option value="Abarrotes" {{ old('categoria', $ingrediente->categoria) === 'Abarrotes' ? 'selected' : '' }}>Abarrotes</option>
                            <option value="Carnes" {{ old('categoria', $ingrediente->categoria) === 'Carnes' ? 'selected' : '' }}>Carnes</option>
                            <option value="Otros" {{ old('categoria', $ingrediente->categoria) === 'Otros' ? 'selected' : '' }}>Otros</option>
                        </select>
                        @error('categoria')
                            <p class="form-error-msg">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <!-- Campo: Platillos (Asociación Múltiple) -->
                    <fieldset class="form-group">
                        <legend class="form-label">Vincular a Platillos / Recetas</legend>
                        <section class="platillos-multiselect">
                            @forelse($platillos as $platillo)
                                <label class="checkbox-option">
                                    <input type="checkbox" name="platillo_ids[]" value="{{ $platillo->id }}" {{ (is_array(old('platillo_ids')) && in_array($platillo->id, old('platillo_ids'))) || (!is_array(old('platillo_ids')) && $ingrediente->platillos->contains($platillo->id)) ? 'checked' : '' }}>
                                    <span>{{ $platillo->nombre }}</span>
                                </label>
                            @empty
                                <p class="empty-platillos-msg">No hay platillos creados actualmente.</p>
                            @endforelse
                        </section>
                        @error('platillo_ids')
                            <p class="form-error-msg">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <!-- Acciones del Formulario -->
                    <footer class="form-actions">
                        <a href="{{ route('ingredientes.index') }}" class="btn-cancel">Cancelar</a>
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
