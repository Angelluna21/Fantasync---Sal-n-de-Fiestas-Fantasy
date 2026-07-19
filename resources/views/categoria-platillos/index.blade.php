<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías · FantaSync</title>
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
            <a href="{{ route('dashboard') }}" class="btn-back-nav">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Panel
            </a>
        </nav>

        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">Administración de Menú</p>
                <h1 class="dashboard-title">Categorías</h1>
                <p class="dashboard-description">Clasifica los platillos por tiempos (guisados, bebidas, infantil, guarniciones, etc.).</p>
            </hgroup>
        </header>

        @if (session('success'))
            <p style="max-width: 1100px; margin: 0 auto 1rem; background: #e9f9ee; color: #1e8a4c; padding: 12px 18px; border-radius: 10px; font-weight: 500;">
                {{ session('success') }}
            </p>
        @endif

        @if (session('error'))
            <p style="max-width: 1100px; margin: 0 auto 1rem; background: #fdeceb; color: #c0392b; padding: 12px 18px; border-radius: 10px; font-weight: 500;">
                {{ session('error') }}
            </p>
        @endif

        <section class="platillos-section" aria-label="Listado de categorías">

            <menu class="action-bar" aria-label="Acciones del catálogo">
                <li>
                    <h2 class="section-title">Catálogo de Categorías</h2>
                </li>
                <li>
                    <nav class="action-controls" aria-label="Controles del listado">
                        <input type="text" id="search-input" class="search-bar-input" placeholder="Buscar categoría...">

                        <a href="{{ route('categorias.create') }}" class="btn-create">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Crear Categoría
                        </a>
                    </nav>
                </li>
            </menu>

            @if($categorias->isEmpty())
                <article class="empty-state" id="empty-state-card">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    <h3>No hay categorías registradas</h3>
                    <p>Crea tu primera categoría para empezar a clasificar los platillos.</p>
                    <a href="{{ route('categorias.create') }}" class="btn-create">Crear Primera Categoría</a>
                </article>
            @else
                @foreach($categorias as $grupo => $items)
                    <h3 style="color: var(--primary-purple, #7A288A); margin: 2rem 0 1rem 0; font-size: 1.25rem; border-bottom: 2px solid #f0eaef; padding-bottom: 5px;">
                        {{ $grupo }}
                    </h3>

                    <div class="platillos-grid group-container">
                        @foreach($items as $categoria)
                            <article class="platillo-card" data-nombre="{{ $categoria->nombre }}">
                                <header class="card-header">
                                    <h3 class="card-title">{{ $categoria->nombre }}</h3>
                                    <span class="badge">{{ $categoria->platillos_count }} platillos</span>
                                </header>

                                <footer class="card-footer">
                                    <menu class="card-actions-menu">
                                        <li>
                                            <a href="{{ route('categorias.show', $categoria->id) }}" class="btn-action btn-view" title="Ver Categoría">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn-action btn-edit" title="Editar Categoría">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <button type="button" class="btn-action btn-delete" title="Eliminar" onclick="confirmDelete('{{ $categoria->nombre }}', 'delete-form-{{ $categoria->id }}')">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </li>
                                    </menu>

                                    <form id="delete-form-{{ $categoria->id }}" action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </footer>
                            </article>
                        @endforeach
                    </div>
                @endforeach
            @endif
        </section>
    </main>

    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>

    <dialog id="confirm-modal" class="custom-confirm">
        <h3 id="confirm-title" class="confirm-title">¿Eliminar categoría?</h3>
        <p id="confirm-text" class="confirm-text">Esta acción no se puede deshacer.</p>
        <footer class="confirm-actions">
            <menu class="modal-actions-menu">
                <li>
                    <button type="button" class="btn-cancel" onclick="closeConfirmModal()">Cancelar</button>
                </li>
                <li>
                    <button type="button" class="btn-delete-confirm" id="confirm-delete-btn">Eliminar</button>
                </li>
            </menu>
        </footer>
    </dialog>

    <script>
        let formToSubmit = null;

        function confirmDelete(name, formId) {
            formToSubmit = document.getElementById(formId);
            const modal = document.getElementById('confirm-modal');
            document.getElementById('confirm-text').innerHTML = `¿Estás seguro de que deseas eliminar la categoría <strong>"${name}"</strong>?`;
            modal.showModal();
        }

        function closeConfirmModal() {
            document.getElementById('confirm-modal').close();
            formToSubmit = null;
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', () => {
            if (formToSubmit) formToSubmit.submit();
        });

        const searchInput = document.getElementById('search-input');
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            document.querySelectorAll('.platillo-card').forEach(card => {
                const name = card.getAttribute('data-nombre').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                card.style.display = name.includes(query) ? 'flex' : 'none';
            });
        });
    </script>
</body>

</html>