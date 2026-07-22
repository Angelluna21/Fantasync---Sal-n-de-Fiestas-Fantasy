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



        @if($servicio_id == 2 || $servicio_id == 3)
        <fieldset class="form-section animated-section" wire:key="servicio-tiempos-section">
            <legend>Menú Estructurado por Tiempos</legend>
            
            <section class="input-grid grid-2 grid-margin-2">
                <article class="input-wrapper">
                    <label class="tiempo-label">1er Tiempo (Entrada o Crema)</label>
                    <select wire:model="entrada_id" class="form-control select-margin">
                        <option value="">-- Selecciona una entrada --</option>
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['entradas']))->flatMap->platillos ?? [] as $entrada)
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
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['platos fuertes']))->flatMap->platillos ?? [] as $fuerte)
                            <option value="{{ $fuerte->id }}">{{ $fuerte->nombre }}</option>
                        @endforeach
                    </select>
                    @error('plato_fuerte_id') 
                        <span class="error-msg-3">{{ $message }}</span> 
                    @enderror
                </article>
                
                @if($servicio_id == 3)
                <article class="input-wrapper">
                    <label class="tiempo-label">3er Tiempo (Postre)</label>
                    <select wire:model="postre_id" class="form-control select-margin">
                        <option value="">-- Selecciona el postre --</option>
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['postres']))->flatMap->platillos ?? [] as $postre)
                            <option value="{{ $postre->id }}">{{ $postre->nombre }}</option>
                        @endforeach
                    </select>
                    @error('postre_id') 
                        <span class="error-msg-3">{{ $message }}</span> 
                    @enderror
                </article>
                @endif
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