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
        <nav class="top-nav" aria-label="Menú superior">
            <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>
            <x-user-menu />
        </nav>

        <nav aria-label="Navegación de retorno" class="platillos-section">
            <a href="{{ route('platillos.index') }}" class="btn-back-nav">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Catálogo
            </a>
        </nav>

        <header class="dashboard-header">
            <hgroup>
                <p class="eyebrow">Administración de Menú</p>
                <h1 class="dashboard-title">Nuevo Platillo</h1>
                <p class="dashboard-description">Agrega una nueva opción al catálogo asignándole su servicio y categoría.</p>
            </hgroup>
        </header>

        <section class="platillos-section" aria-label="Formulario de creación">

            <div class="form-card medium-container">
                <form action="{{ route('platillos.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div style="background: #ffebee; color: #d32f2f; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #d32f2f;">
                            <p style="margin-top: 0; font-weight: bold;">Por favor corrige los siguientes errores:</p>
                            <ul style="margin-bottom: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label">NOMBRE DEL PLATILLO</label>
                        <input type="text" name="nombre" class="form-input" placeholder="Ej. Pechuga Cordon Bleu en salsa chipotle" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">SERVICIO(S) GASTRONÓMICO(S)</label>
                        <div style="display: flex; flex-direction: column; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                            @foreach($servicios as $servicio)
                                <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; cursor: pointer;">
                                    <input type="checkbox" name="servicio_gastronomico_id[]" value="{{ $servicio->id }}">
                                    {{ $servicio->nombre }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">CATEGORÍA DE MENÚ</label>
                        <select name="categoria_platillo_id" class="form-input" required>
                            <option value="">Selecciona una categoría...</option>
                            <optgroup label="Menús por Tiempos">
                                <option value="1">Sopas, Cremas y Caldos</option>
                                <option value="2">Pastas</option>
                                <option value="3">Ensaladas</option>
                                <option value="4">Plato Fuerte - Pollo</option>
                                <option value="5">Plato Fuerte - Res</option>
                                <option value="6">Plato Fuerte - Cerdo</option>
                            </optgroup>
                            <optgroup label="Opciones de Taquiza">
                                <option value="7">Guisado - Pollo</option>
                                <option value="8">Guisado - Res</option>
                                <option value="9">Guisado - Cerdo</option>
                                <option value="10">Guisado - Vegetariano</option>
                                <option value="11">Guisado - Otros</option>
                            </optgroup>
                            <optgroup label="Opciones de Parrillada">
                                <option value="12">Carnes de Parrillada</option>
                            </optgroup>
                            <optgroup label="Complementos Universales">
                                <option value="13">Guarniciones</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">DESCRIPCIÓN</label>
                        <textarea name="descripcion" class="form-input form-textarea" placeholder="Ingresa los detalles principales del platillo, guarniciones incluidas, etc."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">FÓRMULA / INSUMOS DEL ALMACÉN</label>
                        <div id="contenedor-insumos">
                            <div class="ingrediente-row" style="display: flex; gap: 10px; margin-bottom: 10px;">
                                <!-- Quitamos 'required' aquí para que el formulario se envíe aunque el select esté en blanco -->
                                <select class="form-input ingrediente-select" name="ingredientes[id][]" style="flex: 2;">
                                    <option value="">Selecciona insumo...</option>
                                    @if(isset($insumos) && $insumos->count() > 0)
                                        @foreach($insumos as $insumo)
                                            <option value="{{ $insumo->id }}">{{ $insumo->nombre }} ({{ $insumo->unidad ?? 'pza' }})</option>
                                        @endforeach
                                    @endif
                                </select>
                                <input type="text" class="form-input ingrediente-input" name="ingredientes[cantidad][]" placeholder="Cantidad" style="flex: 1;">
                                <button type="button" class="btn-remove-ingrediente" style="background: #ffebee; color: #d32f2f; border: none; border-radius: 8px; padding: 0 15px; font-weight: bold; cursor: pointer;">X</button>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px; margin-top: 10px;">
                            <button type="button" onclick="abrirModalInsumo()" style="border: 1px solid var(--primary-purple); border-radius: 8px; background: var(--primary-purple, #7A288A); color: white; padding: 10px; width: 50%; cursor: pointer;">+ Crear Nuevo Insumo</button>
                            <button type="button" id="btn-agregar-fila" style="border: 1px dashed rgba(122, 40, 138, 0.4); border-radius: 8px; background: transparent; color: var(--primary-purple, #7A288A); padding: 10px; width: 50%; cursor: pointer;">+ Añadir fila</button>
                        </div>
                    </div>

                    <div class="form-actions" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; border-top: none; margin-top: 2rem;">
                        <a href="{{ route('platillos.index') }}" class="btn-cancel" style="width: 200px; text-align: center;">Cancelar</a>
                        <button type="submit" class="btn-save" style="width: 200px;">Crear Platillo</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <!-- Modal para crear insumo -->
    <div id="modal-insumo" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 30px; border-radius: 12px; width: 400px; max-width: 90%;">
            <h3 style="margin-bottom: 20px; color: #7A288A; font-family: sans-serif; font-weight: bold;">Crear Insumo en Almacén</h3>
            <div class="form-group">
                <label class="form-label" style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 5px; display: block;">NOMBRE DEL INSUMO</label>
                <input type="text" id="modal-nombre" class="form-input" placeholder="Ej. Pechuga de pollo" style="width: 100%; box-sizing: border-box;">
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <label class="form-label" style="font-size: 12px; font-weight: bold; color: #666; margin-bottom: 5px; display: block;">UNIDAD DE MEDIDA</label>
                <select id="modal-unidad" class="form-input" style="width: 100%; box-sizing: border-box;">
                    <option value="kg">Kilogramos (kg)</option>
                    <option value="g">Gramos (g)</option>
                    <option value="lt">Litros (lt)</option>
                    <option value="pza">Pieza (pza)</option>
                    <option value="manojo">Manojo</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 25px;">
                <button type="button" onclick="cerrarModalInsumo()" style="border: 1px solid #ccc; background: white; padding: 10px; border-radius: 8px; flex: 1; cursor: pointer; font-weight: bold; color: #666;">Cancelar</button>
                <button type="button" onclick="simularGuardadoInsumo()" style="border: none; background: #7A288A; color: white; padding: 10px; border-radius: 8px; flex: 1; cursor: pointer; font-weight: bold;">Guardar Insumo</button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('btn-agregar-fila').addEventListener('click', function() {
            const contenedor = document.getElementById('contenedor-insumos');
            const filaOriginal = contenedor.querySelector('.ingrediente-row');
            const nuevaFila = filaOriginal.cloneNode(true);
            nuevaFila.querySelector('select').value = '';
            nuevaFila.querySelector('input[type="text"]').value = '';
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
                    fila.querySelector('input[type="text"]').value = '';
                }
            }
        });

        function abrirModalInsumo() { document.getElementById('modal-insumo').style.display = 'flex'; }
        function cerrarModalInsumo() { document.getElementById('modal-insumo').style.display = 'none'; document.getElementById('modal-nombre').value = ''; }
        
        function simularGuardadoInsumo() {
            const nombre = document.getElementById('modal-nombre').value;
            const unidad = document.getElementById('modal-unidad').value;
            
            if(nombre.trim() === '') { 
                alert('Por favor, ingresa el nombre del insumo.'); 
                return; 
            }

            // Petición AJAX para guardar en la base de datos real
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
                // Añadimos el insumo recién creado a todas las listas
                const selects = document.querySelectorAll('.ingrediente-select');
                selects.forEach(select => {
                    const option = document.createElement('option');
                    option.value = data.id; // Aquí se asigna el ID real generado en la base de datos
                    option.text = `${data.nombre} (${data.unidad})`;
                    select.appendChild(option);
                });
                
                // Seleccionamos automáticamente el nuevo insumo en la última fila
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