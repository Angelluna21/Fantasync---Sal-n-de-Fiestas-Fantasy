<section class="contract-card menu-builder-card">
    <form wire:submit.prevent="guardarMenu" class="contract-form">
        
        <fieldset class="form-section">
            <legend>Modalidad del Banquete</legend>
            <section class="input-grid grid-2 grid-margin-1">
                @foreach($servicios as $servicio)
                <article class="input-wrapper checkbox-wrapper servicio-card {{ $servicio_id == $servicio->id ? 'selected' : '' }}">
                    <label for="servicio_{{ $servicio->id }}" class="checkbox-label servicio-label">
                        <input type="radio" id="servicio_{{ $servicio->id }}" value="{{ $servicio->id }}" wire:model.live="servicio_id" class="servicio-radio">
                        {{ $servicio->nombre }}
                    </label>
                </article>
                @endforeach
            </section>
            @error('servicio_id') 
                <span class="error-msg-1">{{ $message }}</span> 
            @enderror
        </fieldset>

        @if($servicio_id == 1)
        <fieldset class="form-section animated-section" wire:key="servicio-1-section">
            <legend>Selección de Guisados</legend>
            <p class="guisado-subtitle">Elige entre 5 y 7 platillos para tu taquiza.</p>
            
            <section class="input-grid grid-3">
                @foreach($categorias->where('nombre', 'GUISADOS')->first()->platillos ?? [] as $guisado)
                <article class="input-wrapper checkbox-wrapper guisado-card {{ in_array($guisado->id, $guisados) ? 'selected' : '' }}">
                    <label for="guisado_{{ $guisado->id }}" class="checkbox-label guisado-label">
                        <input type="checkbox" id="guisado_{{ $guisado->id }}" value="{{ $guisado->id }}" wire:model.live="guisados">
                        {{ $guisado->nombre }}
                    </label>
                </article>
                @endforeach
            </section>
            @error('guisados') 
                <span class="error-msg-2">{{ $message }}</span> 
            @enderror
        </fieldset>
        @endif

        @if($servicio_id == 2)
        <fieldset class="form-section animated-section" wire:key="servicio-2-section">
            <legend>Menú Estructurado por Tiempos</legend>
            
            <section class="input-grid grid-2 grid-margin-2">
                <article class="input-wrapper">
                    <label class="tiempo-label">1er Tiempo (Entrada o Crema)</label>
                    <select wire:model="entrada_id" class="form-control select-margin">
                        <option value="">-- Selecciona una entrada --</option>
                        @foreach($categorias->where('nombre', 'ENTRADAS')->first()->platillos ?? [] as $entrada)
                            <option value="{{ $entrada->id }}">{{ $entrada->nombre }}</option>
                        @endforeach
                    </select>
                    @error('entrada_id') 
                        <span class="error-msg-3">{{ $message }}</span> 
                    @enderror
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">2do Tiempo (Plato Fuerte)</label>
                    <select wire:model="plato_fuerte_id" class="form-control select-margin">
                        <option value="">-- Selecciona el plato fuerte --</option>
                        @foreach($categorias->where('nombre', 'PLATOS FUERTES')->first()->platillos ?? [] as $fuerte)
                            <option value="{{ $fuerte->id }}">{{ $fuerte->nombre }}</option>
                        @endforeach
                    </select>
                    @error('plato_fuerte_id') 
                        <span class="error-msg-3">{{ $message }}</span> 
                    @enderror
                </article>
            </section>
        </fieldset>
        @endif

        <footer class="footer-submit">
            <button type="submit" class="btn-submit">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" class="icon-submit">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Confirmar Menú y Generar Comanda
            </button>
        </footer>
    </form>
</section>