<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Categoría · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/platillos.css'])
</head>

<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>

    <main class="dashboard-layout">
        <nav class="top-nav" aria-label="Menú superior">
            <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>
            <x-user-menu />
        </nav>

        <nav aria-label="Navegación de retorno" class="platillos-section">
            <a href="{{ route('categorias.index') }}" class="btn-back-nav">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Categorías
            </a>
        </nav>

        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">Administración de Menú</p>
                <h1 class="dashboard-title">Crear Categoría</h1>
                <p class="dashboard-description">Agrega una nueva categoría de platillos.</p>
            </hgroup>
        </header>

        <section class="platillos-section" aria-label="Formulario de categoría" style="max-width: 520px; margin: 0 auto;">
            <section class="platillo-card" style="padding: 2rem;">
                <form action="{{ route('categorias.store') }}" method="POST">
                    @csrf

                    <label for="nombre" style="display:block; font-weight:600; color: var(--primary-purple, #7A288A); margin-bottom: 8px;">
                        Nombre de la categoría
                    </label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="{{ old('nombre') }}"
                        placeholder="Ej. Guisado - Pollo"
                        class="search-bar-input"
                        style="width: 100%; margin-bottom: 4px;"
                        autofocus
                        required
                    >
                    @error('nombre')
                        <p style="color:#c0392b; font-size: 0.85rem; margin: 6px 0 0;">{{ $message }}</p>
                    @enderror

                    <label for="grupo" style="display:block; font-weight:600; color: var(--primary-purple, #7A288A); margin: 20px 0 8px;">
                        Grupo (encabezado bajo el que aparece)
                    </label>
                    <input
                        type="text"
                        id="grupo"
                        name="grupo"
                        value="{{ old('grupo') }}"
                        placeholder="Ej. Opciones de Taquiza"
                        class="search-bar-input"
                        style="width: 100%; margin-bottom: 4px;"
                        list="grupos-existentes"
                    >
                    <datalist id="grupos-existentes">
                        @foreach($grupos as $g)
                            <option value="{{ $g }}"></option>
                        @endforeach
                    </datalist>
                    @error('grupo')
                        <p style="color:#c0392b; font-size: 0.85rem; margin: 6px 0 0;">{{ $message }}</p>
                    @enderror
                    <p style="font-size: 0.8rem; color: #a98bb3; margin-top: 6px;">
                        Deja vacío si no pertenece a ningún grupo. Puedes elegir uno existente o escribir uno nuevo.
                    </p>

                    <label for="orden" style="display:block; font-weight:600; color: var(--primary-purple, #7A288A); margin: 20px 0 8px;">
                        Orden dentro del grupo
                    </label>
                    <input
                        type="number"
                        id="orden"
                        name="orden"
                        value="{{ old('orden', 1) }}"
                        min="1"
                        class="search-bar-input"
                        style="width: 100%; margin-bottom: 4px;"
                        required
                    >
                    @error('orden')
                        <p style="color:#c0392b; font-size: 0.85rem; margin: 6px 0 0;">{{ $message }}</p>
                    @enderror

                    <section style="display:flex; justify-content:flex-end; gap:12px; margin-top: 24px;">
                        <a href="{{ route('categorias.index') }}" class="btn-back-nav">Cancelar</a>
                        <button type="submit" class="btn-create">Guardar Categoría</button>
                    </section>
                </form>
            </section>
        </section>
    </main>

    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>

</html>