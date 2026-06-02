<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingredientes · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/ingredientes.css'])
</head>
<body>
    <!-- Fondo decorativo -->
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

        <!-- Volver al Panel -->
        <nav aria-label="Navegación de retorno" class="ingredientes-section">
            <a href="{{ route('dashboard') }}" class="btn-back-nav">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
        </nav>

        <!-- Encabezado -->
        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">Recetas e Insumos</p>
                <h1 class="dashboard-title">Ingredientes</h1>
                <p class="dashboard-description">Administra el catálogo de ingredientes, presentaciones y unidades métricas necesarias para formular tus platillos y recetas.</p>
            </hgroup>
        </header>

        <!-- Contenedor Principal de Ingredientes -->
        <section class="ingredientes-section" aria-label="Listado de ingredientes">
            <menu class="action-bar" aria-label="Acciones del catálogo">
                <li>
                    <h2 class="section-title">Insumos del Almacén</h2>
                </li>
                <li>
                    <nav class="action-controls" aria-label="Controles del listado">
                        <!-- Entrada de Búsqueda -->
                        <input type="text" id="search-input" class="search-bar-input" placeholder="Buscar ingrediente...">

                        <!-- Selección de Ordenamiento -->
                        <select id="sort-select" class="sort-bar-select" aria-label="Criterio de ordenación">
                            <option value="asc">A-Z (Ascendente)</option>
                            <option value="desc">Z-A (Descendente)</option>
                        </select>

                        <!-- Conmutador de Vistas (Grid / Lista) -->
                        <nav class="view-switcher" aria-label="Cambio de vista">
                            <button type="button" id="grid-view-btn" class="btn-switch active" title="Vista Cuadrícula">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            </button>
                            <button type="button" id="list-view-btn" class="btn-switch" title="Vista Lista">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            </button>
                        </nav>

                        <a href="{{ route('ingredientes.create') }}" class="btn-create">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Crear Ingrediente
                        </a>
                    </nav>
                </li>
            </menu>

            <!-- 1. Vista de Cuadrícula (Grid) -->
            <section class="ingredientes-grid" id="ingredients-grid-view" aria-label="Catálogo de Ingredientes en Cuadrícula">
                @forelse($ingredientes as $ingrediente)
                    <article class="ingrediente-card" data-nombre="{{ $ingrediente->nombre }}">
                        <header class="card-header">
                            <h3 class="card-title">{{ $ingrediente->nombre }}</h3>
                            <span class="unit-badge {{ $ingrediente->unidad }}">
                                {{ $ingrediente->unidad }}
                            </span>
                        </header>

                        <section class="card-body">
                            <h4 class="associated-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Platillos Asociados
                            </h4>
                            @if($ingrediente->platillos->count() > 0)
                                <menu class="platillos-list" aria-label="Platillos que usan este ingrediente">
                                    @foreach($ingrediente->platillos as $platillo)
                                        <li><span class="platillo-badge">{{ $platillo->nombre }}</span></li>
                                    @endforeach
                                </menu>
                            @else
                                <p class="no-platillos">No asignado a ningún platillo</p>
                            @endif
                        </section>

                        <footer class="card-footer">
                            <menu style="list-style: none; display: flex; gap: 0.5rem; padding: 0; margin: 0;">
                                <li>
                                    <a href="{{ route('ingredientes.show', $ingrediente->id) }}" class="btn-action btn-view" title="Ver Detalles">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('ingredientes.edit', $ingrediente->id) }}" class="btn-action btn-edit" title="Editar Ingrediente">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                </li>
                                <li>
                                    <button type="button" class="btn-action btn-delete" title="Eliminar" onclick="confirmDelete('{{ $ingrediente->nombre }}', 'delete-form-{{ $ingrediente->id }}')">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </li>
                            </menu>

                            <form id="delete-form-{{ $ingrediente->id }}" action="{{ route('ingredientes.destroy', $ingrediente->id) }}" method="POST" class="form-delete" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </footer>
                    </article>
                @empty
                    <article class="empty-state" id="empty-state-card">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <h3>No hay ingredientes registrados</h3>
                        <p>Registra los insumos de tu almacén para empezar a formular tus recetas.</p>
                        <a href="{{ route('ingredientes.create') }}" class="btn-create">Crear Primer Ingrediente</a>
                    </article>
                @endforelse
            </section>

            <!-- 2. Vista de Lista (Tabla) -->
            <section class="table-container" id="ingredients-list-view" style="display: none;" aria-label="Catálogo de Ingredientes en Lista">
                <table class="platillos-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Unidad</th>
                            <th>Platillos Asociados</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="ingredients-table-body">
                        @forelse($ingredientes as $ingrediente)
                            <tr class="table-row-item" data-nombre="{{ $ingrediente->nombre }}">
                                <td class="col-name">{{ $ingrediente->nombre }}</td>
                                <td>
                                    <span class="unit-badge {{ $ingrediente->unidad }}">
                                        {{ $ingrediente->unidad }}
                                    </span>
                                </td>
                                <td>
                                    @if($ingrediente->platillos->count() > 0)
                                        <menu class="platillos-list" aria-label="Platillos asociados" style="list-style: none; display: flex; gap: 0.35rem; padding: 0; margin: 0; flex-wrap: wrap;">
                                            @foreach($ingrediente->platillos as $platillo)
                                                <li><span class="platillo-badge">{{ $platillo->nombre }}</span></li>
                                            @endforeach
                                        </menu>
                                    @else
                                        <span class="no-platillos">No asignado a ningún platillo</span>
                                    @endif
                                </td>
                                <td class="col-actions">
                                    <menu style="list-style: none; display: flex; gap: 0.5rem; padding: 0; margin: 0; justify-content: center;">
                                        <li>
                                            <a href="{{ route('ingredientes.show', $ingrediente->id) }}" class="btn-action btn-view" title="Ver Detalles">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('ingredientes.edit', $ingrediente->id) }}" class="btn-action btn-edit" title="Editar Ingrediente">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                        </li>
                                        <li>
                                            <button type="button" class="btn-action btn-delete" title="Eliminar" onclick="confirmDelete('{{ $ingrediente->nombre }}', 'delete-form-{{ $ingrediente->id }}')">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </li>
                                    </menu>
                                </td>
                            </tr>
                        @empty
                            <tr class="table-empty">
                                <td colspan="4" style="text-align: center; padding: 2rem;">No hay ingredientes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>

    <!-- Diálogo de Confirmación Custom (Premium Backdrop blur) -->
    <dialog id="confirm-modal" class="custom-confirm">
        <h3 id="confirm-title" class="confirm-title">¿Eliminar ingrediente?</h3>
        <p id="confirm-text" class="confirm-text">Esta acción no se puede deshacer y desvinculará este ingrediente de todas sus recetas.</p>
        <footer class="confirm-actions">
            <menu style="list-style: none; display: flex; gap: 0.75rem; padding: 0; margin: 0;">
                <li>
                    <button type="button" class="btn-cancel" onclick="closeConfirmModal()">Cancelar</button>
                </li>
                <li>
                    <button type="button" class="btn-save" style="background: var(--accent-magenta); box-shadow: 0 4px 12px rgba(216,27,96,0.2);" id="confirm-delete-btn">Eliminar</button>
                </li>
            </menu>
        </footer>
    </dialog>

    <!-- Script para control del diálogo, alternancia de vistas, ordenación y búsqueda instantánea -->
    <script>
        let formToSubmit = null;

        function confirmDelete(name, formId) {
            formToSubmit = document.getElementById(formId);
            const modal = document.getElementById('confirm-modal');
            const textEl = document.getElementById('confirm-text');
            textEl.innerHTML = `¿Estás seguro de que deseas eliminar permanentemente el ingrediente <strong>"${name}"</strong> del catálogo? Esta acción desvinculará el ingrediente de todos los platillos asociados.`;
            
            modal.showModal();
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirm-modal');
            modal.close();
            formToSubmit = null;
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', () => {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });

        // ==========================================
        // SISTEMA DE ALTERNANCIA DE VISTAS
        // ==========================================
        const gridViewBtn = document.getElementById('grid-view-btn');
        const listViewBtn = document.getElementById('list-view-btn');
        const gridView = document.getElementById('ingredients-grid-view');
        const listView = document.getElementById('ingredients-list-view');

        gridViewBtn.addEventListener('click', () => {
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');
            gridView.style.display = 'grid';
            listView.style.display = 'none';
            localStorage.setItem('ingredientes_view', 'grid');
        });

        listViewBtn.addEventListener('click', () => {
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
            listView.style.display = 'block';
            gridView.style.display = 'none';
            localStorage.setItem('ingredientes_view', 'list');
        });

        // Restaurar preferencia del usuario
        const savedView = localStorage.getItem('ingredientes_view');
        if (savedView === 'list') {
            listViewBtn.click();
        }

        // ==========================================
        // BÚSQUEDA INSTANTÁNEA EN TIEMPO REAL
        // ==========================================
        const searchInput = document.getElementById('search-input');
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            
            // Grid
            const cards = document.querySelectorAll('.ingrediente-card');
            cards.forEach(card => {
                const name = card.getAttribute('data-nombre').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                card.style.display = name.includes(query) ? 'flex' : 'none';
            });

            // Tabla
            const rows = document.querySelectorAll('.table-row-item');
            rows.forEach(row => {
                const name = row.getAttribute('data-nombre').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                row.style.display = name.includes(query) ? 'table-row' : 'none';
            });
        });

        // ==========================================
        // ORDENAMIENTO DINÁMICO (A-Z y Z-A)
        // ==========================================
        const sortSelect = document.getElementById('sort-select');
        sortSelect.addEventListener('change', () => {
            const order = sortSelect.value;

            // Ordenar Grid
            const cards = Array.from(gridView.querySelectorAll('.ingrediente-card'));
            if (cards.length > 0) {
                cards.sort((a, b) => {
                    const nameA = a.getAttribute('data-nombre').toLowerCase();
                    const nameB = b.getAttribute('data-nombre').toLowerCase();
                    return order === 'asc' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
                });
                cards.forEach(card => gridView.appendChild(card));
            }

            // Ordenar Tabla
            const tableBody = document.getElementById('ingredients-table-body');
            const rows = Array.from(tableBody.querySelectorAll('.table-row-item'));
            if (rows.length > 0) {
                rows.sort((a, b) => {
                    const nameA = a.getAttribute('data-nombre').toLowerCase();
                    const nameB = b.getAttribute('data-nombre').toLowerCase();
                    return order === 'asc' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
                });
                rows.forEach(row => tableBody.appendChild(row));
            }
        });
    </script>
</body>
</html>
