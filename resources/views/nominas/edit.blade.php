<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Nómina · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/eventos.css', 'resources/css/auth.css', 'resources/css/nominas.css'])
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <nav class="top-nav" aria-label="Menú superior">
            <a href="{{ route('dashboard') }}" class="logo-link"><img src="{{ asset('img/logo.png') }}" class="nav-logo"></a>
            <x-user-menu />
        </nav>
        <nav class="eventos-back-nav">
            <a href="{{ route('nominas.index') }}" class="btn-back-nav">Volver a Nóminas</a>
        </nav>

        <header class="dashboard-header">
            <h1 class="dashboard-title">Editar Nómina</h1>
        </header>

        <section class="eventos-section">
            <form action="{{ route('nominas.update', $nomina) }}" method="POST" class="nomina-form-container">
                @csrf
                @method('PUT')
                <fieldset class="form-group border-0 p-0 m-0 form-fieldset">
                    <label class="form-label form-label-semibold">Nombre del Empleado</label>
                    <input type="text" name="nombre_empleado" id="nombre_empleado" value="{{ old('nombre_empleado', $nomina->nombre_empleado) }}" class="form-control" required>
                    @error('nombre_empleado') <span class="form-error-msg">{{ $message }}</span> @enderror
                </fieldset>
                <fieldset class="form-group border-0 p-0 m-0 form-fieldset">
                    <label class="form-label form-label-semibold">Puesto</label>
                    <select name="puesto" id="puesto" class="form-control" required>
                        <option value="">Selecciona un puesto...</option>
                        <option value="Dj" {{ old('puesto', $nomina->puesto) == 'Dj' ? 'selected' : '' }}>Dj</option>
                        <option value="Cocinera" {{ old('puesto', $nomina->puesto) == 'Cocinera' ? 'selected' : '' }}>Cocinera</option>
                        <option value="Auxiliar de cocina" {{ old('puesto', $nomina->puesto) == 'Auxiliar de cocina' ? 'selected' : '' }}>Auxiliar de cocina</option>
                        <option value="Pista (meseros)" {{ old('puesto', $nomina->puesto) == 'Pista (meseros)' ? 'selected' : '' }}>Pista (meseros)</option>
                        <option value="Capitan de meseros" {{ old('puesto', $nomina->puesto) == 'Capitan de meseros' ? 'selected' : '' }}>Capitán de meseros</option>
                        <option value="Puerta" {{ old('puesto', $nomina->puesto) == 'Puerta' ? 'selected' : '' }}>Puerta</option>
                        <option value="Barra" {{ old('puesto', $nomina->puesto) == 'Barra' ? 'selected' : '' }}>Barra</option>
                        <option value="Oficina" {{ old('puesto', $nomina->puesto) == 'Oficina' ? 'selected' : '' }}>Oficina</option>
                        <option value="Encargada" {{ old('puesto', $nomina->puesto) == 'Encargada' ? 'selected' : '' }}>Encargada</option>
                        <option value="Nana" {{ old('puesto', $nomina->puesto) == 'Nana' ? 'selected' : '' }}>Nana</option>
                        <option value="Show externo" {{ old('puesto', $nomina->puesto) == 'Show externo' ? 'selected' : '' }}>Show externo</option>
                        <option value="Bienvenida externa" {{ old('puesto', $nomina->puesto) == 'Bienvenida externa' ? 'selected' : '' }}>Bienvenida externa</option>
                    </select>
                    @error('puesto') <span class="form-error-msg">{{ $message }}</span> @enderror
                </fieldset>
                
                <section class="form-flex-row">
                    <fieldset class="form-group border-0 p-0 m-0 form-fieldset-flex">
                        <label class="form-label form-label-semibold">Salario Base ($)</label>
                        <input type="number" step="0.01" name="salario_base" id="salario_base" value="{{ old('salario_base', $nomina->salario_base) }}" class="form-control form-input-readonly" required readonly>
                        @error('salario_base') <span class="form-error-msg">{{ $message }}</span> @enderror
                    </fieldset>
                    
                    <fieldset class="form-group border-0 p-0 m-0 form-fieldset-flex">
                        <label class="form-label form-label-semibold">Pago x Hora Extra ($)</label>
                        <input type="number" step="0.01" id="pago_hora_extra" class="form-control form-input-readonly" readonly>
                    </fieldset>
                </section>

                <section class="form-flex-row">
                    <fieldset class="form-group border-0 p-0 m-0 form-fieldset-flex">
                        <label class="form-label form-label-semibold">Horas Extra (Cantidad)</label>
                        <input type="number" name="horas_extra" id="horas_extra" value="{{ old('horas_extra', $nomina->horas_extra) }}" class="form-control" min="0">
                        @error('horas_extra') <span class="form-error-msg">{{ $message }}</span> @enderror
                    </fieldset>

                    <fieldset class="form-group border-0 p-0 m-0 form-fieldset-flex">
                        <label class="form-label form-label-total">Monto Total ($)</label>
                        <input type="number" step="0.01" name="monto_total" id="monto_total" value="{{ old('monto_total', $nomina->monto_total) }}" class="form-control form-input-total" readonly>
                        @error('monto_total') <span class="form-error-msg">{{ $message }}</span> @enderror
                    </fieldset>
                </section>
                <fieldset class="form-group border-0 p-0 m-0 form-fieldset">
                    <label class="form-label form-label-semibold">Fecha de Trabajo</label>
                    <input type="date" name="fecha_trabajo" value="{{ old('fecha_trabajo', $nomina->fecha_trabajo) }}" class="form-control" required>
                    @error('fecha_trabajo') <span class="form-error-msg">{{ $message }}</span> @enderror
                </fieldset>
                <fieldset class="form-group border-0 p-0 m-0 form-fieldset">
                    <label class="form-label form-label-semibold">Evento</label>
                    <select name="evento_id" id="evento_id" class="form-control" required>
                        <option value="">Selecciona un evento</option>
                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}" {{ $nomina->evento_id == $evento->id ? 'selected' : '' }}>{{ $evento->titulo ?? 'Evento #'.$evento->id }}</option>
                        @endforeach
                    </select>
                    @error('evento_id') <span class="form-error-msg">{{ $message }}</span> @enderror
                </fieldset>
                <fieldset class="form-group border-0 p-0 m-0 form-fieldset-lg">
                    <label class="form-label form-label-semibold">Estado de Pago</label>
                    <select name="estado_pago" class="form-control">
                        <option value="Pendiente" {{ $nomina->estado_pago == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Pagado" {{ $nomina->estado_pago == 'Pagado' ? 'selected' : '' }}>Pagado</option>
                        <option value="Cancelado" {{ $nomina->estado_pago == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    @error('estado_pago') <span class="form-error-msg">{{ $message }}</span> @enderror
                </fieldset>
                <section class="pt-4">
                    <button type="submit" class="button-submit btn-submit-full">Actualizar Nómina</button>
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
                    cerrado_por: "{{ strtolower(trim($evento->cerrado_por ?? '')) }}"
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

        const puestoSelect = document.getElementById('puesto');
        const eventoSelect = document.getElementById('evento_id');
        const salarioBaseInput = document.getElementById('salario_base');
        const pagoHoraExtraInput = document.getElementById('pago_hora_extra');
        const horasExtraInput = document.getElementById('horas_extra');
        const montoTotalInput = document.getElementById('monto_total');
        const empleadoInput = document.getElementById('nombre_empleado');
        const fechaTrabajoInput = document.querySelector('input[name="fecha_trabajo"]');

        // Store original options to filter them
        const originalEventos = Array.from(eventoSelect.options);

        function filtrarEventos() {
            const puestoName = puestoSelect.value;
            const empleadoName = empleadoInput.value.trim().toLowerCase();
            const currentValue = eventoSelect.value;

            // Clear current options
            eventoSelect.innerHTML = '';

            if (puestoName === 'Oficina' || puestoName === 'Encargada') {
                // Filter only matching events
                originalEventos.forEach(opt => {
                    if (!opt.value) { 
                        eventoSelect.appendChild(opt.cloneNode(true));
                        return;
                    }
                    const ev = eventosData[opt.value];
                    if (ev && ev.cerrado_por === empleadoName) {
                        eventoSelect.appendChild(opt.cloneNode(true));
                    }
                });
            } else {
                // Show all events
                originalEventos.forEach(opt => {
                    eventoSelect.appendChild(opt.cloneNode(true));
                });
            }

            // Restore selection if still exists
            const stillExists = Array.from(eventoSelect.options).some(o => o.value === currentValue);
            eventoSelect.value = stillExists ? currentValue : "";

            calcularTotal();
        }

        function calcularTotal() {
            let base = 0;
            let pagoExtra = 0;
            
            const puestoName = puestoSelect.value;
            if(puestoName && puestosConfig[puestoName]) {
                base = puestosConfig[puestoName].base;
                pagoExtra = puestosConfig[puestoName].extra;
                
                // Lógica de comisiones para Oficina y Encargada
                if((puestoName === 'Oficina' || puestoName === 'Encargada') && eventoSelect.value) {
                    const ev = eventosData[eventoSelect.value];
                    if(ev) {
                        const dateObj = new Date(ev.fecha);
                        const isSaturday = dateObj.getUTCDay() === 6;
                        const porcentaje = isSaturday ? 0.01 : 0.05;
                        const comision = ev.total * porcentaje;
                        base += comision;
                    }
                }
            }

            const cantExtra = parseInt(horasExtraInput.value) || 0;
            
            salarioBaseInput.value = base > 0 ? base.toFixed(2) : '';
            pagoHoraExtraInput.value = pagoExtra > 0 ? pagoExtra.toFixed(2) : '';

            const total = base + (pagoExtra * cantExtra);
            montoTotalInput.value = total.toFixed(2);
        }

        // Initialize calculations if data exists on page load
        if(puestoSelect.value) {
            filtrarEventos();
        }

        puestoSelect.addEventListener('change', filtrarEventos);
        empleadoInput.addEventListener('input', filtrarEventos);
        eventoSelect.addEventListener('change', function() {
            calcularTotal();
            if (this.value && eventosData[this.value]) {
                const fecha = eventosData[this.value].fecha;
                fechaTrabajoInput.value = fecha.split(' ')[0]; // Solo tomar yyyy-mm-dd
            }
        });
        horasExtraInput.addEventListener('input', calcularTotal);
    </script>
</body>
</html>
