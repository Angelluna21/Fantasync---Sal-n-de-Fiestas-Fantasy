<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios Gastronómicos · FantaSync</title>
    <meta name="description" content="Catálogo de servicios gastronómicos — FantaSync Sistema de Gestión de Eventos Gastronómicos">
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/platillos.css'])
</head>

<body>
    <!-- Fondo decorativo -->
    <figure class="dashboard-background" aria-hidden="true"></figure>

    <main class="dashboard-layout">
        <!-- Navegación superior y Encabezado Unificado -->
        <section class="top-nav" aria-label="Menú superior" style="align-items: flex-start;">
            <!-- Lado Izquierdo: Logo y Botón Volver -->
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                <a href="{{ route('dashboard') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Panel
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 2rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Administración de Menú</p>
                    <h1 class="dashboard-title" style="font-size: 2.8rem; margin-top: 0.2rem;">Servicios Gastronómicos</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.1rem;">Gestiona los tipos de servicio (ej. Menús a 2 Tiempos, Taquizas) que puedes ofrecer en tus eventos.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <section style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </section>
        </section>

        @if (session('success'))
            <p class="alert-success-msg">
                {{ session('success') }}
            </p>
        @endif

        @if (session('error'))
            <p class="alert-error-msg">
                {{ session('error') }}
            </p>
        @endif

        <!-- Contenedor Principal -->
        <section class="platillos-section" aria-label="Listado de servicios gastronómicos" style="margin-top: 7rem;">

            <menu class="action-bar" aria-label="Acciones del catálogo">
                <li>
                    <h2 class="section-title">Catálogo de Servicios</h2>
                </li>
                <li>
                    <nav class="action-controls" aria-label="Controles del listado">
                        <!-- Búsqueda -->
                        <input type="text" id="search-input" class="search-bar-input" placeholder="Buscar servicio...">

                        <!-- Ordenamiento -->
                        <select id="sort-select" class="sort-bar-select" aria-label="Criterio de ordenación">
                            <option value="asc">A-Z (Ascendente)</option>
                            <option value="desc">Z-A (Descendente)</option>
                        </select>

                        <!-- Conmutador de Vistas -->
                        <nav class="view-switcher" aria-label="Cambio de vista">
                            <button type="button" id="grid-view-btn" class="btn-switch active" title="Vista Cuadrícula">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </button>
                            <button type="button" id="list-view-btn" class="btn-switch" title="Vista Lista">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </nav>

                        <a href="{{ route('servicios-gastronomicos.create') }}" class="btn-create">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Crear Servicio
                        </a>
                    </nav>
                </li>
            </menu>

            <!-- 1. Vista de Cuadrícula (Grid) -->
            <section id="platillos-grid-view" aria-label="Servicios en Cuadrícula">
                @if($servicios->isEmpty())
                    <article class="empty-state" id="empty-state-card">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3>No hay servicios registrados</h3>
                        <p>Crea tu primer servicio gastronómico para empezar a clasificar tus platillos.</p>
                        <a href="{{ route('servicios-gastronomicos.create') }}" class="btn-create">Crear Primer Servicio</a>
                    </article>
                @else
                    <section class="platillos-grid group-container">
                        @foreach($servicios as $servicio)
                            <article class="platillo-card" data-nombre="{{ $servicio->nombre }}">
                                <header class="card-header">
                                    <h3 class="card-title">{{ $servicio->nombre }}</h3>
                                    <span class="badge">Servicio</span>
                                </header>

                                <section class="card-body">
                                    <p class="card-description">Creado el {{ $servicio->created_at?->format('d M Y') ?? 'Fecha no disponible' }}</p>
                                </section>

                                <footer class="card-footer">
                                    <menu class="card-actions-menu">
                                        <li>
                                            <a href="{{ route('servicios-gastronomicos.show', $servicio->id) }}" class="btn-action btn-view" title="Ver Servicio">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('servicios-gastronomicos.edit', $servicio->id) }}" class="btn-action btn-edit" title="Editar Servicio">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        </li>
                                        <li>
                                            <button type="button" class="btn-action btn-delete" title="Eliminar" onclick="confirmDelete('{{ $servicio->nombre }}', 'delete-form-{{ $servicio->id }}')">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </li>
                                    </menu>

                                    <form id="delete-form-{{ $servicio->id }}" action="{{ route('servicios-gastronomicos.destroy', $servicio->id) }}" method="POST" class="form-delete" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </footer>
                            </article>
                        @endforeach
                    </section>
                @endif
            </section>

            <!-- 2. Vista de Lista (Tabla) -->
            <section class="table-container" id="platillos-list-view" style="display: none;" aria-label="Servicios en Lista">
                <table class="platillos-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Creado</th>
                            <th class="table-th-actions">Acciones</th>
                        </tr>
                    </thead>
                    @if($servicios->isEmpty())
                        <tbody>
                            <tr class="table-empty">
                                <td colspan="3" class="table-empty-td">No hay servicios registrados.</td>
                            </tr>
                        </tbody>
                    @else
                        <tbody class="group-container">
                            @foreach($servicios as $servicio)
                                <tr class="table-row-item" data-nombre="{{ $servicio->nombre }}">
                                    <td class="col-name">{{ $servicio->nombre }}</td>
                                    <td>{{ $servicio->created_at?->format('d M Y') ?? 'N/D' }}</td>
                                    <td class="col-actions">
                                        <menu class="table-actions-menu">
                                            <li>
                                                <a href="{{ route('servicios-gastronomicos.show', $servicio->id) }}" class="btn-action btn-view" title="Ver Servicio">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('servicios-gastronomicos.edit', $servicio->id) }}" class="btn-action btn-edit" title="Editar Servicio">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" class="btn-action btn-delete" title="Eliminar" onclick="confirmDelete('{{ $servicio->nombre }}', 'delete-form-{{ $servicio->id }}')">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </li>
                                        </menu>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </section>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>

    <!-- Diálogo de Confirmación Custom -->
    <dialog id="confirm-modal" class="custom-confirm">
        <h3 id="confirm-title" class="confirm-title">¿Eliminar servicio?</h3>
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
            const textEl = document.getElementById('confirm-text');
            textEl.innerHTML = `¿Estás seguro de que deseas eliminar permanentemente el servicio <strong>"${name}"</strong>?`;
            modal.showModal();
        }

        function closeConfirmModal() {
            document.getElementById('confirm-modal').close();
            formToSubmit = null;
        }

        document.getElementById('confirm-delete-btn').addEventListener('click', () => {
            if (formToSubmit) formToSubmit.submit();
        });

        // Alternancia de vistas
        const gridViewBtn = document.getElementById('grid-view-btn');
        const listViewBtn = document.getElementById('list-view-btn');
        const gridView = document.getElementById('platillos-grid-view');
        const listView = document.getElementById('platillos-list-view');

        gridViewBtn.addEventListener('click', () => {
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');
            gridView.style.display = 'block';
            listView.style.display = 'none';
            localStorage.setItem('servicios_view', 'grid');
        });

        listViewBtn.addEventListener('click', () => {
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
            listView.style.display = 'block';
            gridView.style.display = 'none';
            localStorage.setItem('servicios_view', 'list');
        });

        const savedView = localStorage.getItem('servicios_view');
        if (savedView === 'list') listViewBtn.click();

        // Búsqueda instantánea
        const searchInput = document.getElementById('search-input');
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

            document.querySelectorAll('.platillo-card').forEach(card => {
                const name = card.getAttribute('data-nombre').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                card.style.display = name.includes(query) ? 'flex' : 'none';
            });

            document.querySelectorAll('.table-row-item').forEach(row => {
                const name = row.getAttribute('data-nombre').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                row.style.display = name.includes(query) ? 'table-row' : 'none';
            });
        });

        // Ordenamiento
        const sortSelect = document.getElementById('sort-select');
        sortSelect.addEventListener('change', () => {
            const order = sortSelect.value;

            document.querySelectorAll('#platillos-grid-view .group-container').forEach(container => {
                const cards = Array.from(container.querySelectorAll('.platillo-card'));
                cards.sort((a, b) => {
                    const nameA = a.getAttribute('data-nombre').toLowerCase();
                    const nameB = b.getAttribute('data-nombre').toLowerCase();
                    return order === 'asc' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
                });
                cards.forEach(card => container.appendChild(card));
            });

            document.querySelectorAll('#platillos-list-view .group-container').forEach(container => {
                const rows = Array.from(container.querySelectorAll('.table-row-item'));
                rows.sort((a, b) => {
                    const nameA = a.getAttribute('data-nombre').toLowerCase();
                    const nameB = b.getAttribute('data-nombre').toLowerCase();
                    return order === 'asc' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
                });
                rows.forEach(row => container.appendChild(row));
            });
        });
    </script>
</body>

</html>