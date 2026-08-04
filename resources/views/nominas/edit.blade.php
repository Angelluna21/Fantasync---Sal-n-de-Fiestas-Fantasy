<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Recibo de Nómina · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/eventos.css', 'resources/css/auth.css', 'resources/css/nominas.css'])
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <!-- Navegación superior y Encabezado Unificado -->
        <section class="top-nav" style="align-items: flex-start; margin-bottom: 2rem; padding-bottom: 0;">
            <!-- Lado Izquierdo: Logo y Botón Volver -->
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                <a href="{{ route('nominas.index') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a Nóminas
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 2rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Recursos Humanos</p>
                    <h1 class="dashboard-title" style="font-size: 2.8rem; margin-top: 0.2rem;">Editar Recibo</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.1rem;">Modifica el recibo general y sus días trabajados correspondientes.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <section style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </section>
        </section>

        <section class="eventos-section" style="margin-top: 8.5rem;">
            @if ($errors->any())
                <div style="background: rgba(239, 68, 68, 0.1); color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid rgba(239, 68, 68, 0.3);">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('nominas.update', $nomina) }}" method="POST" class="nomina-form-container">
                @csrf
                @method('PUT')
                <fieldset class="form-group border-0 p-0 m-0 form-fieldset">
                    <label class="form-label form-label-semibold">Nombre del Empleado</label>
                    <input type="text" name="nombre_empleado" id="nombre_empleado" value="{{ old('nombre_empleado', $nomina->nombre_empleado) }}" class="form-control" required>
                </fieldset>

                <!-- Días Trabajados -->
                <fieldset class="form-group border-0 p-0 m-0" style="margin-top: 1.5rem !important;">
                    <label class="form-label form-label-semibold" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid rgba(122, 40, 138, 0.1); padding-bottom: 0.5rem;">
                        Días Trabajados
                        <button type="button" class="btn-sm btn-outline" id="add_dia_btn" style="padding: 0.3rem 0.6rem; font-size: 0.85rem; border-radius: 8px; background: rgba(122, 40, 138, 0.05); color: var(--primary-purple); border: 1px solid rgba(122, 40, 138, 0.2); cursor: pointer; font-weight: 600;">+ Agregar Día</button>
                    </label>
                    <div id="dias_container" style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem;">
                        @php $diasOld = old('dias_trabajados', $nomina->detalles->toArray() ?? []); @endphp
                        @foreach($diasOld as $dIndex => $dia)
                            <div class="dia-row" style="background: rgba(255,255,255,0.8); border: 1px solid rgba(122, 40, 138, 0.15); padding: 1rem; border-radius: 12px; display: grid; grid-template-columns: 1fr 1fr 1fr 0.5fr 1fr 1fr auto; gap: 0.75rem; align-items: end;">
                                <div>
                                    <label style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">Evento</label>
                                    <select name="dias_trabajados[{{ $dIndex }}][evento_id]" class="form-control dia-evento" required style="font-size: 0.85rem; padding: 0.5rem;">
                                        <option value="">Seleccionar Evento</option>
                                        @foreach($eventos as $ev)
                                            <option value="{{ $ev->id }}" {{ ($dia['evento_id'] ?? '') == $ev->id ? 'selected' : '' }}>{{ $ev->titulo ?? 'Evento #'.$ev->id }} ({{ explode(' ', $ev->fecha)[0] }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">Fecha</label>
                                    <input type="date" name="dias_trabajados[{{ $dIndex }}][fecha_trabajo]" value="{{ $dia['fecha_trabajo'] ?? '' }}" class="form-control dia-fecha" required style="font-size: 0.85rem; padding: 0.5rem;" readonly>
                                </div>
                                <div>
                                    <label style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">Puesto</label>
                                    <select name="dias_trabajados[{{ $dIndex }}][puesto]" class="form-control dia-puesto" required style="font-size: 0.85rem; padding: 0.5rem;">
                                        <option value="">Seleccionar Puesto</option>
                                        @php $puestos = ['Dj', 'Cocinera', 'Auxiliar de cocina', 'Pista (meseros)', 'Capitan de meseros', 'Puerta', 'Barra', 'Oficina', 'Encargada', 'Nana', 'Show externo', 'Bienvenida externa']; @endphp
                                        @foreach($puestos as $puestoOp)
                                            <option value="{{ $puestoOp }}" {{ ($dia['puesto'] ?? '') == $puestoOp ? 'selected' : '' }}>{{ $puestoOp }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">H. Extra</label>
                                    <input type="number" name="dias_trabajados[{{ $dIndex }}][horas_extra]" value="{{ $dia['horas_extra'] ?? 0 }}" min="0" class="form-control dia-horas" style="font-size: 0.85rem; padding: 0.5rem;">
                                </div>
                                <div>
                                    <label style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">Base ($)</label>
                                    <input type="number" step="0.01" name="dias_trabajados[{{ $dIndex }}][salario_base]" value="{{ $dia['salario_base'] ?? '' }}" class="form-control dia-base form-input-readonly" readonly style="font-size: 0.85rem; padding: 0.5rem;">
                                </div>
                                <div>
                                    <label style="font-size: 0.75rem; color: var(--primary-purple); font-weight: bold; margin-bottom: 0.25rem; display: block;">Subtotal ($)</label>
                                    <input type="number" step="0.01" name="dias_trabajados[{{ $dIndex }}][subtotal]" value="{{ $dia['subtotal'] ?? '' }}" class="form-control dia-subtotal form-input-total" readonly style="font-size: 0.95rem; padding: 0.5rem;">
                                </div>
                                <button type="button" class="btn-remove-dia" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.5rem; margin-bottom: 0.25rem;" title="Eliminar Día">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </fieldset>

                <!-- Pagos Extra -->
                <fieldset class="form-group border-0 p-0 m-0" style="margin-top: 1.5rem !important;">
                    <label class="form-label form-label-semibold" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid rgba(122, 40, 138, 0.1); padding-bottom: 0.5rem;">
                        Pagos Extra
                        <button type="button" class="btn-sm btn-outline" id="add_pago_extra_btn" style="padding: 0.3rem 0.6rem; font-size: 0.85rem; border-radius: 8px; background: rgba(122, 40, 138, 0.05); color: var(--primary-purple); border: 1px solid rgba(122, 40, 138, 0.2); cursor: pointer; font-weight: 600;">+ Agregar Pago Extra</button>
                    </label>
                    <div id="pagos_extra_container" style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.75rem;">
                        @php $pagosExtra = old('pagos_extra', is_array($nomina->pagos_extra) ? $nomina->pagos_extra : []); @endphp
                        @foreach($pagosExtra as $index => $pago)
                            <div class="pago-extra-row" style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="text" name="pagos_extra[{{ $index }}][concepto]" value="{{ $pago['concepto'] ?? '' }}" placeholder="Concepto (ej. Bono, Taxi)" class="form-control" style="flex: 2; padding: 0.5rem;" required>
                                <input type="number" step="0.01" name="pagos_extra[{{ $index }}][monto]" value="{{ $pago['monto'] ?? '' }}" placeholder="Monto ($)" class="form-control pago-extra-monto" style="flex: 1; padding: 0.5rem;" min="0" required>
                                <button type="button" class="btn-remove-pago" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.5rem;" title="Eliminar pago">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </fieldset>

                <!-- Estado y Total -->
                <section class="form-flex-row" style="margin-top: 2rem;">
                    <fieldset class="form-group border-0 p-0 m-0 form-fieldset-flex">
                        <label class="form-label form-label-semibold">Estado de Pago</label>
                        <select name="estado_pago" class="form-control">
                            <option value="Pendiente" {{ $nomina->estado_pago == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Pagado" {{ $nomina->estado_pago == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                            <option value="Cancelado" {{ $nomina->estado_pago == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </fieldset>
                    <fieldset class="form-group border-0 p-0 m-0 form-fieldset-flex">
                        <label class="form-label form-label-total" style="font-size: 1.1rem;">MONTO TOTAL GENERAL ($)</label>
                        <input type="number" step="0.01" name="monto_total" id="monto_total_general" value="{{ old('monto_total', $nomina->monto_total) }}" class="form-control form-input-total" style="font-size: 1.5rem; height: auto; padding: 0.75rem;" readonly>
                    </fieldset>
                </section>
                
                <section class="pt-4">
                    <button type="submit" class="button-submit btn-submit-full">Actualizar Recibo de Nómina</button>
                </section>
            </form>
        </section>
    </main>

    <script>
        const eventosData = {
            @foreach($eventos as $evento)
                "{{ $evento->id }}": {
                    fecha: "{{ $evento->fecha }}",
                    total: {{ $evento->contrato->monto_total ?? 0 }},
                    cerrado_por: "{{ strtolower(trim($evento->cerrado_por ?? '')) }}",
                    titulo: "{{ $evento->titulo ?? 'Evento #'.$evento->id }}"
                },
            @endforeach
        };

        const puestosConfig = {
            'Dj': { base: 850, extra: 100 },
            'Cocinera': { base: 650, extra: 50 },
            'Auxiliar de cocina': { base: 400, extra: 50 },
            'Pista (meseros)': { base: 300, extra: 50 },
            'Capitan de meseros': { base: 400, extra: 100 },
            'Puerta': { base: 250, extra: 50 },
            'Barra': { base: 300, extra: 50 },
            'Oficina': { base: 2000, extra: 300 },
            'Encargada': { base: 2200, extra: 300 },
            'Nana': { base: 300, extra: 50 },
            'Show externo': { base: 350, extra: 0 },
            'Bienvenida externa': { base: 250, extra: 0 }
        };

        let diaIndex = {{ count(old('dias_trabajados', $nomina->detalles->toArray() ?? [])) }};
        let pagoExtraIndex = {{ count(old('pagos_extra', is_array($nomina->pagos_extra) ? $nomina->pagos_extra : [])) }};
        
        const diasContainer = document.getElementById('dias_container');
        const pagosContainer = document.getElementById('pagos_extra_container');
        const totalGeneralInput = document.getElementById('monto_total_general');
        const empleadoInput = document.getElementById('nombre_empleado');

        function generateEventOptions() {
            let options = '<option value="">Seleccionar Evento</option>';
            for (const [id, data] of Object.entries(eventosData)) {
                options += `<option value="${id}">${data.titulo} (${data.fecha.split(' ')[0]})</option>`;
            }
            return options;
        }

        function generatePuestoOptions() {
            let options = '<option value="">Seleccionar Puesto</option>';
            for (const puesto of Object.keys(puestosConfig)) {
                options += `<option value="${puesto}">${puesto}</option>`;
            }
            return options;
        }

        function addDiaRow() {
            const row = document.createElement('div');
            row.className = 'dia-row';
            row.style.cssText = 'background: rgba(255,255,255,0.8); border: 1px solid rgba(122, 40, 138, 0.15); padding: 1rem; border-radius: 12px; display: grid; grid-template-columns: 1fr 1fr 1fr 0.5fr 1fr 1fr auto; gap: 0.75rem; align-items: end;';
            row.innerHTML = `
                <div>
                    <label style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">Evento</label>
                    <select name="dias_trabajados[${diaIndex}][evento_id]" class="form-control dia-evento" required style="font-size: 0.85rem; padding: 0.5rem;">
                        ${generateEventOptions()}
                    </select>
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">Fecha</label>
                    <input type="date" name="dias_trabajados[${diaIndex}][fecha_trabajo]" class="form-control dia-fecha" required style="font-size: 0.85rem; padding: 0.5rem;" readonly>
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">Puesto</label>
                    <select name="dias_trabajados[${diaIndex}][puesto]" class="form-control dia-puesto" required style="font-size: 0.85rem; padding: 0.5rem;">
                        ${generatePuestoOptions()}
                    </select>
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">H. Extra</label>
                    <input type="number" name="dias_trabajados[${diaIndex}][horas_extra]" value="0" min="0" class="form-control dia-horas" style="font-size: 0.85rem; padding: 0.5rem;">
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: #6b7280; margin-bottom: 0.25rem; display: block;">Base ($)</label>
                    <input type="number" step="0.01" name="dias_trabajados[${diaIndex}][salario_base]" class="form-control dia-base form-input-readonly" readonly style="font-size: 0.85rem; padding: 0.5rem;">
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: var(--primary-purple); font-weight: bold; margin-bottom: 0.25rem; display: block;">Subtotal ($)</label>
                    <input type="number" step="0.01" name="dias_trabajados[${diaIndex}][subtotal]" class="form-control dia-subtotal form-input-total" readonly style="font-size: 0.95rem; padding: 0.5rem;">
                </div>
                <button type="button" class="btn-remove-dia" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.5rem; margin-bottom: 0.25rem;" title="Eliminar Día">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            `;
            diasContainer.appendChild(row);
            diaIndex++;
            bindDiaRow(row);
        }

        function addPagoExtraRow() {
            const row = document.createElement('div');
            row.className = 'pago-extra-row';
            row.style.cssText = 'display: flex; gap: 0.5rem; align-items: center;';
            row.innerHTML = `
                <input type="text" name="pagos_extra[${pagoExtraIndex}][concepto]" placeholder="Concepto (ej. Bono, Taxi)" class="form-control" style="flex: 2; padding: 0.5rem;" required>
                <input type="number" step="0.01" name="pagos_extra[${pagoExtraIndex}][monto]" placeholder="Monto ($)" class="form-control pago-extra-monto" style="flex: 1; padding: 0.5rem;" min="0" required>
                <button type="button" class="btn-remove-pago" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.5rem;" title="Eliminar pago">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;
            pagosContainer.appendChild(row);
            pagoExtraIndex++;
        }

        function bindDiaRow(row) {
            const eventoSel = row.querySelector('.dia-evento');
            const fechaIn = row.querySelector('.dia-fecha');
            const puestoSel = row.querySelector('.dia-puesto');
            const horasIn = row.querySelector('.dia-horas');
            const baseIn = row.querySelector('.dia-base');
            const subtotalIn = row.querySelector('.dia-subtotal');
            const removeBtn = row.querySelector('.btn-remove-dia');

            function recalcDia() {
                const puesto = puestoSel.value;
                const evId = eventoSel.value;
                let base = 0;
                let horaExtraVal = 0;

                if (puesto && puestosConfig[puesto]) {
                    base = puestosConfig[puesto].base;
                    horaExtraVal = puestosConfig[puesto].extra;

                    // Comisiones
                    if ((puesto === 'Oficina' || puesto === 'Encargada') && evId) {
                        const evData = eventosData[evId];
                        if (evData) {
                            const dateObj = new Date(evData.fecha);
                            const isSaturday = dateObj.getUTCDay() === 6;
                            const porcentaje = isSaturday ? 0.01 : 0.05;
                            base += evData.total * porcentaje;
                        }
                    }
                }

                const horas = parseInt(horasIn.value) || 0;
                baseIn.value = base > 0 ? base.toFixed(2) : '';
                const subtotal = base + (horaExtraVal * horas);
                subtotalIn.value = subtotal.toFixed(2);
                recalcTotalGeneral();
            }

            eventoSel.addEventListener('change', () => {
                if(eventoSel.value && eventosData[eventoSel.value]) {
                    fechaIn.value = eventosData[eventoSel.value].fecha.split(' ')[0];
                } else {
                    fechaIn.value = '';
                }
                recalcDia();
            });

            puestoSel.addEventListener('change', recalcDia);
            horasIn.addEventListener('input', recalcDia);
            
            removeBtn.addEventListener('click', () => {
                row.remove();
                recalcTotalGeneral();
            });
        }

        function recalcTotalGeneral() {
            let total = 0;
            document.querySelectorAll('.dia-subtotal').forEach(inp => {
                total += parseFloat(inp.value) || 0;
            });
            document.querySelectorAll('.pago-extra-monto').forEach(inp => {
                total += parseFloat(inp.value) || 0;
            });
            totalGeneralInput.value = total.toFixed(2);
        }

        document.getElementById('add_dia_btn').addEventListener('click', addDiaRow);
        document.getElementById('add_pago_extra_btn').addEventListener('click', addPagoExtraRow);

        pagosContainer.addEventListener('click', (e) => {
            const btn = e.target.closest('.btn-remove-pago');
            if (btn) {
                btn.closest('.pago-extra-row').remove();
                recalcTotalGeneral();
            }
        });

        pagosContainer.addEventListener('input', (e) => {
            if (e.target.classList.contains('pago-extra-monto')) {
                recalcTotalGeneral();
            }
        });

        // Vincular los eventos de las filas existentes al cargar
        document.querySelectorAll('.dia-row').forEach(row => {
            bindDiaRow(row);
        });

    </script>
</body>
</html>
