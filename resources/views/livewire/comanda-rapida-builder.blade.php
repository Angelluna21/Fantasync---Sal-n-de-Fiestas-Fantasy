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
            <fieldset class="comanda-builder-form-grid comanda-builder-fieldset-grid">
                <!-- Cliente -->
                <p class="comanda-builder-input-group">
                    <label class="comanda-builder-label">Nombre del Cliente (Opcional)</label>
                    <input type="text" wire:model="nombreCliente" class="comanda-builder-input" placeholder="Ej. Juan Pérez">
                    @error('nombreCliente') <span class="comanda-builder-error">{{ $message }}</span> @enderror
                </p>
                
                <!-- Fecha -->
                <p class="comanda-builder-input-group">
                    <label class="comanda-builder-label">Fecha del Evento *</label>
                    <input type="date" wire:model="fechaEvento" class="comanda-builder-input" required>
                    @error('fechaEvento') <span class="comanda-builder-error">{{ $message }}</span> @enderror
                </p>

                <!-- Teléfono -->
                <p class="comanda-builder-input-group">
                    <label class="comanda-builder-label">Teléfono (Opcional)</label>
                    <input type="text" wire:model="telefono" class="comanda-builder-input" placeholder="Ej. 555-1234">
                    @error('telefono') <span class="comanda-builder-error">{{ $message }}</span> @enderror
                </p>

                <!-- Salón -->
                <p class="comanda-builder-input-group">
                    <label class="comanda-builder-label">Salón Asignado (Opcional)</label>
                    <select wire:model="salon_id" class="comanda-builder-input comanda-builder-select-padding">
                        <option value="">-- Ninguno / No aplica --</option>
                        @foreach($salones as $salon)
                            <option value="{{ $salon->id }}">{{ $salon->nombre }}</option>
                        @endforeach
                    </select>
                    @error('salon_id') <span class="comanda-builder-error">{{ $message }}</span> @enderror
                </p>

                <!-- Es Externo -->
                <p class="comanda-builder-checkbox-group">
                    <input type="checkbox" wire:model="es_externo" id="es_externo_chk" class="comanda-builder-checkbox-large">
                    <label for="es_externo_chk" class="comanda-builder-label comanda-builder-label-pointer">
                        Banquete Externo (Solo señalización)
                    </label>
                </p>

                <!-- Adultos -->
                <p class="comanda-builder-input-group">
                    <label class="comanda-builder-label">Cantidad de Adultos</label>
                    <input type="number" wire:model="adultos" min="0" class="comanda-builder-input">
                    @error('adultos') <span class="comanda-builder-error">{{ $message }}</span> @enderror
                </p>
                
                <!-- Niños -->
                <p class="comanda-builder-input-group">
                    <label class="comanda-builder-label">Cantidad de Niños</label>
                    <input type="number" wire:model="ninos" min="0" class="comanda-builder-input">
                    @error('ninos') <span class="comanda-builder-error">{{ $message }}</span> @enderror
                </p>
            </fieldset>

            <!-- Platillos Categorizados (Checkboxes) -->
            <section class="comanda-builder-section-margin">
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
                            
                            <fieldset class="comanda-builder-items-container comanda-builder-items-fieldset">
                                @foreach($platillos as $platillo)
                                    <label class="comanda-builder-item-label">
                                        <input type="checkbox" wire:model="platillosSeleccionados" value="{{ $platillo->id }}" class="comanda-builder-checkbox">
                                        <span class="comanda-builder-item-text">{{ $platillo->nombre }}</span>
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
