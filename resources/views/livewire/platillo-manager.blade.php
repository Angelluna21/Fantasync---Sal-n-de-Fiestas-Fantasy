<article class="manager-section">
    @if(session()->has('message')) 
        <div class="status-bar" style="background:#e83e8c; color:white; padding:10px; margin-bottom:10px;">{{ session('message') }}</div> 
    @endif
    
    <section class="form-grid-2">
        <input type="text" wire:model="nombre" placeholder="Nombre del Platillo" class="form-control">
        <select wire:model="categoria_platillo_id" class="form-control">
            <option value="">Categoría</option>
            @foreach($categorias as $cat) <option value="{{ $cat->id }}">{{ $cat->nombre }}</option> @endforeach
        </select>
        <select wire:model="servicio_gastronomico_id" class="form-control">
            <option value="">Servicio</option>
            @foreach($servicios as $serv) <option value="{{ $serv->id }}">{{ $serv->nombre }}</option> @endforeach
        </select>
    </section>

    <section class="ingredient-adder">
        <select wire:model.live="ingredienteSeleccionadoId" class="form-control">
            <option value="">Buscar ingrediente...</option>
            @foreach($todosLosIngredientes as $ing) <option value="{{ $ing->id }}">{{ $ing->nombre }}</option> @endforeach
        </select>
        <div class="ingredient-adder-qty">
            <input type="number" wire:model="cantidadTemporal" placeholder="0.00" class="form-control">
            <output class="unit-output">{{ $unidadSeleccionada }}</output>
        </div>
        <button wire:click="agregarIngrediente" class="btn-fantasy">Añadir</button>
    </section>

    <table class="recipe-table">
        <thead><tr><th>Ingrediente</th><th>Cant.</th><th>X</th></tr></thead>
        <tbody>
            @foreach($ingredientesAgregados as $id => $info)
            <tr>
                <td>{{ $info['nombre'] }}</td>
                <td><span class="qty-highlight">{{ $info['cantidad'] }}</span> <span class="unit-muted">{{ $info['unidad'] }}</span></td>
                <td><button wire:click="removerIngrediente({{$id}})" class="btn-remove">×</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button wire:click="guardarPlatillo" class="btn-fantasy" style="width:100%; margin-top:20px;">Guardar Platillo y Receta</button>
</article>