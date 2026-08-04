<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendedoras · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/salones.css'])
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <!-- Navegación superior y Encabezado Unificado -->
        <nav class="top-nav" style="align-items: flex-start; margin-bottom: 2rem; padding-bottom: 0;">
            <!-- Lado Izquierdo: Logo y Botones Nav -->
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                
                <section style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('dashboard') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Panel
                    </a>
                </section>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 3rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none; text-align: center;">
                <hgroup style="text-align: center;">
                    <p class="eyebrow" style="margin: 0 auto; color: rgba(255, 255, 255, 0.8);">Gestión de Personal</p>
                    <h1 class="dashboard-title" style="margin: 0 auto; font-size: 2.5rem; color: white;">Vendedoras</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; max-width: 600px; color: rgba(255, 255, 255, 0.9);">Administra las vendedoras, su información de contacto y estado activo.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <aside style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </aside>
        </nav>

        <!-- Mensajes de éxito -->
        @if (session('success'))
            <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.5); color: #fff; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Sección de acciones -->
        <section class="salones-section" aria-label="Gestión de vendedoras">
            <menu class="action-bar" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                
                <!-- Buscador -->
                <div style="flex: 1; min-width: 250px; max-width: 400px; position: relative;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; color: #9ca3af;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="search-vendedoras" placeholder="Buscar vendedora por nombre..." style="width: 100%; padding: 0.8rem 1rem 0.8rem 2.8rem; border-radius: 12px; border: 1px solid rgba(122, 40, 138, 0.2); background: rgba(255,255,255,0.9); font-size: 0.95rem; outline: none; color: #3d1b4a; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: border-color 0.2s;">
                </div>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="{{ route('vendedoras.create') }}" class="btn-create">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Registrar Vendedora
                    </a>
                    <a href="{{ route('vendedoras.estadisticas') }}" class="btn-create" style="background: linear-gradient(to right, #4b5563, #374151); box-shadow: 0 4px 15px rgba(55, 65, 81, 0.4);">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Estadísticas y Reporte
                    </a>
                </div>
            </menu>

            <!-- Grid de vendedoras -->
            <section class="salones-grid">
                @forelse($vendedoras as $vendedora)
                    <article class="salon-card">
                        <header class="card-header">
                            <hgroup class="header-content">
                                <h2 class="salon-name">{{ $vendedora->nombre }} {{ $vendedora->apellidos }}</h2>
                                @if($vendedora->email)
                                    <span class="salon-alias" style="display: block; margin-top: 4px; font-size: 0.85rem;">{{ $vendedora->email }}</span>
                                @endif
                            </hgroup>
                        </header>

                        <section class="card-body">
                            <section class="salon-info salon-info-grid">
                                <article>
                                    <p class="info-label">Teléfono</p>
                                    <p class="info-value info-value-highlight">
                                        {{ $vendedora->telefono ? $vendedora->telefono : 'No registrado' }}
                                    </p>
                                </article>
                                <article>
                                    <p class="info-label">Estado</p>
                                    <span class="status-badge status-{{ $vendedora->estado === 'activo' ? 'verde' : 'rojo' }} status-badge-inline">
                                        {{ $vendedora->estado === 'activo' ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </article>
                            </section>
                        </section>

                        <footer class="card-footer">
                            <a href="{{ route('vendedoras.show', $vendedora->id) }}" class="btn-action btn-view" title="Consultar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <a href="{{ route('vendedoras.edit', $vendedora->id) }}" class="btn-action btn-edit" title="Editar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <button type="button" class="btn-action btn-delete" title="Eliminar" onclick="confirmDelete('{{ route('vendedoras.destroy', $vendedora->id) }}', '{{ $vendedora->nombre }} {{ $vendedora->apellidos }}')">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </footer>
                    </article>
                @empty
                    <section class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <h3>No hay vendedoras registradas</h3>
                        <p>Registra una vendedora para comenzar a gestionar tu equipo.</p>
                        <a href="{{ route('vendedoras.create') }}" class="btn-create-empty">Registrar Primera Vendedora</a>
                    </section>
                @endforelse
            </section>
        </section>
    </main>

    <!-- Footer -->
    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
    
    <!-- Diálogo de Confirmación Customizado -->
    <dialog id="delete-confirm-dialog" class="delete-dialog">
        <section class="dialog-content">
            <figure class="dialog-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </figure>
            <h3 class="dialog-title">¿Eliminar Vendedora?</h3>
            <p class="dialog-message">¿Estás seguro de que deseas eliminar a la vendedora <strong id="delete-vendedora-name"></strong>? Esta acción la enviará a la papelera.</p>
            
            <form id="delete-confirm-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <menu class="dialog-actions">
                    <button type="button" class="btn-dialog-cancel" onclick="closeDeleteDialog()">Cancelar</button>
                    <button type="submit" class="btn-dialog-confirm">Eliminar</button>
                </menu>
            </form>
        </section>
    </dialog>

    <script>
        const deleteDialog = document.getElementById('delete-confirm-dialog');
        const deleteForm = document.getElementById('delete-confirm-form');
        const deleteVendedoraNameEl = document.getElementById('delete-vendedora-name');

        function confirmDelete(url, name) {
            if (deleteDialog && deleteForm && deleteVendedoraNameEl) {
                deleteForm.action = url;
                deleteVendedoraNameEl.textContent = name;
                deleteDialog.showModal();
            }
        }

        function closeDeleteDialog() {
            if (deleteDialog) {
                deleteDialog.close();
            }
        }

        // Cerrar dialog haciendo clic en el backdrop
        if (deleteDialog) {
            deleteDialog.addEventListener('click', (e) => {
                const dialogDimensions = deleteDialog.getBoundingClientRect();
                if (
                    e.clientX < dialogDimensions.left ||
                    e.clientX > dialogDimensions.right ||
                    e.clientY < dialogDimensions.top ||
                    e.clientY > dialogDimensions.bottom
                ) {
                    deleteDialog.close();
                }
            });
        }

        // Buscador de Vendedoras
        const searchInput = document.getElementById('search-vendedoras');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase();
                const cards = document.querySelectorAll('.salon-card');
                
                cards.forEach(card => {
                    const name = card.querySelector('.salon-name')?.textContent.toLowerCase() || '';
                    const email = card.querySelector('.salon-alias')?.textContent.toLowerCase() || '';
                    
                    if (name.includes(term) || email.includes(term)) {
                        card.style.display = 'flex'; // o block según tu css original
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    </script>
</body>
</html>
