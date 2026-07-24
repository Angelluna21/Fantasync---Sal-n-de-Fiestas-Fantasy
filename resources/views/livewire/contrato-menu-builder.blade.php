<section class="contract-card menu-builder-card">
    <form wire:submit.prevent="guardarMenu" class="contract-form">
        
        <fieldset class="form-section">
            <legend>Modalidad del Banquete</legend>
            <section class="input-grid grid-2 grid-margin-1">
                @foreach($servicios as $servicio)
                <article class="input-wrapper checkbox-wrapper servicio-card {{ $servicio_id == $servicio->id ? 'selected' : '' }}" style="{{ $servicio_id != $servicio->id ? 'opacity: 0.6; cursor: not-allowed;' : '' }}">
                    <label for="servicio_{{ $servicio->id }}" class="checkbox-label servicio-label" style="cursor: {{ $servicio_id != $servicio->id ? 'not-allowed' : 'default' }};">
                        <input type="radio" id="servicio_{{ $servicio->id }}" value="{{ $servicio->id }}" wire:model="servicio_id" class="servicio-radio" disabled>
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
        <fieldset class="form-section animated-section" wire:key="servicio-taquiza-section">
            <legend>Taquiza / Parrillada</legend>
            <section class="input-grid grid-2 grid-margin-2">
                <article class="input-wrapper">
                    <label class="tiempo-label">Guisados</label>
                    <details class="dropdown-multi" style="position: relative;">
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($taquiza_guisados) == 0 ? '-- Seleccionar Guisados --' : count($taquiza_guisados) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['guisados', 'taquiza']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id)) ?? [] as $guisado)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="taquiza_guisados" value="{{ $guisado->id }}"> {{ $guisado->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">Guarniciones (Opcional)</label>
                    <details class="dropdown-multi" style="position: relative;">
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($taquiza_guarniciones) == 0 ? '-- Seleccionar Guarniciones --' : count($taquiza_guarniciones) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['guarniciones']))->flatMap->platillos ?? [] as $guarnicion)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="taquiza_guarniciones" value="{{ $guarnicion->id }}"> {{ $guarnicion->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                </article>
            </section>
        </fieldset>
        @endif

        @if($servicio_id == 2 || $servicio_id == 3)
        <fieldset class="form-section animated-section" wire:key="servicio-tiempos-section">
            <legend>Menú Estructurado por Tiempos</legend>
            
            <section class="input-grid grid-2 grid-margin-2">
                <article class="input-wrapper">
                    <label class="tiempo-label">1er Tiempo (Entrada o Crema)</label>
                    <select wire:model="entrada_id" class="form-control select-margin">
                        <option value="">-- Selecciona una entrada --</option>
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['entradas']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id)) ?? [] as $entrada)
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
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['platos fuertes']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id)) ?? [] as $fuerte)
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
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['postres']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id)) ?? [] as $postre)
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

        @if($servicio_id)
        <fieldset class="form-section animated-section" wire:key="servicio-extras-section">
            <legend>Extras del Banquete</legend>
            <section class="input-grid grid-2 grid-margin-2">
                <article class="input-wrapper">
                    <label class="tiempo-label">Opciones Infantiles (Opcional)</label>
                    <details class="dropdown-multi" style="position: relative;">
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($infantil) == 0 ? '-- Seleccionar menú infantil --' : count($infantil) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['menú infantil', 'menu infantil', 'buffet infantil']))->flatMap->platillos ?? [] as $infantil_opt)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="infantil" value="{{ $infantil_opt->id }}"> {{ $infantil_opt->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">Bebidas (Hasta 2 sabores)</label>
                    <details class="dropdown-multi" style="position: relative;">
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($bebidas) == 0 ? '-- Seleccionar bebidas --' : count($bebidas) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['bebidas']))->flatMap->platillos ?? [] as $bebida_opt)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="bebidas" value="{{ $bebida_opt->id }}" @if(count($bebidas) >= 2 && !in_array($bebida_opt->id, $bebidas)) disabled @endif> {{ $bebida_opt->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </details>
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