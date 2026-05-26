<section class="card">
    <header class="card-header">
        <span class="card-icon">🍽️</span>
        <h1>Configuración de Comanda (Reactiva)</h1>
    </header>

    <form wire:submit.prevent="guardarMenu" class="info-content">
        
        <fieldset style="border: 1px solid #dee2e6; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
            <legend style="font-weight: bold; padding: 0 10px; color: #495057;">Modalidad del Banquete</legend>
            
            <ul style="list-style: none; padding: 0; display: flex; gap: 2rem;">
                @foreach($servicios as $servicio)
                <li>
                    <label style="cursor: pointer;">
                        <input type="radio" value="{{ $servicio->id }}" wire:model.live="servicio_id">
                        <strong>{{ $servicio->nombre }}</strong>
                    </label>
                </li>
                @endforeach
            </ul>
            @error('servicio_id') 
                <span style="color: #dc3545; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> 
            @enderror
        </fieldset>

        @if($servicio_id == 1)
        <fieldset style="border: none; padding: 0; margin-bottom: 2rem;">
            <h3 style="color: #667eea; margin-bottom: 1rem;">🌮 Selección de Guisados (Elige entre 5 y 7 platillos)</h3>
            
            <article style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                @foreach($categorias->where('nombre', 'GUISADOS')->first()->platillos ?? [] as $guisado)
                <label style="background: #f8f9fa; padding: 10px; border-radius: 6px; border: 1px solid #e9ecef; display: block; cursor: pointer;">
                    <input type="checkbox" value="{{ $guisado->id }}" wire:model="guisados">
                    {{ $guisado->nombre }}
                </label>
                @endforeach
            </article>
            @error('guisados') 
                <span style="color: #dc3545; display: block; margin-top: 1rem; font-size: 0.85rem;">{{ $message }}</span> 
            @enderror
        </fieldset>
        @endif

        @if($servicio_id == 2)
        <fieldset style="border: none; padding: 0; margin-bottom: 2rem;">
            <h3 style="color: #667eea; margin-bottom: 1rem;">🥂 Menú Estructurado por Tiempos</h3>
            
            <article style="display: flex; flex-direction: column; gap: 1.5rem;">
                <label style="display: block;">
                    <strong>1er Tiempo (Entrada o Crema de Apertura):</strong>
                    <select wire:model="entrada_id" style="width: 100%; padding: 8px; border-radius: 4px; margin-top: 0.5rem; border: 1px solid #dee2e6;">
                        <option value="">-- Selecciona una entrada --</option>
                        @foreach($categorias->where('nombre', 'ENTRADAS')->first()->platillos ?? [] as $entrada)
                            <option value="{{ $entrada->id }}">{{ $entrada->nombre }}</option>
                        @endforeach
                    </select>
                    @error('entrada_id') 
                        <span style="color: #dc3545; font-size: 0.85rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> 
                    @enderror
                </label>

                <label style="display: block;">
                    <strong>2do Tiempo (Plato Fuerte de Producción):</strong>
                    <select wire:model="plato_fuerte_id" style="width: 100%; padding: 8px; border-radius: 4px; margin-top: 0.5rem; border: 1px solid #dee2e6;">
                        <option value="">-- Selecciona el plato fuerte --</option>
                        @foreach($categorias->where('nombre', 'PLATOS FUERTES')->first()->platillos ?? [] as $fuerte)
                            <option value="{{ $fuerte->id }}">{{ $fuerte->nombre }}</option>
                        @endforeach
                    </select>
                    @error('plato_fuerte_id') 
                        <span style="color: #dc3545; font-size: 0.85rem; display: block; margin-top: 0.25rem;">{{ $message }}</span> 
                    @enderror
                </label>
            </article>
        </fieldset>
        @endif

        <footer style="margin-top: 2rem; text-align: right;">
            <button type="submit" class="btn primary">💾 Registrar Menú y Calcular Insumos</button>
        </footer>
    </form>
</section>