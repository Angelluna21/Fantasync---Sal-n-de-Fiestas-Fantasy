<section class="comanda-rapida-container comanda-builder-wrapper">
    <header wire:click="toggleExpanded" class="comanda-builder-header">
        <h3 class="comanda-builder-title">
            Cotización de Banquetes y Comandas Especiales
        </h3>
        <span class="comanda-builder-icon">
            @if($isExpanded)
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            @endif
        </span>
    </header>

    @if($isExpanded)
    <article class="comanda-builder-body">
        <form wire:submit.prevent="generarComanda">
            <fieldset class="comanda-builder-form-grid" style="border: none; padding: 0; margin-bottom: 1.5rem;">
                <!-- Adultos -->
                <p style="margin: 0;">
                    <label class="comanda-builder-label">Cantidad de Adultos</label>
                    <input type="number" wire:model="adultos" min="0" class="comanda-builder-input">
                    @error('adultos') <span class="comanda-builder-error">{{ $message }}</span> @enderror
                </p>
                
                <!-- Niños -->
                <p style="margin: 0;">
                    <label class="comanda-builder-label">Cantidad de Niños</label>
                    <input type="number" wire:model="ninos" min="0" class="comanda-builder-input">
                    @error('ninos') <span class="comanda-builder-error">{{ $message }}</span> @enderror
                </p>
            </fieldset>

            <!-- Platillos Categorizados (Checkboxes) -->
            <section style="margin-bottom: 2rem;">
                <label class="comanda-builder-section-title">
                    Selecciona los Platillos para la Comanda
                </label>
                
                @error('platillosSeleccionados') 
                    <aside class="comanda-builder-error-box">
                        {{ $message }}
                    </aside> 
                @enderror

                <section class="comanda-builder-categories-grid">
                    @foreach($platillosAgrupados as $categoria => $platillos)
                        <article class="comanda-builder-category-card">
                            <h4 class="comanda-builder-category-title">
                                {{ $categoria }}
                            </h4>
                            
                            <fieldset class="comanda-builder-items-container" style="border: none; padding: 0;">
                                @foreach($platillos as $platillo)
                                    <label class="comanda-builder-item-label">
                                        <input type="checkbox" wire:model="platillosSeleccionados" value="{{ $platillo->id }}" class="comanda-builder-checkbox">
                                        <span style="line-height: 1.4;">{{ $platillo->nombre }}</span>
                                    </label>
                                @endforeach
                            </fieldset>
                        </article>
                    @endforeach
                </section>
            </section>

            <footer class="comanda-builder-btn-container">
                <button type="submit" class="comanda-builder-btn">
                    Generar Lista de Compras
                </button>
            </footer>
        </form>
    </article>
    @endif
</section>
