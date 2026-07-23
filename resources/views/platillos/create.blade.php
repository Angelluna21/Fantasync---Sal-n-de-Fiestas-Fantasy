<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Platillo · FantaSync</title>
    <meta name="description" content="Crear nuevo platillo — FantaSync Sistema de Gestión de Eventos Gastronómicos">
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/platillos.css', 'resources/css/comanda-rapida.css'])
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
                    <h1 class="dashboard-title" style="font-size: 2.5rem; margin-top: 0.2rem;">Nuevo Platillo</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.05rem;">Registra un nuevo elemento para el menú, definiendo su nombre, categoría e insumos requeridos.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <aside style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </aside>
        </nav>

        <section class="platillos-section" aria-label="Formulario de creación" style="margin-top: 7rem;">
            <style>
                .form-input, .form-textarea { max-width: 600px; }
            </style>
            <article class="form-card" style="max-width: 900px; margin: 0 auto;">
                <form action="{{ route('platillos.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                    <aside style="background: #ffebee; color: #d32f2f; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #d32f2f;">
                        <p style="margin-top: 0; font-weight: bold;">Por favor corrige los siguientes errores:</p>
                        <ul style="margin-bottom: 0;">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </aside>
                    @endif

                    <fieldset class="form-group">
                        <label class="form-label">NOMBRE DEL PLATILLO</label>
                        <input type="text" name="nombre" class="form-input" placeholder="Ej. Pechuga Cordon Bleu en salsa chipotle" value="{{ old('nombre') }}" required>
                    </fieldset>

                    <fieldset class="form-group">
                        <label class="form-label">SERVICIO(S) GASTRONÓMICO(S) (Define la sección donde aparecerá)</label>
                        <section style="display: flex; flex-direction: column; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                            @foreach($servicios as $servicio)
                            <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; cursor: pointer;">
                                <input type="checkbox" name="servicio_gastronomico_id[]" value="{{ $servicio->id }}">
                                {{ $servicio->nombre }}
                            </label>
                            @endforeach
                        </section>
                    </fieldset>

                    <fieldset class="form-group">
                        <label class="form-label">CATEGORÍA DE MENÚ</label>
                        <select name="categoria_platillo_id" class="form-input" required>
                            <option value="">Selecciona una categoría...</option>
                            @foreach($categorias->groupBy(fn($cat) => $cat->grupo ?? 'Sin Grupo') as $grupo => $items)
                            <optgroup label="{{ $grupo }}">
                                @foreach($items as $categoria)
                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </fieldset>

                    <fieldset class="form-group">
                        <label class="form-label">DESCRIPCIÓN</label>
                        <textarea name="descripcion" class="form-input form-textarea" placeholder="Ingresa los detalles principales del platillo, guarniciones incluidas, etc.">{{ old('descripcion') }}</textarea>
                    </fieldset>

                    <fieldset class="form-group">
                        <label class="form-label">FÓRMULA / INSUMOS DEL ALMACÉN</label>
                        <section id="contenedor-insumos">
                            <article class="ingrediente-row" style="display: flex; gap: 10px; margin-bottom: 10px;">
                                <select class="form-input ingrediente-select" name="ingredientes[id][]" style="flex: 2;">
                                    <option value="">Selecciona insumo...</option>
                                    @if(isset($insumos) && $insumos->count() > 0)
                                    @foreach($insumos as $insumo)
                                    <option value="{{ $insumo->id }}">{{ $insumo->nombre }} ({{ $insumo->unidad ?? 'pza' }})</option>
                                    @endforeach
                                    @endif
                                </select>
                                <input type="number" step="0.001" min="0" class="form-input" name="ingredientes[cantidad][]" placeholder="Cant. (100 px)" style="flex: 1;" title="Cantidad necesaria para 100 porciones/personas">
                                <button type="button" class="btn-remove-ingrediente" style="background: none; border: none; color: #d32f2f; cursor: pointer; padding: 0 10px; font-size: 1.2rem;">&times;</button>
                            </article>
                        </section>
                    </fieldset>

                    <menu class="form-actions" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; border-top: none; margin-top: 2rem; padding: 0; list-style: none;">
                        <section style="display: flex; gap: 1rem; justify-content: center; width: 100%;">
                            <button type="button" onclick="abrirModalInsumo()" class="btn-back-nav" style="background: rgba(122, 40, 138, 0.1); color: var(--primary-purple); border-color: transparent; margin: 0; min-width: 150px; flex: 1; max-width: 250px; text-align: center; padding: 0.8rem; font-size: 1rem;">+ Crear Insumo</button>
                            <button type="button" id="btn-agregar-fila" class="btn-submit" style="background: var(--accent-yellow); color: var(--primary-purple); margin: 0; min-width: 150px; flex: 1; max-width: 250px; text-align: center; padding: 0.8rem; font-size: 1rem;">+ Añadir fila</button>
                        </section>
                        <section style="display: flex; justify-content: center; width: 100%;">
                            <button type="submit" class="btn-submit btn-large generate" style="font-size: 1rem; padding: 0.8rem; margin: 0; min-width: 250px; text-align: center; display: flex; justify-content: center; align-items: center;">Guardar</button>
                        </section>
                        <section style="display: flex; justify-content: center; width: 100%; margin-top: 0.5rem;">
                            <a href="{{ route('platillos.index') }}" class="btn-back" style="margin: 0; font-size: 1rem;">Cancelar</a>
                        </section>
                    </menu>
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
            <menu style="display: flex; gap: 10px; margin-top: 25px; padding: 0; list-style: none;">
                <button type="button" onclick="simularGuardadoInsumo()" class="btn-submit" style="flex: 1; padding: 10px;">Guardar</button>
                <button type="button" onclick="cerrarModalInsumo()" class="btn-back" style="flex: 1; margin: 0; padding: 10px;">Cancelar</button>
            </menu>
        </article>
    </dialog>

    <script>
        document.getElementById('btn-agregar-fila').addEventListener('click', function() {
            const contenedor = document.getElementById('contenedor-insumos');
            const filaOriginal = contenedor.querySelector('.ingrediente-row');
            const nuevaFila = filaOriginal.cloneNode(true);
            nuevaFila.querySelector('select').value = '';
            nuevaFila.querySelector('input[type="number"]').value = '';
            contenedor.appendChild(nuevaFila);
        });

        document.getElementById('contenedor-insumos').addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-ingrediente')) {
                const filas = document.querySelectorAll('.ingrediente-row');
                if (filas.length > 1) {
                    e.target.closest('.ingrediente-row').remove();
                } else {
                    const fila = e.target.closest('.ingrediente-row');
                    fila.querySelector('select').value = '';
                    fila.querySelector('input[type="number"]').value = '';
                }
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
                    body: JSON.stringify({
                        nombre: nombre,
                        unidad: unidad
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const selects = document.querySelectorAll('.ingrediente-select');
                    selects.forEach(select => {
                        const option = document.createElement('option');
                        option.value = data.id;
                        option.text = `${data.nombre} (${data.unidad})`;
                        select.appendChild(option);
                    });

                    selects[selects.length - 1].value = data.id;
                    cerrarModalInsumo();
                })
                .catch(error => {
                    alert("Hubo un error al guardar el insumo en el servidor.");
                    console.error("Error:", error);
                });
        }
    </script>
</body>

</html>