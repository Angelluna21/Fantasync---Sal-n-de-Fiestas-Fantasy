<section class="gestion-categorias">
    <nav class="action-bar" style="display: flex; gap: 1rem; align-items: center; justify-content: space-between; flex-wrap: wrap;">
        <span style="display: block; flex: 1; min-width: 250px;">
            <input type="search" wire:model.live="search" placeholder="Buscar categoría..." style="width: 100%; padding: 0.8rem 1.5rem; border-radius: 50px; border: none; background: rgba(255,255,255,0.9); box-shadow: 0 4px 6px rgba(0,0,0,0.05); outline: none; font-family: inherit;">
        </span>
        
        <button wire:click="$set('formVisible', true)" class="btn-create" style="border: none; cursor: pointer; font-family: inherit;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Crear Categoría
        </button>
    </nav>

    @if($formVisible)
        <article class="sucursal-card" style="margin-bottom: 2rem; width: 100%; max-width: 100%; background: white; margin-top: 1rem;">
            <header class="card-header" style="border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                <h2 class="card-title" style="font-size: 1.25rem; color: var(--primary-purple);">
                    {{ $categoria_id ? 'Editar Categoría' : 'Nueva Categoría' }}
                </h2>
            </header>
            
            <form wire:submit.prevent="guardarCategoria" style="display: flex; gap: 1.5rem; flex-wrap: wrap; align-items: flex-end;">
                
                <fieldset style="border: none; padding: 0; margin: 0; flex: 2; min-width: 200px;">
                    <label for="nombre_categoria" style="font-size: 0.85rem; font-weight: 600; color: #666; margin-bottom: 0.5rem; display: block;">
                        Nombre de la Categoría
                    </label>
                    <input id="nombre_categoria" wire:model="nombre" type="text" placeholder="Ej. Postres" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #ddd; outline: none;">
                </fieldset>
                
                <fieldset style="border: none; padding: 0; margin: 0; flex: 1; min-width: 100px;">
                    <label for="orden_categoria" style="font-size: 0.85rem; font-weight: 600; color: #666; margin-bottom: 0.5rem; display: block;">
                        Orden
                    </label>
                    <input id="orden_categoria" wire:model="orden" type="number" placeholder="Ej. 1" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #ddd; outline: none;">
                </fieldset>
                
                <fieldset style="border: none; padding: 0; margin: 0; flex: 1; min-width: 200px; display: flex; gap: 0.5rem;">
                    <button type="submit" style="flex: 1; padding: 0.75rem; border-radius: 8px; border: none; background: var(--primary-purple); color: white; font-weight: bold; cursor: pointer; transition: 0.3s;">
                        {{ $categoria_id ? 'Actualizar' : 'Guardar' }}
                    </button>
                    <button type="button" wire:click="$set('formVisible', false)" style="flex: 1; padding: 0.75rem; border-radius: 8px; border: 1px solid #ddd; background: white; color: #666; font-weight: bold; cursor: pointer; transition: 0.3s;">
                        Cancelar
                    </button>
                </fieldset>
            </form>
        </article>
    @endif

    <section class="sucursales-grid">
        @forelse($categorias as $cat)
            <article class="sucursal-card">
                <header class="card-header">
                    <h2 class="card-title">{{ $cat->nombre }}</h2>
                    <span class="location-badge">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </span>
                </header>

                <main class="card-body">
                    <section class="salons-section">
                        <p class="salons-label">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="vertical-align: middle; margin-right: 4px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                            </svg>
                            Orden de visualización
                        </p>
                        <span class="salons-list" style="display: block; margin-top: 0.5rem;">
                            <span class="salon-badge">Posición #{{ $cat->orden }}</span>
                        </span>
                    </section>
                </main>

                <footer class="card-footer">
                    <button wire:click="edit({{ $cat->id }})" class="btn-action btn-edit" title="Editar" style="border: none; background: transparent; cursor: pointer;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    
                    <button wire:click="delete({{ $cat->id }})" class="btn-action btn-delete" title="Eliminar" onclick="confirm('¿Estás segura de eliminar esta categoría?') || event.stopImmediatePropagation()" style="border: none; background: transparent; cursor: pointer;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </footer>
            </article>
        @empty
            <section class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="48" height="48" style="color: #ccc; margin-bottom: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <h3 style="margin-bottom: 0.5rem; color: #333;">No hay categorías registradas</h3>
                <p style="color: #666; margin-bottom: 1.5rem;">Crea una nueva categoría para organizar los menús.</p>
                <button wire:click="$set('formVisible', true)" class="btn-create-empty" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: none; background: var(--primary-purple); color: white; cursor: pointer; font-weight: bold;">
                    Crear Primera Categoría
                </button>
            </section>
        @endforelse
    </section>
</section>