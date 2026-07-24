<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Platillo · FantaSync</title>
<meta name="description" content="Edita la ficha técnica de un platillo — FantaSync">
@vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/platillos.css'])
</head>
<body>
<figure class="dashboard-background" aria-hidden="true"></figure>

<main class="dashboard-layout">
<!-- Navegación superior y Encabezado Unificado -->
<nav class="top-nav" style="align-items: flex-start; margin-bottom: 2rem; padding-bottom: 0;">
<!-- Lado Izquierdo: Logo y Botón Volver -->
<section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
<a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
<img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
</a>
<a href="{{ route('platillos.index') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
Volver al Catálogo
</a>
</section>

<!-- Centro: Encabezado -->
<header class="dashboard-header" style="margin: 3rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
<hgroup>
<p class="eyebrow" style="margin-bottom: 0;">Administración de Menú</p>
<h1 class="dashboard-title" style="font-size: 2.5rem; margin-top: 0.2rem;">Editar Platillo</h1>
<p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.05rem;">Actualiza la ficha técnica del platillo, sus porciones base e insumos requeridos.</p>
</hgroup>
</header>

<!-- Lado Derecho: Menú Usuario -->
<aside style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
<x-user-menu />
</aside>
</nav>

<!-- Formulario -->
<section aria-label="Formulario de edición de platillo" style="margin-top: 7rem;">
<article class="form-card" style="max-width: 900px; margin: 0 auto;">
<form action="{{ route('platillos.update', $platillo->id) }}" method="POST">
@csrf
@method('PUT')

<!-- Campo: Nombre -->
<fieldset class="form-group">
<legend class="form-label">Nombre del Platillo</legend>
<input type="text" id="nombre" name="nombre" class="form-input"
placeholder="Ej. Filete de res en salsa pimienta"
value="{{ old('nombre', $platillo->nombre) }}" required>
@error('nombre')
<output class="form-error">{{ $message }}</output>
@enderror
</fieldset>

<!-- Campo: Precio -->
<fieldset class="form-group">
<legend class="form-label">Precio Unitario ($) *</legend>
<input type="number" step="0.01" min="0" id="precio" name="precio" class="form-input"
placeholder="Ej. 45.00"
value="{{ old('precio', $platillo->precio) }}" required>
@error('precio')
<output class="form-error">{{ $message }}</output>
@enderror
</fieldset>

<!-- Campo: Servicio(s) Gastronómico(s) -->
<fieldset class="form-group">
<legend class="form-label">Servicio(s) Gastronómico(s) *</legend>
<section style="display: flex; flex-direction: column; gap: 8px; padding: 10px; border: 1px solid rgba(122, 40, 138, 0.15); background: rgba(122, 40, 138, 0.03); border-radius: 8px;">
@foreach($servicios as $servicio)
<label style="display: flex; align-items: center; gap: 8px; font-weight: normal; cursor: pointer; color: var(--text-main, #3d1b4a);">
<input type="checkbox" name="servicio_gastronomico_id[]" value="{{ $servicio->id }}"
{{ in_array($servicio->id, old('servicio_gastronomico_id', $platillo->serviciosGastronomicos->pluck('id')->toArray())) ? 'checked' : '' }}>
{{ $servicio->nombre }}
</label>
@endforeach
</section>
@error('servicio_gastronomico_id')
<output class="form-error">{{ $message }}</output>
@enderror
</fieldset>

<!-- Campo: Categoría -->
<fieldset class="form-group">
<legend class="form-label">Categoría de Menú</legend>
<select id="categoria_platillo_id" name="categoria_platillo_id" class="form-input" required>
<option value="" disabled>Selecciona una categoría...</option>
@foreach($categorias->groupBy(fn($cat) => $cat->grupo ?? 'Sin Grupo') as $grupo => $items)
<optgroup label="{{ $grupo }}">
@foreach($items as $categoria)
<option value="{{ $categoria->id }}"
{{ old('categoria_platillo_id', $platillo->categoria_platillo_id) == $categoria->id ? 'selected' : '' }}>
{{ $categoria->nombre }}
</option>
@endforeach
</optgroup>
@endforeach
</select>
@error('categoria_platillo_id')
<output class="form-error">{{ $message }}</output>
@enderror
</fieldset>

<!-- Campo: Descripción -->
<fieldset class="form-group">
<legend class="form-label">Descripción</legend>
<textarea id="descripcion" name="descripcion" class="form-input form-textarea"
placeholder="Describe los ingredientes principales y guarniciones">{{ old('descripcion', $platillo->descripcion) }}</textarea>
@error('descripcion')
<output class="form-error">{{ $message }}</output>
@enderror
</fieldset>


<!-- Campo: Ingredientes (Asociación Múltiple con Cantidad) -->
<fieldset class="form-group">
<legend class="form-label">Fórmula / Insumos del Almacén</legend>
<section id="ingredientes-container" class="ingredientes-container">
<!-- Las filas se agregarán aquí por JS -->
</section>
<section style="display: flex; gap: 1rem; margin-top: 0.5rem;">
<button type="button" id="btn-add-ingrediente" class="btn-add-ingrediente" style="flex: 1; background: var(--accent-yellow); color: var(--primary-purple); border-color: var(--accent-yellow); font-weight: 800;">+ Añadir fila</button>
<button type="button" onclick="abrirModalInsumo()" class="btn-add-ingrediente" style="flex: 1; background: rgba(122, 40, 138, 0.05); color: var(--primary-purple); border-color: rgba(122, 40, 138, 0.2); font-weight: 800;">+ Crear Insumo</button>
</section>
@error('ingredientes')
<output class="form-error">{{ $message }}</output>
@enderror
</fieldset>

<script>
document.addEventListener('DOMContentLoaded', function() {
const container = document.getElementById('ingredientes-container');
const btnAdd = document.getElementById('btn-add-ingrediente');
const ingredientesCat = @json($ingredientes);
const platilloIngredientes = @json($platillo->ingredientes);

function addRow(selectedId = '', cantidad = '', esFijo = 0) {
const row = document.createElement('article');
row.className = 'ingrediente-row';

let selectHtml = `<select name="ingredientes[id][]" class="form-input ingrediente-select" style="flex: 2;" required>
<option value="" disabled ${!selectedId ? 'selected' : ''}>Selecciona insumo...</option>`;
ingredientesCat.forEach(ing => {
const isSelected = ing.id == selectedId ? 'selected' : '';
selectHtml += `<option value="${ing.id}" ${isSelected}>${ing.nombre} (${ing.unidad})</option>`;
});
selectHtml += `</select>`;

row.innerHTML = `
${selectHtml}
    <input type="number" step="0.001" min="0" name="ingredientes[cantidad][]" class="form-input" style="flex: 1;" placeholder="Cantidad" value="${cantidad}" title="Cantidad base (ej. para 100 pax o para todo el evento)" required>
    <select name="ingredientes[es_fijo][]" class="form-input" style="flex: 1;" title="Tipo de cálculo">
        <option value="0" ${esFijo == 0 ? 'selected' : ''}>Proporcional</option>
        <option value="1" ${esFijo == 1 ? 'selected' : ''}>Fijo por evento</option>
    </select>
<button type="button" class="btn-remove btn-remove-ingrediente">X</button>
`;

row.querySelector('.btn-remove').addEventListener('click', () => row.remove());
container.appendChild(row);
}

btnAdd.addEventListener('click', () => addRow());
// Cargar ingredientes existentes
if(platilloIngredientes.length > 0) {
platilloIngredientes.forEach(pi => {
addRow(pi.id, pi.pivot.cantidad_por_base, pi.pivot.es_fijo);
});
} else if(ingredientesCat.length > 0) {
addRow();
} else {
container.innerHTML = '<p class="multiselect-empty">No hay ingredientes registrados. Registra insumos primero.</p>';
btnAdd.style.display = 'none';
}
});

function abrirModalInsumo() {
document.getElementById('modal-insumo').style.display = 'flex';
}

function cerrarModalInsumo() {
document.getElementById('modal-insumo').style.display = 'none';
document.getElementById('modal-nombre').value = '';
}

function simularGuardadoInsumo() {
const nombre = document.getElementById('modal-nombre').value; 
const unidad = document.getElementById('modal-unidad').value;

if (!nombre || nombre.trim() === '') {
alert('Por favor, ingresa el nombre del insumo.');
return;
}

fetch("{{ route('insumos.storeAjax') }}", {
method: 'POST',
headers: {
'Content-Type': 'application/json',
'X-CSRF-TOKEN': '{{ csrf_token() }}'
},
body: JSON.stringify({ nombre: nombre, unidad: unidad })
})
.then(response => response.json())
.then(data => {
// Agregar opción a todos los selects de ingredientes
const selects = document.querySelectorAll('.ingrediente-select');
selects.forEach(select => {
const option = document.createElement('option');
option.value = data.id;
option.text = `${data.nombre} (${data.unidad})`;
select.appendChild(option);
});

// Auto-seleccionar el insumo creado en el último select o agregar fila si no hay
if (selects.length > 0) {
selects[selects.length - 1].value = data.id;
} else {
if (typeof addRow === 'function') addRow(data.id, '', 0);
}
cerrarModalInsumo();
})
.catch(error => {
alert("Hubo un error al guardar el insumo en el servidor.");
console.error("Error:", error);
});
}
</script>

<!-- Acciones del Formulario -->
<footer class="form-actions" style="display: flex; gap: 1rem; width: 100%; justify-content: center;">
<a href="{{ route('platillos.index') }}" class="btn-cancel" style="flex: 1; text-align: center; max-width: 400px;">Cancelar</a>
<button type="submit" class="btn-save" style="flex: 1; text-align: center; max-width: 400px;">Guardar Cambios</button>
</footer>
</form>
</article>
</section>
</main>

<dialog id="modal-insumo" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center; border: none;">
<article style="background: white; padding: 30px; border-radius: 12px; width: 400px; max-width: 90%;">
<h3 style="margin-top: 0; color: var(--primary-purple);">Crear Nuevo Insumo</h3>
<section class="form-group">
<label class="form-label">Nombre del Insumo</label>
<input type="text" id="modal-nombre" class="form-input" placeholder="Ej. Jitomate, Crema, Pollo...">
</section>
<section class="form-group" style="margin-top: 15px;">
<label class="form-label">Unidad de Medida</label>
<select id="modal-unidad" class="form-input">
<option value="kg">Kilogramos (kg)</option>
<option value="gr">Gramos (gr)</option>
<option value="lt">Litros (lt)</option>
<option value="ml">Mililitros (ml)</option>
<option value="pza">Piezas (pza)</option>
</select>
</section>
<menu style="display: flex; flex-direction: row; gap: 10px; margin-top: 25px; padding: 0; list-style: none;">
<button type="button" onclick="cerrarModalInsumo()" class="btn-cancel" style="flex: 1; text-align: center; padding: 10px;">Cancelar</button>
<button type="button" onclick="simularGuardadoInsumo()" class="btn-save" style="flex: 1; text-align: center; padding: 10px;">Guardar</button>
</menu>
</article>
</dialog>

<!-- Footer -->
<footer class="dashboard-footer">
<p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
</footer>
</body>
</html>
