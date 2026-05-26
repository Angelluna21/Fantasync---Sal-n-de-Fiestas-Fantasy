<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear contrato · Fantasync</title>
    @vite(['resources/css/app.css'])
</head>
<body class="contract-page">
    <main class="contract-shell">
        <header class="contract-header">
            <hgroup>
                <p class="eyebrow">Nuevo flujo</p>
                <h1>Crear contrato</h1>
                <p>
                    Captura los datos del evento, selecciona el salón y el menú, y deja listo el borrador para
                    revisarlo en la vista previa.
                </p>
            </hgroup>
            <nav class="header-actions" aria-label="Acciones de cabecera de contrato">
                <a href="{{ route('contrato.demo') }}" class="status-badge neutral" style="text-decoration:none; display:inline-block;">Ver vista previa</a>
            </nav>
        </header>

        @if (session('status'))
            <output class="block rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900 shadow-sm mb-4">
                {{ session('status') }}
            </output>
        @endif

        <form method="POST" action="{{ route('contratos.crear.store') }}" class="grid gap-4">
            @csrf

            <article class="contract-card">
                <header class="section-heading">
                    <hgroup>
                        <p class="section-label">Datos del cliente</p>
                        <h2>Información del evento</h2>
                    </hgroup>
                    <span class="tag">Paso 1</span>
                </header>

                <fieldset class="grid gap-4 md:grid-cols-2 border-0 p-0 m-0">
                    <section>
                        <label for="cliente" class="mb-2 block font-semibold text-slate-700">Cliente</label>
                        <input id="cliente" name="cliente" value="{{ old('cliente', $draft['cliente'] ?? 'Carmelo Pérez') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" placeholder="Carmelo Pérez" />
                        @error('cliente')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="correo" class="mb-2 block font-semibold text-slate-700">Correo</label>
                        <input id="correo" name="correo" type="email" value="{{ old('correo', $draft['correo'] ?? '') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" placeholder="correo@empresa.com" />
                        @error('correo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="telefono" class="mb-2 block font-semibold text-slate-700">Teléfono</label>
                        <input id="telefono" name="telefono" value="{{ old('telefono', $draft['telefono'] ?? '') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" placeholder="55 0000 0000" />
                        @error('telefono')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="tipo_evento" class="mb-2 block font-semibold text-slate-700">Tipo de evento</label>
                        <input id="tipo_evento" name="tipo_evento" value="{{ old('tipo_evento', $draft['tipo_evento'] ?? 'Comunión') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" placeholder="Boda, XV años, cumpleaños" />
                        @error('tipo_evento')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="festejado" class="mb-2 block font-semibold text-slate-700">Nombre del festejado</label>
                        <input id="festejado" name="festejado" value="{{ old('festejado', $draft['festejado'] ?? '') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" placeholder="Nombre del festejado" />
                        @error('festejado')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="estado" class="mb-2 block font-semibold text-slate-700">Estado</label>
                        <select id="estado" name="estado" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none">
                            @php $selectedEstado = old('estado', $draft['estado'] ?? 'cotizacion'); @endphp
                            <option value="cotizacion" {{ $selectedEstado === 'cotizacion' ? 'selected' : '' }}>Cotización</option>
                            <option value="confirmado" {{ $selectedEstado === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                            <option value="finalizado" {{ $selectedEstado === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                            <option value="cancelado" {{ $selectedEstado === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </section>
                    <section>
                        <label for="evento_fecha" class="mb-2 block font-semibold text-slate-700">Fecha del evento</label>
                        <input id="evento_fecha" name="evento_fecha" type="date" value="{{ old('evento_fecha', $draft['evento_fecha'] ?? date('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" />
                        @error('evento_fecha')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="recepcion_hora" class="mb-2 block font-semibold text-slate-700">Hora de recepción</label>
                        <input id="recepcion_hora" name="recepcion_hora" value="{{ old('recepcion_hora', $draft['recepcion_hora'] ?? '15:00') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" placeholder="15:00" />
                        @error('recepcion_hora')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="inicio_hora" class="mb-2 block font-semibold text-slate-700">Hora de inicio</label>
                        <input id="inicio_hora" name="inicio_hora" value="{{ old('inicio_hora', $draft['inicio_hora'] ?? '16:30') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" placeholder="16:30" />
                        @error('inicio_hora')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="horas_evento" class="mb-2 block font-semibold text-slate-700">Duración del evento (horas)</label>
                        <input id="horas_evento" name="horas_evento" type="number" min="1" value="{{ old('horas_evento', $draft['horas_evento'] ?? 5) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" required />
                        @error('horas_evento')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="num_adultos" class="mb-2 block font-semibold text-slate-700">Número de adultos</label>
                        <input id="num_adultos" name="num_adultos" type="number" min="0" value="{{ old('num_adultos', $draft['num_adultos'] ?? 50) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" required />
                        @error('num_adultos')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="num_ninos" class="mb-2 block font-semibold text-slate-700">Número de niños</label>
                        <input id="num_ninos" name="num_ninos" type="number" min="0" value="{{ old('num_ninos', $draft['num_ninos'] ?? 20) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" required />
                        @error('num_ninos')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="manteleria_color" class="mb-2 block font-semibold text-slate-700">Color de mantelería</label>
                        <input id="manteleria_color" name="manteleria_color" value="{{ old('manteleria_color', $draft['manteleria_color'] ?? 'Blanco') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" />
                        @error('manteleria_color')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="cliente_domicilio" class="mb-2 block font-semibold text-slate-700">Domicilio del cliente</label>
                        <input id="cliente_domicilio" name="cliente_domicilio" value="{{ old('cliente_domicilio', $draft['cliente_domicilio'] ?? '') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" placeholder="Av. Principal #123, Col. Centro" />
                        @error('cliente_domicilio')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <label for="cliente_ine" class="mb-2 block font-semibold text-slate-700">Identificación INE</label>
                        <input id="cliente_ine" name="cliente_ine" value="{{ old('cliente_ine', $draft['cliente_ine'] ?? '') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none" placeholder="Clave de elector o ID" />
                        @error('cliente_ine')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                </fieldset>
            </article>

            <article class="contract-card">
                <header class="section-heading">
                    <hgroup>
                        <p class="section-label">Asignación</p>
                        <h2>Salón y servicios</h2>
                    </hgroup>
                    <span class="tag accent">Paso 2</span>
                </header>

                <fieldset class="grid gap-4 md:grid-cols-2 border-0 p-0 m-0">
                    <section>
                        <label for="salon_id" class="mb-2 block font-semibold text-slate-700">Salón</label>
                        <select id="salon_id" name="salon_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 focus:border-orange-400 focus:outline-none">
                            @php $selectedSalon = old('salon_id', $draft['salon_id'] ?? $salones->first()?->id); @endphp
                            @foreach ($salones as $salon)
                                <option value="{{ $salon->id }}" {{ (string) $selectedSalon === (string) $salon->id ? 'selected' : '' }}>
                                    {{ $salon->nombre }} · {{ $salon->sucursal?->nombre ?? 'Sucursal sin registrar' }}
                                </option>
                            @endforeach
                        </select>
                        @error('salon_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </section>
                    <section>
                        <p class="mb-2 font-semibold text-slate-700">Servicios adicionales</p>
                        <ul class="grid gap-3 list-none p-0 m-0">
                            <li>
                                <label class="flex items-start gap-3 rounded-xl border border-slate-200 px-4 py-3 cursor-pointer">
                                    <input type="checkbox" name="extras[pinata]" value="1" {{ old('extras.pinata', $draft['extras']['pinata'] ?? false) ? 'checked' : '' }} class="mt-1">
                                    <span>
                                        <strong>Piñata</strong><br>
                                        <span class="text-sm text-slate-500">Entrega y montaje</span>
                                    </span>
                                </label>
                            </li>
                            <li>
                                <label class="flex items-start gap-3 rounded-xl border border-slate-200 px-4 py-3 cursor-pointer">
                                    <input type="checkbox" name="extras[pista]" value="1" {{ old('extras.pista', $draft['extras']['pista'] ?? false) ? 'checked' : '' }} class="mt-1">
                                    <span>
                                        <strong>Derecho de pista</strong><br>
                                        <span class="text-sm text-slate-500">Show y audio</span>
                                    </span>
                                </label>
                            </li>
                            <li>
                                <label class="flex items-start gap-3 rounded-xl border border-slate-200 px-4 py-3 cursor-pointer">
                                    <input type="checkbox" name="extras[mickey]" value="1" {{ old('extras.mickey', $draft['extras']['mickey'] ?? false) ? 'checked' : '' }} class="mt-1">
                                    <span>
                                        <strong>Mickey móvil</strong><br>
                                        <span class="text-sm text-slate-500">Traslado y logística</span>
                                    </span>
                                </label>
                            </li>
                        </ul>
                    </section>
                </fieldset>
            </article>

            <article class="contract-card">
                <header class="section-heading">
                    <hgroup>
                        <p class="section-label">Menú</p>
                        <h2>Selección del paquete</h2>
                    </hgroup>
                    <span class="tag neutral">Paso 3</span>
                </header>

                <fieldset class="border-0 p-0 m-0">
                    <ul class="grid gap-3 md:grid-cols-2 list-none p-0 m-0">
                        @php $selectedPlatilloIds = collect(old('platillo_ids', $draft['platillo_ids'] ?? []))->map(fn ($value) => (string) $value)->all(); @endphp
                        @foreach ($platillos as $platillo)
                            <li>
                                <label class="flex items-start gap-3 rounded-xl border border-slate-200 px-4 py-3 cursor-pointer">
                                    <input type="checkbox" name="platillo_ids[]" value="{{ $platillo->id }}" {{ in_array((string) $platillo->id, $selectedPlatilloIds, true) ? 'checked' : '' }} class="mt-1">
                                    <span>
                                        <strong>{{ $platillo->nombre }}</strong><br>
                                        <span class="text-sm text-slate-500">{{ $platillo->categoriaPlatillo?->nombre ?? 'Menú principal' }} · ${{ number_format($platillo->precio, 2, '.', ',') }}</span>
                                    </span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </fieldset>
                @error('platillo_ids')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </article>

            <footer class="flex flex-wrap justify-end gap-3 mt-4">
                <a href="{{ route('dashboard') }}" class="rounded-xl border border-slate-200 px-4 py-3 font-semibold text-slate-700" style="text-decoration:none;">Cancelar</a>
                <button type="submit" class="primary-btn" style="width:auto; margin-top:0;">Guardar borrador y ver contrato</button>
            </footer>
        </form>
    </main>
</body>
</html>
