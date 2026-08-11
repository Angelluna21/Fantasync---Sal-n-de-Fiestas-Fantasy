<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Contratos | FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/contract.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
</head>

<body class="contract-page">
    <figure class="contract-background" aria-hidden="true"></figure>

    <main class="contract-layout">
        <!-- Navegación superior y Encabezado Unificado -->        <nav class="top-nav" style="align-items: flex-start; margin-bottom: 2rem; padding-bottom: 0;">
            <!-- Lado Izquierdo: Logo y Botón Volver -->
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ url('/dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                <a href="{{ route('contratos.index') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 8px; text-decoration: none; transition: all 0.3s;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a Contratos
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 3rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none; text-align: center;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Fantasy</p>
                    <h1 class="dashboard-title" style="font-size: 2.5rem; margin-top: 0.2rem;">Contrato Nuevo</h1>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <aside style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </aside>
        </nav>

        <section class="contract-card">
            <!-- Mensajes de Error -->
            @if($errors->any())
                <aside class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert" style="margin-bottom: 2rem;">
                    <strong class="font-bold">¡Atención!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </aside>
            @endif

            <form action="{{ route('contratos.store') }}" method="POST" class="contract-form">
                @csrf
                @if(isset($draft['contract_id']))
                    <input type="hidden" name="contract_id" value="{{ $draft['contract_id'] }}">
                @endif
                
                <!-- SECCIÓN 1: DATOS DEL CLIENTE -->
                <fieldset class="form-section">
                    <legend>Datos del Cliente</legend>
                    <section class="input-grid grid-2">
                        <article class="input-wrapper">
                            <label for="cliente">Nombre Completo del Contratante *</label>
                            <input type="text" id="cliente" name="cliente" class="form-control" required pattern="[A-Za-záéíóúÁÉÍÓÚñÑ\s]+" title="El nombre solo debe contener letras y espacios, sin números ni símbolos" value="{{ old('cliente', $draft['cliente'] ?? '') }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="correo">Correo Electrónico *</label>
                            <input type="email" id="correo" name="correo" class="form-control" required value="{{ old('correo', $draft['correo'] ?? '') }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="telefono">Celular *</label>
                            <input type="tel" id="telefono" name="telefono" class="form-control" required value="{{ old('telefono', $draft['telefono'] ?? '') }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="tel_casa">Teléfono Casa</label>
                            <input type="tel" id="tel_casa" name="tel_casa" class="form-control" value="{{ old('tel_casa', $draft['tel_casa'] ?? '') }}">
                        </article>
                    </section>
                    <section class="input-grid grid-3" style="margin-top: 1.5rem;">
                        <article class="input-wrapper">
                            <label for="cliente_domicilio">Domicilio</label>
                            <input type="text" id="cliente_domicilio" name="cliente_domicilio" class="form-control" value="{{ old('cliente_domicilio', $draft['cliente_domicilio'] ?? '') }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="cp">C.P.</label>
                            <input type="text" id="cp" name="cp" class="form-control" value="{{ old('cp', $draft['cp'] ?? '') }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="cliente_ine">INE</label>
                            <input type="text" id="cliente_ine" name="cliente_ine" class="form-control" maxlength="18" pattern="[A-Za-z0-9]{18}" title="La clave INE debe tener exactamente 18 caracteres alfanuméricos, sin símbolos (opcional)" value="{{ old('cliente_ine', $draft['cliente_ine'] ?? '') }}">
                        </article>
                    </section>
                </fieldset>

                <!-- SECCIÓN 2: LOGÍSTICA Y DETALLES DEL EVENTO -->
                <fieldset class="form-section">
                    <legend>Detalles del Evento</legend>
                    <section class="input-grid grid-3" style="margin-top: 1.5rem;">
                        <article class="input-wrapper">
                            <label for="salon_id">Salón Asignado *</label>
                            <select id="salon_id" name="salon_id" class="form-control" required>
                                @foreach($salones as $salon)
                                    <option value="{{ $salon->id }}" {{ old('salon_id', $draft['salon_id'] ?? '') == $salon->id ? 'selected' : '' }}>
                                        {{ $salon->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox" id="servicio_externo" name="servicio_externo" value="1" {{ old('servicio_externo', !empty($draft['extras']['servicio_externo'])) ? 'checked' : '' }}>
                                <label for="servicio_externo" style="font-size: 0.85rem; color: var(--text-color); margin: 0; font-weight: normal; cursor: pointer;">Servicio Externo</label>
                            </div>
                        </article>
                        <article class="input-wrapper">
                            <label for="estado">Estado del Contrato *</label>
                            <select id="estado" name="estado" class="form-control" required>
                                <option value="cotizacion" {{ old('estado', $draft['estado'] ?? '') == 'cotizacion' ? 'selected' : '' }}>Cotización</option>
                                <option value="confirmado" {{ old('estado', $draft['estado'] ?? '') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                                <option value="finalizado" {{ old('estado', $draft['estado'] ?? '') == 'finalizado' ? 'selected' : '' }}>Liquidado</option>
                                <option value="cancelado" {{ old('estado', $draft['estado'] ?? '') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </article>
                        <article class="input-wrapper">
                            <label for="tipo_evento">Tipo de Evento *</label>
                            <select id="tipo_evento" name="tipo_evento" class="form-control" required>
                                <option value="Bautizo" {{ old('tipo_evento', $draft['tipo_evento'] ?? '') == 'Bautizo' ? 'selected' : '' }}>Bautizo</option>
                                <option value="Presentación" {{ old('tipo_evento', $draft['tipo_evento'] ?? '') == 'Presentación' ? 'selected' : '' }}>Presentación</option>
                                <option value="Cumpleaños" {{ old('tipo_evento', $draft['tipo_evento'] ?? '') == 'Cumpleaños' ? 'selected' : '' }}>Cumpleaños</option>
                                <option value="Comunión" {{ old('tipo_evento', $draft['tipo_evento'] ?? '') == 'Comunión' ? 'selected' : '' }}>Comunión</option>
                                <option value="Otro" {{ old('tipo_evento', $draft['tipo_evento'] ?? '') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </article>
                        <article class="input-wrapper">
                            <label for="festejado">Nombre del festejado(a) *</label>
                            <input type="text" id="festejado" name="festejado" class="form-control" required value="{{ old('festejado', $draft['festejado'] ?? '') }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="evento_fecha">Fecha del evento *</label>
                            <input type="date" id="evento_fecha" name="evento_fecha" class="form-control" required value="{{ old('evento_fecha', $draft['evento_fecha'] ?? '') }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="paquete_no">Paquete No.</label>
                            <input type="text" id="paquete_no" name="paquete_no" class="form-control" value="{{ old('paquete_no', $draft['paquete_no'] ?? '') }}">
                        </article>
                        <article class="input-wrapper" style="position: relative;">
                            <label>Vendedora(s)</label>
                            <div class="custom-multiselect-dropdown form-control" style="cursor: pointer; position: relative; padding-right: 30px; user-select: none; display: flex; align-items: center;" id="vendedoras_dropdown_btn">
                                <span id="vendedoras_dropdown_text" style="color: var(--text-main);">Seleccionar vendedoras...</span>
                                <span style="position: absolute; right: 10px; color: var(--primary-purple);">▼</span>
                            </div>
                            <div class="custom-multiselect-list" id="vendedoras_dropdown_list" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: var(--card-bg); border: 2px solid var(--border-color); border-radius: 1rem; z-index: 100; max-height: 200px; overflow-y: auto; padding: 0.5rem; box-shadow: var(--shadow-sm); margin-top: 5px;">
                                @foreach($vendedoras as $vendedora)
                                    <label class="checkbox-label custom-multiselect-item" style="display: flex; margin-bottom: 0.25rem; color: var(--text-main); font-size: 0.95rem; padding: 0.5rem; border-radius: 0.5rem; transition: background 0.2s; font-weight: normal;">
                                        <input type="checkbox" name="vendedoras_ids[]" value="{{ $vendedora->id }}" class="vendedora-checkbox" style="width: 18px; height: 18px; accent-color: var(--primary-purple); margin-right: 0.5rem;"
                                            {{ (is_array(old('vendedoras_ids', $draft['vendedoras_ids'] ?? [])) && in_array($vendedora->id, old('vendedoras_ids', $draft['vendedoras_ids'] ?? []))) ? 'checked' : '' }}>
                                        {{ $vendedora->nombre }} {{ $vendedora->apellidos }}
                                    </label>
                                @endforeach
                            </div>
                        </article>
                    </section>
                    <section class="input-grid grid-4" style="margin-top: 1.5rem;">
                        <article class="input-wrapper">
                            <label for="horas_evento">Horas de servicio *</label>
                            <input type="number" id="horas_evento" name="horas_evento" class="form-control" required min="1" step="0.5" value="{{ old('horas_evento', $draft['horas_evento'] ?? 6) }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="horas_adicionales">Horas Adicionales</label>
                            <input type="number" id="horas_adicionales" name="horas_adicionales" class="form-control" min="0" step="0.5" value="{{ old('horas_adicionales', $draft['horas_adicionales'] ?? 0) }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="recepcion_hora">Hora de Recepción</label>
                            <input type="text" id="recepcion_hora" name="recepcion_hora" class="form-control" placeholder="Seleccionar hora..." value="{{ old('recepcion_hora', $draft['recepcion_hora'] ?? '') }}" {{ old('hora_por_definir', $draft['extras']['hora_por_definir'] ?? '') ? 'disabled' : '' }}>
                        </article>
                        <article class="input-wrapper">
                            <label for="inicio_hora">Hora Inicio del Evento</label>
                            <input type="text" id="inicio_hora" name="inicio_hora" class="form-control" placeholder="Seleccionar hora..." value="{{ old('inicio_hora', $draft['inicio_hora'] ?? '') }}" {{ old('hora_por_definir', $draft['extras']['hora_por_definir'] ?? '') ? 'disabled' : '' }}>
                        </article>
                        <article class="input-wrapper">
                            <label for="invitacion_estado">Invitación (Estado)</label>
                            <select id="invitacion_estado" name="invitacion_estado" class="form-control">
                                <option value="">-- Seleccionar --</option>
                                <option value="Entregada" {{ (old('invitacion_estado', $draft['invitacion_estado'] ?? '')) == 'Entregada' ? 'selected' : '' }}>Entregada</option>
                                <option value="Pendiente" {{ (old('invitacion_estado', $draft['invitacion_estado'] ?? '')) == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            </select>
                        </article>
                        <article class="input-wrapper">
                            <label for="invitacion_detalle">Invitación (Detalle/Notas)</label>
                            <input type="text" id="invitacion_detalle" name="invitacion_detalle" class="form-control" placeholder="Ej. Faltan pases..." value="{{ old('invitacion_detalle', $draft['invitacion_detalle'] ?? '') }}">
                        </article>
                    </section>
                    
                    <section class="input-grid grid-4" style="margin-top: 1.5rem;">
                        <article class="input-wrapper">
                            <label for="manteleria_color">Color de Mantelería</label>
                            <input type="text" id="manteleria_color" name="manteleria_color" class="form-control" placeholder="Ej. Blanco" value="{{ old('manteleria_color', $draft['manteleria_color'] ?? '') }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="cubre_mantel_color">Color de Cubre Mantel</label>
                            <input type="text" id="cubre_mantel_color" name="cubre_mantel_color" class="form-control" placeholder="Ej. Dorado" value="{{ old('cubre_mantel_color', $draft['cubre_mantel_color'] ?? '') }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="monos_color">Color de Moños</label>
                            <input type="text" id="monos_color" name="monos_color" class="form-control" placeholder="Ej. Rojo" value="{{ old('monos_color', $draft['monos_color'] ?? '') }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="camino_mesa_color">Color Camino de Mesa</label>
                            <input type="text" id="camino_mesa_color" name="camino_mesa_color" class="form-control" placeholder="Ej. Plateado" value="{{ old('camino_mesa_color', $draft['camino_mesa_color'] ?? '') }}">
                        </article>
                    </section>
                    
                    <section class="input-grid grid-6" style="margin-top: 0.5rem; margin-bottom: 1.5rem;">
                        <article class="input-wrapper checkbox-wrapper"><label class="checkbox-label" style="font-size: 0.9rem; color: #666;"><input type="checkbox" name="hora_por_definir" id="hora_por_definir" value="1" {{ old('hora_por_definir', $draft['extras']['hora_por_definir'] ?? '') ? 'checked' : '' }}> Hora por definir</label></article>
                        <article class="input-wrapper checkbox-wrapper"><label class="checkbox-label" style="font-size: 0.9rem; color: #666;"><input type="checkbox" name="tiene_misa" id="tiene_misa" value="1" {{ old('tiene_misa', $draft['extras']['tiene_misa'] ?? '') ? 'checked' : '' }}> Misa</label></article>
                    </section>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const cbPorDefinir = document.getElementById('hora_por_definir');
                            const inputRecepcion = document.getElementById('recepcion_hora');
                            const inputInicio = document.getElementById('inicio_hora');
                            const cbMisa = document.getElementById('tiene_misa');
                            const inputHorasEvento = document.getElementById('horas_evento');
                            const cbServicioExterno = document.getElementById('servicio_externo');
                            
                            const fpConfig = {
                                enableTime: true,
                                noCalendar: true,
                                dateFormat: "H:i",
                                altInput: true,
                                altFormat: "h:i K",
                                time_24hr: false,
                                minuteIncrement: 1,
                                locale: "es",
                                allowInput: true
                            };
                            const fpRecepcion = typeof flatpickr !== 'undefined' && inputRecepcion ? flatpickr(inputRecepcion, fpConfig) : null;
                            const fpInicio = typeof flatpickr !== 'undefined' && inputInicio ? flatpickr(inputInicio, fpConfig) : null;

                            if(cbPorDefinir) {
                                const toggleHoras = () => {
                                    if(cbPorDefinir.checked) {
                                        if(fpRecepcion) { fpRecepcion.clear(); fpRecepcion._input.disabled = true; }
                                        if(fpInicio) { fpInicio.clear(); fpInicio._input.disabled = true; }
                                        inputRecepcion.disabled = true;
                                        inputInicio.disabled = true;
                                        inputRecepcion.value = '';
                                        inputInicio.value = '';
                                    } else {
                                        if(fpRecepcion) { fpRecepcion._input.disabled = false; }
                                        if(fpInicio) { fpInicio._input.disabled = false; }
                                        inputRecepcion.disabled = false;
                                        inputInicio.disabled = false;
                                    }
                                };
                                cbPorDefinir.addEventListener('change', toggleHoras);
                                if(cbPorDefinir.checked) {
                                    if(fpRecepcion) { fpRecepcion._input.disabled = true; }
                                    if(fpInicio) { fpInicio._input.disabled = true; }
                                    inputRecepcion.disabled = true;
                                    inputInicio.disabled = true;
                                }
                            }
                            
                            function recalcularHoras() {
                                if (cbServicioExterno && cbServicioExterno.checked) {
                                    if (inputHorasEvento) {
                                        inputHorasEvento.value = 0;
                                        inputHorasEvento.closest('.input-wrapper').style.display = 'none';
                                    }
                                    if (inputHorasAdicionales) {
                                        inputHorasAdicionales.value = 0;
                                        inputHorasAdicionales.closest('.input-wrapper').style.display = 'none';
                                    }
                                    return;
                                } else {
                                    if (inputHorasEvento) inputHorasEvento.closest('.input-wrapper').style.display = '';
                                    if (inputHorasAdicionales) inputHorasAdicionales.closest('.input-wrapper').style.display = '';
                                }

                                let base = 6.0;
                                let misaExtra = (cbMisa && cbMisa.checked) ? 0.5 : 0;
                                let adicionales = 0;
                                if (inputHorasAdicionales) {
                                    adicionales = parseFloat(inputHorasAdicionales.value) || 0;
                                    if (adicionales < 0) {
                                        adicionales = 0;
                                        inputHorasAdicionales.value = 0;
                                    }
                                }
                                if (inputHorasEvento) {
                                    inputHorasEvento.value = base + misaExtra + adicionales;
                                }
                            }

                            if (cbMisa) {
                                cbMisa.addEventListener('change', recalcularHoras);
                            }
                            if (cbServicioExterno) {
                                cbServicioExterno.addEventListener('change', recalcularHoras);
                            }

                            const inputHorasAdicionales = document.getElementById('horas_adicionales');
                            if (inputHorasAdicionales) {
                                inputHorasAdicionales.addEventListener('input', recalcularHoras);
                            }
                            
                            // Make sure it doesn't get messed up if the user manually types in horas_evento
                            if (inputHorasEvento) {
                                inputHorasEvento.readOnly = true;
                                inputHorasEvento.style.backgroundColor = '#f5f5f5';
                                inputHorasEvento.style.cursor = 'not-allowed';
                                
                                // Initial calculation on load just to be sure
                                recalcularHoras();
                            }
                            
                            // Custom Multiselect Checkbox Logic
                            const vBtn = document.getElementById('vendedoras_dropdown_btn');
                            const vList = document.getElementById('vendedoras_dropdown_list');
                            const vText = document.getElementById('vendedoras_dropdown_text');
                            const vCheckboxes = document.querySelectorAll('.vendedora-checkbox');

                            if (vBtn && vList) {
                                // Toggle dropdown
                                vBtn.addEventListener('click', function(e) {
                                    e.stopPropagation();
                                    vList.style.display = vList.style.display === 'none' ? 'block' : 'none';
                                });

                                // Update text
                                const updateVText = () => {
                                    const checked = Array.from(vCheckboxes).filter(cb => cb.checked);
                                    if (checked.length === 0) {
                                        vText.textContent = 'Seleccionar vendedoras...';
                                    } else if (checked.length === 1) {
                                        vText.textContent = checked[0].parentElement.textContent.trim();
                                    } else {
                                        vText.textContent = checked.length + ' vendedoras seleccionadas';
                                    }
                                };

                                // Listeners for checkboxes
                                vCheckboxes.forEach(cb => {
                                    cb.addEventListener('change', updateVText);
                                });

                                // Close when clicking outside
                                document.addEventListener('click', function(e) {
                                    if (!vBtn.contains(e.target) && !vList.contains(e.target)) {
                                        vList.style.display = 'none';
                                    }
                                });

                                // Initial text update
                                updateVText();
                                
                                // Hover effects for checkboxes
                                document.querySelectorAll('.custom-multiselect-list label').forEach(lbl => {
                                    lbl.addEventListener('mouseenter', () => lbl.style.background = 'rgba(122, 40, 138, 0.05)');
                                    lbl.addEventListener('mouseleave', () => lbl.style.background = 'transparent');
                                });
                            }
                        });
                    </script>
                    
                    <section class="input-grid grid-4" style="margin-top: 1.5rem; align-items: start;">
                        <article class="input-wrapper">
                            <div class="checkbox-wrapper" style="margin-bottom: 0.5rem;">
                                <label class="checkbox-label"><input type="checkbox" id="cb_pinata" name="tiene_pinata" value="1" {{ old('tiene_pinata', $draft['extras']['tiene_pinata'] ?? '') ? 'checked' : '' }}> Piñata</label>
                            </div>
                            <input type="text" id="detalle_pinata" name="detalle_pinata" class="form-control" placeholder="¿De qué será la piñata?" value="{{ old('detalle_pinata', $draft['extras']['detalle_pinata'] ?? '') }}" style="{{ old('tiene_pinata', $draft['extras']['tiene_pinata'] ?? '') ? '' : 'display:none;' }}">
                        </article>
                        <article class="input-wrapper">
                            <div class="checkbox-wrapper" style="margin-bottom: 0.5rem;">
                                <label class="checkbox-label"><input type="checkbox" id="cb_show" name="tiene_show" value="1" {{ old('tiene_show', $draft['extras']['tiene_show'] ?? '') ? 'checked' : '' }}> Show</label>
                            </div>
                            <input type="text" id="detalle_show" name="detalle_show" class="form-control" placeholder="¿De qué será el show?" value="{{ old('detalle_show', $draft['extras']['detalle_show'] ?? '') }}" style="{{ old('tiene_show', $draft['extras']['tiene_show'] ?? '') ? '' : 'display:none;' }}">
                        </article>
                        <article class="input-wrapper checkbox-wrapper" style="margin-top: 0.5rem;"><label class="checkbox-label"><input type="checkbox" name="arco_globos" value="1" {{ old('arco_globos', $draft['extras']['arco_globos'] ?? '') ? 'checked' : '' }}> Arco globos</label></article>
                        <article class="input-wrapper checkbox-wrapper" style="margin-top: 0.5rem;"><label class="checkbox-label"><input type="checkbox" name="derecho_pista_check" value="1" {{ old('derecho_pista_check', $draft['extras']['derecho_pista_check'] ?? '') ? 'checked' : '' }}> Der. pista</label></article>
                    </section>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const cbPinata = document.getElementById('cb_pinata');
                            const inputPinata = document.getElementById('detalle_pinata');
                            if (cbPinata && inputPinata) {
                                cbPinata.addEventListener('change', function() {
                                    inputPinata.style.display = this.checked ? 'block' : 'none';
                                    if (!this.checked) inputPinata.value = '';
                                });
                            }
                            
                            const cbShow = document.getElementById('cb_show');
                            const inputShow = document.getElementById('detalle_show');
                            if (cbShow && inputShow) {
                                cbShow.addEventListener('change', function() {
                                    inputShow.style.display = this.checked ? 'block' : 'none';
                                    if (!this.checked) inputShow.value = '';
                                });
                            }
                        });
                    </script>
                </fieldset>

                <!-- SECCIÓN 3: ALIMENTOS Y CONFIGURACIÓN -->
                <fieldset class="form-section">
                    <legend>Alimentos y Configuración</legend>
                    <section class="input-grid" style="grid-template-columns: 2.5fr 1fr 1fr 1fr; align-items: flex-end;">
                        <article class="input-wrapper">
                            <label for="servicio_gastronomico">Servicio Gastronómico *</label>
                            <select id="servicio_gastronomico" name="servicio_gastronomico" class="form-control" required>
                                <option value="">-- Selecciona el Servicio --</option>
                                @foreach($serviciosGastronomicos as $sg)
                                    <option value="{{ $sg->id }}" {{ old('servicio_gastronomico', $draft['servicio_gastronomico'] ?? '') == $sg->id ? 'selected' : '' }}>{{ $sg->nombre }}</option>
                                @endforeach
                            </select>
                        </article>
                        <article class="input-wrapper">
                            <label for="num_adultos">Para (Adultos) *</label>
                            <input type="number" id="num_adultos" name="num_adultos" class="form-control" required min="0" value="{{ old('num_adultos', $draft['num_adultos'] ?? 0) }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="num_ninos">Para (Niños) *</label>
                            <input type="number" id="num_ninos" name="num_ninos" class="form-control" required min="0" value="{{ old('num_ninos', $draft['num_ninos'] ?? 0) }}">
                        </article>
                        <article class="input-wrapper">
                            <label for="total_personas">Total</label>
                            <input type="number" id="total_personas" name="total_personas" class="form-control" min="0" value="{{ old('total_personas', $draft['total_personas'] ?? 0) }}">
                        </article>
                    </section>


                </fieldset>

                <!-- SECCIÓN 4: DESGLOSE DE COSTOS -->
                <fieldset class="form-section">
                    <legend>Desglose de Costos ($)</legend>
                    <p class="section-desc">Presiona "Enter" en cualquier costo para sumarlo automáticamente. (No incluye anticipo).</p>
                    <section class="input-grid grid-4">
                        <article class="input-wrapper"><label>Renta de Salón</label><input type="number" step="0.01" min="0" name="c_renta_salon" class="form-control cost-input" value="{{ old('c_renta_salon', $draft['c_renta_salon'] ?? '') }}"></article>
                        <article class="input-wrapper"><label>Otras Bebidas</label><input type="number" step="0.01" min="0" name="c_otras_bebidas" class="form-control cost-input" value="{{ old('c_otras_bebidas', $draft['c_otras_bebidas'] ?? '') }}"></article>
                        <article class="input-wrapper"><label>Piñata</label><input type="number" step="0.01" min="0" name="c_pinata" class="form-control cost-input" value="{{ old('c_pinata', $draft['c_pinata'] ?? '') }}"></article>
                        <article class="input-wrapper"><label>Mesa de Dulces</label><input type="number" step="0.01" min="0" name="c_mesa_dulces" class="form-control cost-input" value="{{ old('c_mesa_dulces', $draft['c_mesa_dulces'] ?? '') }}"></article>

                        <article class="input-wrapper"><label>Show</label><input type="number" step="0.01" min="0" name="c_show" class="form-control cost-input" value="{{ old('c_show', $draft['c_show'] ?? '') }}"></article>
                        <article class="input-wrapper"><label>USB Video c/reseña</label><input type="number" step="0.01" min="0" name="c_usb_video" class="form-control cost-input" value="{{ old('c_usb_video', $draft['c_usb_video'] ?? '') }}"></article>
                        <article class="input-wrapper"><label>Álbum Digital</label><input type="number" step="0.01" min="0" name="c_album_digital" class="form-control cost-input" value="{{ old('c_album_digital', $draft['c_album_digital'] ?? '') }}"></article>
                        <article class="input-wrapper"><label>Paquete Álbum</label><input type="number" step="0.01" min="0" name="c_album_paquete" class="form-control cost-input" value="{{ old('c_album_paquete', $draft['c_album_paquete'] ?? '') }}"></article>

                        <article class="input-wrapper"><label>Derecho de Pista</label><input type="number" step="0.01" min="0" name="c_derecho_pista" class="form-control cost-input" value="{{ old('c_derecho_pista', $draft['c_derecho_pista'] ?? '') }}"></article>
                        <article class="input-wrapper"><label>Hora Extra ($)</label><input type="number" step="0.01" min="0" name="c_hora_extra" class="form-control cost-input" value="{{ old('c_hora_extra', $draft['c_hora_extra'] ?? '') }}"></article>
                        <article class="input-wrapper">
                            <label>¿Quién vendió Hr. Extra?</label>
                            <select name="quien_vendio_hora_extra" class="form-control cost-input">
                                <option value="">Ninguno / Incluido</option>
                                <option value="capitan" {{ old('quien_vendio_hora_extra', $draft['quien_vendio_hora_extra'] ?? '') == 'capitan' ? 'selected' : '' }}>Capitán de Meseros</option>
                                @if(isset($vendedoras))
                                    @foreach($vendedoras as $v)
                                        <option value="vendedora_{{ $v->id }}" {{ old('quien_vendio_hora_extra', $draft['quien_vendio_hora_extra'] ?? '') == 'vendedora_'.$v->id ? 'selected' : '' }}>
                                            Vendedora: {{ $v->nombre }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </article>
                        <article class="input-wrapper"><label>Cámara 360°</label><input type="number" step="0.01" min="0" name="c_camara_360" class="form-control cost-input" value="{{ old('c_camara_360', $draft['c_camara_360'] ?? '') }}"></article>
                        <article class="input-wrapper"><label>Amenización</label><input type="number" step="0.01" min="0" name="c_amenizacion" class="form-control cost-input" value="{{ old('c_amenizacion', $draft['c_amenizacion'] ?? '') }}"></article>

                        <article class="input-wrapper"><label>Personas Adic.</label><input type="number" step="0.01" min="0" name="c_personas_adicionales" class="form-control cost-input" value="{{ old('c_personas_adicionales', $draft['c_personas_adicionales'] ?? '') }}"></article>
                        <article class="input-wrapper"><label>Café</label><input type="number" step="0.01" min="0" name="c_cafe" class="form-control cost-input" value="{{ old('c_cafe', $draft['c_cafe'] ?? '') }}"></article>
                        <article class="input-wrapper"><label>Mickey Móvil</label><input type="number" step="0.01" min="0" name="c_mickey_movil" class="form-control cost-input" value="{{ old('c_mickey_movil', $draft['c_mickey_movil'] ?? '') }}"></article>
                        <article class="input-wrapper"><label>Otros</label><input type="number" step="0.01" min="0" name="c_otros" class="form-control cost-input" value="{{ old('c_otros', $draft['c_otros'] ?? '') }}"></article>
                    </section>
                    <section class="input-grid grid-2" style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                        <article class="input-wrapper">
                            <label for="monto_total" class="highlight-label">MONTO TOTAL ($) *</label>
                            <input type="text" id="monto_total" name="monto_total" class="form-control total-input" style="font-weight: bold; color: #d32f2f;" required readonly>
                        </article>
                        <article class="input-wrapper">
                            <label for="total_letra">Total en Letra *</label>
                            <input type="text" id="total_letra" name="total_letra" class="form-control" placeholder="Ej. Diez mil pesos 00/100 M.N." required readonly tabindex="-1" style="background-color: #f5f5f5; cursor: not-allowed; font-weight: 600;">
                        </article>
                    </section>
                </fieldset>

                <!-- SECCIÓN 5: PAGOS, NOTAS Y CIERRE -->
                <fieldset class="form-section" id="pagos-section">
                    <legend>Pagos, Notas y Cierre</legend>
                    <p class="section-desc">Agrega los pagos realizados para este contrato. El sistema lo marcará como liquidado automáticamente si el total pagado cubre el monto total.</p>
                    
                    <section id="pagos-container">
                        @php
                            $pagos = old('pagos', $draft['pagos'] ?? []);
                            if (empty($pagos)) {
                                // Siempre mostramos al menos un campo vacío por defecto
                                $pagos[] = ['monto' => '', 'recibo' => '', 'fecha' => date('Y-m-d')];
                            }
                        @endphp
                        
                        @foreach($pagos as $index => $pago)
                        <section class="input-grid grid-4 pago-row" style="margin-bottom: 1rem; align-items: end;">
                            <article class="input-wrapper">
                                <label>Monto Abonado ($)</label>
                                <input type="number" step="0.01" min="0" name="pagos[{{ $index }}][monto]" class="form-control pago-monto" value="{{ $pago['monto'] ?? '' }}" required>
                            </article>
                            <article class="input-wrapper">
                                <label>No. de Recibo / Transferencia</label>
                                <input type="text" name="pagos[{{ $index }}][recibo]" class="form-control pago-recibo" value="{{ $pago['recibo'] ?? '' }}">
                            </article>
                            <article class="input-wrapper">
                                <label>Fecha de Pago</label>
                                <input type="date" name="pagos[{{ $index }}][fecha]" class="form-control pago-fecha" value="{{ $pago['fecha'] ?? date('Y-m-d') }}" required>
                            </article>
                            <article class="input-wrapper" style="text-align: center;">
                                @if($index > 0)
                                <button type="button" class="btn-remove-pago" style="background: rgba(220, 38, 38, 0.1); color: #f87171; border: 1px solid rgba(220, 38, 38, 0.2); padding: 0.75rem 1rem; border-radius: 8px; cursor: pointer;">Eliminar</button>
                                @endif
                            </article>
                        </section>
                        @endforeach
                    </section>
                    
                    <button type="button" id="btn-add-pago" style="background: var(--accent-yellow); color: var(--primary-purple); font-weight: 800; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 15px rgba(255, 213, 79, 0.4); margin-top: 1rem;">
                        + Agregar Pago
                    </button>
                    
                    <section style="margin-top: 2rem; display: flex; justify-content: space-between; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);">
                        <article style="text-align: center; flex: 1;">
                            <p style="margin:0; font-size: 0.8rem; color: #a0a0a0; text-transform: uppercase;">Total Pagado</p>
                            <p id="display-total-pagado" style="margin: 0; font-size: 1.5rem; font-weight: bold; color: #10b981;">$0.00</p>
                        </article>
                        <article style="text-align: center; flex: 1;">
                            <p style="margin:0; font-size: 0.8rem; color: #a0a0a0; text-transform: uppercase;">Saldo Pendiente</p>
                            <p id="display-saldo-pendiente" style="margin: 0; font-size: 1.5rem; font-weight: bold; color: #ef4444;">$0.00</p>
                        </article>
                        <article style="text-align: center; flex: 1; display: flex; align-items: center; justify-content: center;">
                            <span id="badge-liquidado" style="display: none; background: #10b981; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: bold; font-size: 1.1rem; box-shadow: 0 0 10px rgba(16,185,129,0.5);">
                                ¡LIQUIDADO!
                            </span>
                        </article>
                    </section>
                </fieldset>

                <!-- SECCIÓN 6: CLÁUSULAS (Imprimible) -->
                <fieldset class="form-section">
                    <legend>Cláusulas Legales del Contrato</legend>
                    <p class="section-desc">Estas cláusulas se imprimirán formalmente con el documento.</p>
                    <article class="clauses-box">
                        <p><strong>PRIMERA. -</strong> "OPERADORA DE FIESTAS FANTASY", renta a "El cliente", su salón ubicado en Calle San Rafael no.254, Col. Vicente Villada, Ciudad Nezahualcóyotl, Estado de México, los servicios detallados y solicitados previamente[cite: 1].</p>
                        <p><strong>SEGUNDA. -</strong> El servicio a que se refiere la cláusula primera que antecede, comprende y será cubierto conforme a lo estipulado en el desglose de este contrato[cite: 1].</p>
                        <p><strong>TERCERA. -</strong> "OPERADORA DE FIESTAS FANTASY", proporcionará los servicios mencionados en la cláusula primera y empezará a trabajar el día y la hora señalado con las instalaciones y equipo completo en buen estado y funcionando[cite: 1].</p>
                        <p><strong>CUARTA. -</strong> Cualquier daño que por accidente o negligencia por parte del "cliente" o sus invitados afecten o sufran cualquier equipo o las instalaciones, serán cubiertas en su totalidad por el "cliente"[cite: 1].</p>
                        <p><strong>QUINTA. -</strong> Todas las personas ingresarán a las instalaciones sin excepción con su respectivo boleto[cite: 1].</p>
                        <p><strong>SEXTA. -</strong> La empresa o cualquier persona que ahí labora, no se hace responsable de los accidentes que sufran o puedan sufrir los menores; quedando entendido que los padres o tutores se harán completamente responsables liberando a "OPERADORA DE FIESTAS FANTASY" y María Guadalupe Betancourt R.[cite: 1].</p>
                        <p><strong>SÉPTIMA. -</strong> No se admite el ingreso a las instalaciones de puestos de fritangas, ni venta de producto por terceros (show, payasos, etc.). En caso de ingreso de equipo de audio por grupos, deberá cubrir el derecho de pista[cite: 1].</p>
                        <p><strong>OCTAVA. -</strong> No se permitirá el acceso a las instalaciones, o estancia en las mismas, a personas en estado de ebriedad[cite: 1].</p>
                        <p><strong>NOVENA. -</strong> Queda prohibido el acceso de personas con armas, o la introducción de bebidas alcohólicas o enervantes. Este hecho será puesto del conocimiento inmediato de las autoridades[cite: 1].</p>
                        <p><strong>DÉCIMA. -</strong> Queda prohibido el uso de confeti, papeles de aluminio, espuma, bazuca metálica, pirotecnia fría o caliente, bombas de humo, por seguridad de los pequeños[cite: 1].</p>
                        <p><strong>DÉCIMA PRIMERA. -</strong> La empresa no se responsabiliza por objetos de valor extraviados no depositados en Gerencia, así como objetos (bebidas, ropa) los cuales deberán recogerse al día siguiente en horario hábil[cite: 1].</p>
                        <p><strong>DÉCIMA SEGUNDA. -</strong> Cuando el cliente haya liquidado trabajos de foto/video, la empresa se hará responsable máximo 30 días. Si solo dio anticipo quedará pendiente de elaborarse[cite: 1].</p>
                        <p><strong>DÉCIMA TERCERA. -</strong> Si después de contratado el servicio "El cliente" no lo utiliza o cancela, pagará a la empresa el 50% del costo total de dicho servicio[cite: 1].</p>
                        <p><strong>DÉCIMA CUARTA. -</strong> La empresa no se hace responsable si el servicio se suspende por causas de fuerza mayor (suspensión de energía, lluvias, manifestaciones, sismos, pandemias, etc.)[cite: 1].</p>
                        <p><strong>DÉCIMA QUINTA. -</strong> Recorrer la fecha solo aplica con un mínimo de 2 meses de anticipación, con una penalización de $5,000 pesos mexicanos y ajuste de precios[cite: 1].</p>
                        <p><strong>DÉCIMA SEXTA. - PAGOS:</strong> Anticipo no reembolsable de $2,500.00 MXN. Obligación de abonos mensuales. El 50% debe cubrirse 30 días antes, y liquidar el 100% 15 días antes del evento. El incumplimiento es causal de rescisión sin devolución[cite: 1].</p>
                        <p><strong>DÉCIMA SÉPTIMA. - IMAGEN:</strong> Autorización expresa para captar foto/video durante el evento para fines publicitarios de la empresa en redes sociales[cite: 1].</p>
                        <p><strong>DÉCIMA OCTAVA. - MICKEY MÓVIL:</strong> Traslado domicilio-iglesia-salón. Queda prohibido rebasar capacidad, sacar extremidades o ingerir alcohol. La puntualidad es responsabilidad del cliente[cite: 1].</p>
                        <p><strong>DÉCIMA NOVENA. - BEBIDAS:</strong> Modalidad de copeo exclusivamente. Entregar inventario al personal de barra antes del inicio[cite: 1].</p>
                        <p><strong>VIGÉSIMA. - JURISDICCIÓN:</strong> Ambas partes se someten a los Tribunales de Ciudad Nezahualcóyotl, Estado de México[cite: 1].</p>
                    </article>
                </fieldset>

                <!-- ÁREA DE FIRMAS (Solo visible al imprimir el documento) -->
                <section class="signatures-print">
                    <section>
                        <hr>
                        <p><strong>"VENDEDOR"</strong></p>
                        <p>OPERADORA DE FIESTAS FANTASY</p>
                        <p>María Guadalupe Betancourt R.</p>
                    </section>
                    <section>
                        <hr>
                        <p><strong>"EL CLIENTE"</strong></p>
                        <p>Firma de conformidad</p>
                    </section>
                </section>

                <!-- BOTONES DE ACCIÓN -->
                <footer class="form-actions" style="gap: 1rem; display: flex; flex-wrap: wrap; justify-content: space-between;">
                    <!-- Botón de Imprimir -->
                    @if(isset($draft['contract_id']))
                        <a href="{{ route('contratos.show', $draft['contract_id']) }}" target="_blank" class="btn-print" style="flex: 1; justify-content: center; text-decoration: none;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Imprimir PDF
                        </a>
                    @else
                        <button type="button" class="btn-print" onclick="alert('Por favor guarda el contrato primero para poder generar e imprimir el PDF.')" style="flex: 1; justify-content: center;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Imprimir PDF
                        </button>
                    @endif

                    <!-- Botón de Guardar y Salir -->
                    <button type="submit" name="action" value="save_only" class="btn-submit" style="flex: 1; justify-content: center; background: var(--accent-yellow); color: var(--primary-purple);">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Solo Guardar
                    </button>

                    <!-- Botón de Guardar y Continuar -->
                    <button type="submit" name="action" value="continue" class="btn-submit" style="flex: 1; justify-content: center; background: var(--primary-purple); color: white;">
                        Guardar y Continuar al Menú
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </footer>
            </form>
        </section>
    </main>

    <!-- SCRIPT SEGURO PARA LA CALCULADORA -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const costInputs = document.querySelectorAll('.cost-input');
            const totalInput = document.getElementById('monto_total');
            const letraInput = document.getElementById('total_letra');

            // Convertir número a letras (Pesos Mexicanos)
            function numeroALetras(num) {
                if (num === 0) return 'CERO PESOS 00/100 M.N.';

                var enteros = Math.floor(num);
                var centavos = (((Math.round(num * 100)) - (Math.floor(num) * 100)));

                function Unidades(num) {
                    switch (num) {
                        case 1:
                            return 'UN';
                        case 2:
                            return 'DOS';
                        case 3:
                            return 'TRES';
                        case 4:
                            return 'CUATRO';
                        case 5:
                            return 'CINCO';
                        case 6:
                            return 'SEIS';
                        case 7:
                            return 'SIETE';
                        case 8:
                            return 'OCHO';
                        case 9:
                            return 'NUEVE';
                    }
                    return '';
                }

                function Decenas(num) {
                    var decena = Math.floor(num / 10);
                    var unidad = num - (decena * 10);
                    switch (decena) {
                        case 1:
                            switch (unidad) {
                                case 0:
                                    return 'DIEZ';
                                case 1:
                                    return 'ONCE';
                                case 2:
                                    return 'DOCE';
                                case 3:
                                    return 'TRECE';
                                case 4:
                                    return 'CATORCE';
                                case 5:
                                    return 'QUINCE';
                                default:
                                    return 'DIECI' + Unidades(unidad);
                            }
                        case 2:
                            switch (unidad) {
                                case 0:
                                    return 'VEINTE';
                                default:
                                    return 'VEINTI' + Unidades(unidad);
                            }
                        case 3:
                            return DecenasY('TREINTA', unidad);
                        case 4:
                            return DecenasY('CUARENTA', unidad);
                        case 5:
                            return DecenasY('CINCUENTA', unidad);
                        case 6:
                            return DecenasY('SESENTA', unidad);
                        case 7:
                            return DecenasY('SETENTA', unidad);
                        case 8:
                            return DecenasY('OCHENTA', unidad);
                        case 9:
                            return DecenasY('NOVENTA', unidad);
                        case 0:
                            return Unidades(unidad);
                    }
                }

                function DecenasY(strSin, numUnidades) {
                    if (numUnidades > 0) return strSin + ' Y ' + Unidades(numUnidades);
                    return strSin;
                }

                function Centenas(num) {
                    var centenas = Math.floor(num / 100);
                    var decenas = num - (centenas * 100);
                    switch (centenas) {
                        case 1:
                            if (decenas > 0) return 'CIENTO ' + Decenas(decenas);
                            return 'CIEN';
                        case 2:
                            return 'DOSCIENTOS ' + Decenas(decenas);
                        case 3:
                            return 'TRESCIENTOS ' + Decenas(decenas);
                        case 4:
                            return 'CUATROCIENTOS ' + Decenas(decenas);
                        case 5:
                            return 'QUINIENTOS ' + Decenas(decenas);
                        case 6:
                            return 'SEISCIENTOS ' + Decenas(decenas);
                        case 7:
                            return 'SETECIENTOS ' + Decenas(decenas);
                        case 8:
                            return 'OCHOCIENTOS ' + Decenas(decenas);
                        case 9:
                            return 'NOVECIENTOS ' + Decenas(decenas);
                    }
                    return Decenas(decenas);
                }

                function Seccion(num, divisor, strSingular, strPlural) {
                    var cientos = Math.floor(num / divisor);
                    var resto = num - (cientos * divisor);
                    var letras = '';
                    if (cientos > 0) {
                        if (cientos > 1) letras = Centenas(cientos) + ' ' + strPlural;
                        else letras = strSingular;
                    }
                    return letras;
                }

                function Miles(num) {
                    var divisor = 1000;
                    var cientos = Math.floor(num / divisor);
                    var resto = num - (cientos * divisor);
                    var strMiles = Seccion(num, divisor, 'UN MIL', 'MIL');
                    var strCentenas = Centenas(resto);
                    if (strMiles == '') return strCentenas;
                    return (strMiles + ' ' + strCentenas).trim();
                }

                function Millones(num) {
                    var divisor = 1000000;
                    var cientos = Math.floor(num / divisor);
                    var resto = num - (cientos * divisor);
                    var strMillones = Seccion(num, divisor, 'UN MILLON', 'MILLONES');
                    var strMiles = Miles(resto);
                    if (strMillones == '') return strMiles;
                    return (strMillones + ' ' + strMiles).trim();
                }

                var strFinal = '';
                if (enteros == 0) strFinal = 'CERO ';
                else strFinal = Millones(enteros) + ' ';

                var centavosStr = centavos.toString().padStart(2, '0');
                return strFinal + 'PESOS ' + centavosStr + '/100 M.N.';
            }

            function calcularTotal() {
                let suma = 0;
                costInputs.forEach(function(input) {
                    if (input.id !== 'anticipo' && input.value !== '') {
                        let valor = parseFloat(input.value);
                        if (!isNaN(valor)) {
                            suma += valor;
                        }
                    }
                });
                totalInput.value = '$ ' + suma.toLocaleString('es-MX', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                letraInput.value = numeroALetras(suma);
            }

            costInputs.forEach(function(input) {
                input.addEventListener('input', calcularTotal);
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        calcularTotal();
                        totalInput.style.transform = 'scale(1.05)';
                        totalInput.style.backgroundColor = '#ffe0e9';
                        setTimeout(function() {
                            totalInput.style.transform = 'scale(1)';
                            totalInput.style.backgroundColor = '#fff5f8';
                        }, 300);
                    }
                });
            });

            // Initialize total on load
            calcularTotal();

            const checkboxesAgua = document.querySelectorAll('.sabor-checkbox');
            checkboxesAgua.forEach(function(box) {
                box.addEventListener('change', function() {
                    const seleccionados = document.querySelectorAll('.sabor-checkbox:checked');
                    if (seleccionados.length > 2) {
                        this.checked = false;
                        alert('Recuerda que solo puedes elegir un máximo de 2 sabores de agua.');
                    }
                });
            });

            /* ==== LÓGICA DE PAGOS DINÁMICOS ==== */
            const pagosContainer = document.getElementById('pagos-container');
            const btnAddPago = document.getElementById('btn-add-pago');
            const totalPagadoDisplay = document.getElementById('display-total-pagado');
            const saldoPendienteDisplay = document.getElementById('display-saldo-pendiente');
            const badgeLiquidado = document.getElementById('badge-liquidado');
            
            let pagoIndex = document.querySelectorAll('.pago-row').length;

            function calcularTotalesPagos() {
                // 1. Obtener el monto total actual de los costos
                let montoTotal = 0;
                costInputs.forEach(function(input) {
                    if (input.value !== '') {
                        let valor = parseFloat(input.value);
                        if (!isNaN(valor)) montoTotal += valor;
                    }
                });

                // 2. Obtener la suma de los pagos
                let totalPagado = 0;
                document.querySelectorAll('.pago-monto').forEach(input => {
                    if (input.value !== '') {
                        let valor = parseFloat(input.value);
                        if (!isNaN(valor)) totalPagado += valor;
                    }
                });

                // 3. Calcular saldo pendiente
                let saldoPendiente = montoTotal - totalPagado;

                // 4. Actualizar interfaz
                totalPagadoDisplay.textContent = '$' + totalPagado.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                if (saldoPendiente < 0) {
                    saldoPendienteDisplay.textContent = '-$' + Math.abs(saldoPendiente).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' (A favor)';
                    saldoPendienteDisplay.style.color = '#3b82f6';
                } else {
                    saldoPendienteDisplay.textContent = '$' + saldoPendiente.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    saldoPendienteDisplay.style.color = '#ef4444';
                }

                // Mostrar badge de liquidado
                if (montoTotal > 0 && totalPagado >= montoTotal) {
                    badgeLiquidado.style.display = 'inline-block';
                } else {
                    badgeLiquidado.style.display = 'none';
                }
            }

            // Escuchar cambios en los inputs de costos para recalcular saldos
            costInputs.forEach(function(input) {
                input.addEventListener('input', calcularTotalesPagos);
            });

            // Escuchar cambios en los montos de pagos existentes
            pagosContainer.addEventListener('input', function(e) {
                if (e.target.classList.contains('pago-monto')) {
                    calcularTotalesPagos();
                }
            });

            // Eliminar fila de pago
            pagosContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-pago')) {
                    e.target.closest('.pago-row').remove();
                    calcularTotalesPagos();
                }
            });

            // Agregar nueva fila de pago
            btnAddPago.addEventListener('click', function() {
                const row = document.createElement('section');
                row.className = 'input-grid grid-4 pago-row';
                row.style.marginBottom = '1rem';
                row.style.alignItems = 'end';
                
                const today = new Date().toISOString().split('T')[0];

                row.innerHTML = `
                    <article class="input-wrapper">
                        <label>Monto Abonado ($)</label>
                        <input type="number" step="0.01" min="0" name="pagos[${pagoIndex}][monto]" class="form-control pago-monto" required>
                    </article>
                    <article class="input-wrapper">
                        <label>No. de Recibo / Transferencia</label>
                        <input type="text" name="pagos[${pagoIndex}][recibo]" class="form-control pago-recibo">
                    </article>
                    <article class="input-wrapper">
                        <label>Fecha de Pago</label>
                        <input type="date" name="pagos[${pagoIndex}][fecha]" class="form-control pago-fecha" value="${today}" required>
                    </article>
                    <article class="input-wrapper" style="text-align: center;">
                        <button type="button" class="btn-remove-pago" style="background: rgba(220, 38, 38, 0.1); color: #f87171; border: 1px solid rgba(220, 38, 38, 0.2); padding: 0.75rem 1rem; border-radius: 8px; cursor: pointer;">Eliminar</button>
                    </article>
                `;
                
                pagosContainer.appendChild(row);
                pagoIndex++;
            });

            // Inicializar totales de pagos al cargar
            calcularTotalesPagos();

            // Prevent double submission
            const contractForm = document.querySelector('.contract-form');
            if (contractForm) {
                contractForm.addEventListener('submit', function(e) {
                    const submitBtns = contractForm.querySelectorAll('button[type="submit"]');
                    setTimeout(() => {
                        submitBtns.forEach(btn => {
                            btn.disabled = true;
                            btn.style.opacity = '0.7';
                            btn.style.cursor = 'not-allowed';
                        });
                    }, 10);
                });
            }

        });
    </script>
    
</body>

</html>