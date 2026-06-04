<section class="contract-card" style="box-shadow: none; padding: 0;">
    <form wire:submit.prevent="guardarMenu" class="contract-form">
        
        <fieldset class="form-section">
            <legend>Modalidad del Banquete</legend>
            <section class="input-grid grid-2" style="margin-top: 1rem;">
                @foreach($servicios as $servicio)
                <article class="input-wrapper checkbox-wrapper" style="padding: 1.5rem; background: var(--surface-card); border: 2px solid var(--border-light); border-radius: 12px; transition: all 0.3s ease;">
                    <label class="checkbox-label" style="font-weight: 600; font-size: 1.1rem; width: 100%; display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                        <input type="radio" value="{{ $servicio->id }}" wire:model.live="servicio_id" style="transform: scale(1.5);">
                        {{ $servicio->nombre }}
                    </label>
                </article>
                @endforeach
            </section>
            @error('servicio_id') 
                <span style="color: #dc3545; font-weight: 600; display: block; margin-top: 0.75rem;">{{ $message }}</span> 
            @enderror
        </fieldset>

        @if($servicio_id == 1)
        <fieldset class="form-section">
            <legend>Selección de Guisados</legend>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">Elige entre 5 y 7 platillos para tu taquiza.</p>
            
            <section class="input-grid grid-3">
                @foreach($categorias->where('nombre', 'GUISADOS')->first()->platillos ?? [] as $guisado)
                <article class="input-wrapper checkbox-wrapper" style="background: var(--bg-body); border-radius: 8px; padding: 0.75rem;">
                    <label class="checkbox-label" style="cursor: pointer; width: 100%;">
                        <input type="checkbox" value="{{ $guisado->id }}" wire:model="guisados">
                        {{ $guisado->nombre }}
                    </label>
                </article>
                @endforeach
            </section>
            @error('guisados') 
                <span style="color: #dc3545; font-weight: 600; display: block; margin-top: 1rem;">{{ $message }}</span> 
            @enderror
        </fieldset>
        @endif

        @if($servicio_id == 2)
        <fieldset class="form-section">
            <legend>Menú Estructurado por Tiempos</legend>
            
            <section class="input-grid grid-2" style="margin-top: 1.5rem;">
                <article class="input-wrapper">
                    <label style="font-weight: 600; color: var(--primary-dark);">1er Tiempo (Entrada o Crema)</label>
                    <select wire:model="entrada_id" class="form-control" style="margin-top: 0.5rem;">
                        <option value="">-- Selecciona una entrada --</option>
                        @foreach($categorias->where('nombre', 'ENTRADAS')->first()->platillos ?? [] as $entrada)
                            <option value="{{ $entrada->id }}">{{ $entrada->nombre }}</option>
                        @endforeach
                    </select>
                    @error('entrada_id') 
                        <span style="color: #dc3545; font-weight: 600; display: block; margin-top: 0.5rem;">{{ $message }}</span> 
                    @enderror
                </article>

                <article class="input-wrapper">
                    <label style="font-weight: 600; color: var(--primary-dark);">2do Tiempo (Plato Fuerte)</label>
                    <select wire:model="plato_fuerte_id" class="form-control" style="margin-top: 0.5rem;">
                        <option value="">-- Selecciona el plato fuerte --</option>
                        @foreach($categorias->where('nombre', 'PLATOS FUERTES')->first()->platillos ?? [] as $fuerte)
                            <option value="{{ $fuerte->id }}">{{ $fuerte->nombre }}</option>
                        @endforeach
                    </select>
                    @error('plato_fuerte_id') 
                        <span style="color: #dc3545; font-weight: 600; display: block; margin-top: 0.5rem;">{{ $message }}</span> 
                    @enderror
                </article>
            </section>
        </fieldset>
        @endif

        <footer style="margin-top: 2rem; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn-submit">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20" style="margin-right: 0.5rem; vertical-align: text-bottom;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Confirmar Menú y Generar Comanda
            </button>
        </footer>
    </form>
</section>