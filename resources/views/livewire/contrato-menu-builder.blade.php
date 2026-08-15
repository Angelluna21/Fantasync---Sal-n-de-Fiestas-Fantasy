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
                    <details class="dropdown-multi" style="position: relative;" wire:ignore.self>
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($taquiza_guisados) == 0 ? '-- Seleccionar Guisados --' : count($taquiza_guisados) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['guisados', 'taquiza']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $guisado)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="taquiza_guisados" value="{{ $guisado->id }}"> {{ $guisado->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">Carnes (Parrillada)</label>
                    <details class="dropdown-multi" style="position: relative;" wire:ignore.self>
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($taquiza_parrillada) == 0 ? '-- Seleccionar Carnes --' : count($taquiza_parrillada) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['parrillada (carnes)', 'parrillada', 'parrilladas', 'carnes']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $parrillada)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="taquiza_parrillada" value="{{ $parrillada->id }}"> {{ $parrillada->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">Guarniciones (Opcional)</label>
                    <details class="dropdown-multi" style="position: relative;" wire:ignore.self>
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($taquiza_guarniciones) == 0 ? '-- Seleccionar Guarniciones --' : count($taquiza_guarniciones) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['guarniciones', 'guarniciones (taquiza)', 'guarniciones (formales)', 'guarnicion formal']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $guarnicion)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="taquiza_guarniciones" value="{{ $guarnicion->id }}"> {{ $guarnicion->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                </article>
                <article class="input-wrapper">
                    <label class="tiempo-label">Salsas (Opcional)</label>
                    <details class="dropdown-multi" style="position: relative;" wire:ignore.self>
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($salsas) == 0 ? '-- Seleccionar Salsas --' : count($salsas) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['salsas', 'salsa']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $salsa)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="salsas" value="{{ $salsa->id }}"> {{ $salsa->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">Aderezos (Opcional)</label>
                    <details class="dropdown-multi" style="position: relative;" wire:ignore.self>
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($aderezos) == 0 ? '-- Seleccionar Aderezos --' : count($aderezos) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['aderezos', 'aderezo']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $aderezo)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="aderezos" value="{{ $aderezo->id }}"> {{ $aderezo->nombre }}
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

                @if($servicio_id == 3)
                <article class="input-wrapper">
                    <label class="tiempo-label">1er Tiempo (Crema o Sopa)</label>
                    <select wire:model="crema_sopa_id" class="form-control select-margin">
                        <option value="">-- Selecciona una crema/sopa --</option>
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['cremas y sopas', 'cremas / sopas', 'crema', 'sopa']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $crema)
                            <option value="{{ $crema->id }}">{{ $crema->nombre }}</option>
                        @endforeach
                    </select>
                    @error('crema_sopa_id') 
                        <span class="error-msg-3">{{ $message }}</span> 
                    @enderror
                </article>
                @endif

                @php
                    $entradaCats = ['entradas', 'entrada'];
                    if ($servicio_id == 2) {
                        $entradaCats = array_merge($entradaCats, ['cremas y sopas', 'cremas / sopas', 'crema', 'sopa']);
                    }
                @endphp
                <article class="input-wrapper">
                    <label class="tiempo-label">{{ $servicio_id == 3 ? '2do Tiempo' : '1er Tiempo' }} (Entrada / Pasta / Ensalada{{ $servicio_id == 2 ? ' / Crema o Sopa' : '' }})</label>
                    <select wire:model="entrada_id" class="form-control select-margin">
                        <option value="">-- Selecciona una entrada --</option>
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), $entradaCats))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $entrada)
                            <option value="{{ $entrada->id }}">{{ $entrada->nombre }}</option>
                        @endforeach
                    </select>
                    @error('entrada_id') 
                        <span class="error-msg-3">{{ $message }}</span> 
                    @enderror
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">{{ $servicio_id == 3 ? '3er Tiempo' : '2do Tiempo' }} (Plato Fuerte)</label>
                    <select wire:model="plato_fuerte_id" class="form-control select-margin">
                        <option value="">-- Selecciona el plato fuerte --</option>
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['platos fuertes', 'plato fuerte']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $fuerte)
                            <option value="{{ $fuerte->id }}">{{ $fuerte->nombre }}</option>
                        @endforeach
                    </select>
                    @error('plato_fuerte_id') 
                        <span class="error-msg-3">{{ $message }}</span> 
                    @enderror
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">Guarnición para Plato Fuerte</label>
                    <select wire:model="guarnicion_formal_id" class="form-control select-margin">
                        <option value="">-- Sin guarnición --</option>
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['guarniciones', 'guarniciones (taquiza)', 'guarniciones (formales)', 'guarnicion formal']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $guar)
                            <option value="{{ $guar->id }}">{{ $guar->nombre }}</option>
                        @endforeach
                    </select>
                    @error('guarnicion_formal_id') 
                        <span class="error-msg-3">{{ $message }}</span> 
                    @enderror
                </article>
                <article class="input-wrapper">
                    <label class="tiempo-label">Espejos (Opcional)</label>
                    <details class="dropdown-multi" style="position: relative;" wire:ignore.self>
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($espejos) == 0 ? '-- Seleccionar Espejos --' : count($espejos) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['espejos', 'espejo']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $espejo)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="espejos" value="{{ $espejo->id }}"> {{ $espejo->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">Salsas (Opcional)</label>
                    <details class="dropdown-multi" style="position: relative;" wire:ignore.self>
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($salsas) == 0 ? '-- Seleccionar Salsas --' : count($salsas) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['salsas', 'salsa']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $salsa)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="salsas" value="{{ $salsa->id }}"> {{ $salsa->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">Aderezos (Opcional)</label>
                    <details class="dropdown-multi" style="position: relative;" wire:ignore.self>
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($aderezos) == 0 ? '-- Seleccionar Aderezos --' : count($aderezos) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['aderezos', 'aderezo']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $aderezo)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="aderezos" value="{{ $aderezo->id }}"> {{ $aderezo->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </details>
                </article>
            </section>
        </fieldset>

        @elseif($servicio_id == 7)
        <fieldset class="form-section animated-section" wire:key="servicio-desayuno-section">
            <legend>Desayuno / Brunch</legend>
            <section class="input-grid grid-2 grid-margin-2">
                <article class="input-wrapper">
                    <label class="tiempo-label">Entrada (Fruta, Jugo, etc.)</label>
                    <select wire:model="entrada_id" class="form-control select-margin">
                        <option value="">-- Selecciona una entrada --</option>
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['entradas', 'entrada']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $entrada)
                            <option value="{{ $entrada->id }}">{{ $entrada->nombre }}</option>
                        @endforeach
                    </select>
                    @error('entrada_id') 
                        <span class="error-msg-3">{{ $message }}</span> 
                    @enderror
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">Plato Fuerte</label>
                    <select wire:model="plato_fuerte_id" class="form-control select-margin">
                        <option value="">-- Selecciona el plato fuerte --</option>
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['platos fuertes', 'plato fuerte']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $fuerte)
                            <option value="{{ $fuerte->id }}">{{ $fuerte->nombre }}</option>
                        @endforeach
                    </select>
                    @error('plato_fuerte_id') 
                        <span class="error-msg-3">{{ $message }}</span> 
                    @enderror
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">Proteína (Opcional)</label>
                    <select wire:model="proteina_id" class="form-control select-margin">
                        <option value="">-- Sin proteína --</option>
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['proteínas', 'proteinas', 'proteína', 'proteina']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $prot)
                            <option value="{{ $prot->id }}">{{ $prot->nombre }}</option>
                        @endforeach
                    </select>
                    @error('proteina_id') 
                        <span class="error-msg-3">{{ $message }}</span> 
                    @enderror
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">Complemento de Desayuno (Opcional)</label>
                    <select wire:model="complemento_desayuno_id" class="form-control select-margin">
                        <option value="">-- Sin complemento --</option>
                        @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['complementos de desayuno', 'complemento de desayuno', 'complementos', 'complemento']))->flatMap->platillos->filter(fn($p) => $p->serviciosGastronomicos->contains('id', $servicio_id) || $p->serviciosGastronomicos->isEmpty()) ?? [] as $comp)
                            <option value="{{ $comp->id }}">{{ $comp->nombre }}</option>
                        @endforeach
                    </select>
                    @error('complemento_desayuno_id') 
                        <span class="error-msg-3">{{ $message }}</span> 
                    @enderror
                </article>
            </section>
        </fieldset>
        @endif

        @if($servicio_id)
        <fieldset class="form-section animated-section" wire:key="servicio-extras-section">
            <legend>Infantil y Extras</legend>
            <section class="input-grid grid-2 grid-margin-2">
                <article class="input-wrapper">
                    <label class="tiempo-label">Opciones Infantiles (Opcional)</label>
                    <details class="dropdown-multi" style="position: relative;" wire:ignore.self>
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($infantil) == 0 ? '-- Seleccionar menú infantil --' : count($infantil) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 300px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @php
                                $allPlatillos = $categorias->flatMap->platillos->unique('id');
                                $menuInfantilOpts = $allPlatillos->filter(function($p) {
                                    $servicios = $p->serviciosGastronomicos->pluck('nombre')->map(fn($n) => strtolower(trim($n)))->toArray();
                                    $catNombre = strtolower(trim($p->categoriaPlatillo->nombre ?? ''));
                                    return in_array('menú infantil', $servicios) || in_array('menu infantil', $servicios) || in_array($catNombre, ['menú infantil', 'menu infantil']);
                                });
                                $buffetInfantilOpts = $allPlatillos->filter(function($p) {
                                    $servicios = $p->serviciosGastronomicos->pluck('nombre')->map(fn($n) => strtolower(trim($n)))->toArray();
                                    $catNombre = strtolower(trim($p->categoriaPlatillo->nombre ?? ''));
                                    return in_array('buffet infantil', $servicios) || $catNombre === 'buffet infantil';
                                });
                            @endphp

                            @if($menuInfantilOpts->count() > 0)
                                <h4 style="margin: 0.5rem 0 0.25rem 0; padding-bottom: 0.25rem; border-bottom: 1px solid #ddd; font-size: 0.9rem; color: var(--primary-purple);">Menú Infantil</h4>
                                @foreach($menuInfantilOpts as $infantil_opt)
                                    <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px dashed #eee;">
                                        <input type="checkbox" wire:model.live="infantil" value="{{ $infantil_opt->id }}"> {{ $infantil_opt->nombre }}
                                    </label>
                                @endforeach
                            @endif

                            @if($buffetInfantilOpts->count() > 0)
                                <h4 style="margin: 1rem 0 0.25rem 0; padding-bottom: 0.25rem; border-bottom: 1px solid #ddd; font-size: 0.9rem; color: var(--primary-purple);">Buffet Infantil</h4>
                                @foreach($buffetInfantilOpts as $buffet_opt)
                                    <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px dashed #eee;">
                                        <input type="checkbox" wire:model.live="infantil" value="{{ $buffet_opt->id }}"> {{ $buffet_opt->nombre }}
                                    </label>
                                @endforeach
                            @endif

                            @if($menuInfantilOpts->isEmpty() && $buffetInfantilOpts->isEmpty())
                                <p style="font-size: 0.85rem; color: #777; margin: 0; padding: 0.5rem 0;">No hay platillos clasificados como infantiles.</p>
                            @endif
                        </div>
                    </details>
                </article>

                <article class="input-wrapper">
                    <label class="tiempo-label">Bebidas (Sabores sin límite)</label>
                    <details class="dropdown-multi" style="position: relative;" wire:ignore.self>
                        <summary class="form-control" style="cursor: pointer; display: flex; justify-content: space-between; align-items: center; background-color: white;">
                            <span>{{ count($bebidas) == 0 ? '-- Seleccionar bebidas --' : count($bebidas) . ' seleccionado(s)' }}</span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <div style="position: absolute; z-index: 10; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #ccc; border-radius: 4px; padding: 0.5rem; background: white; margin-top: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @foreach($categorias->filter(fn($cat) => in_array(strtolower(trim($cat->nombre)), ['bebidas']))->flatMap->platillos ?? [] as $bebida_opt)
                                <label style="display: block; padding: 0.25rem 0; cursor: pointer; border-bottom: 1px solid #eee;">
                                    <input type="checkbox" wire:model.live="bebidas" value="{{ $bebida_opt->id }}"> {{ $bebida_opt->nombre }}
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