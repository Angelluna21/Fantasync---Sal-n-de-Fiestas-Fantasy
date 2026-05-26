<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa de Contrato - {{ $contrato->id ? 'Contrato #' . $contrato->id : 'Cotización' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 antialiased">
    <!-- Barra de acciones superior (oculta al imprimir) -->
    <nav class="max-w-5xl mx-auto mt-8 px-4 print:hidden" aria-label="Menú de acciones de vista previa">
        <ul class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 flex flex-col sm:flex-row justify-between items-center gap-4 list-none m-0">
            <li>
                <menu class="flex items-center gap-3 list-none p-0 m-0">
                    <li>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition flex items-center gap-2">
                            ← Volver al Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contratos.crear') }}" class="px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl text-sm transition flex items-center gap-2">
                            ✏️ Editar Borrador
                        </a>
                    </li>
                </menu>
            </li>
            <li>
                <menu class="flex items-center gap-3 list-none p-0 m-0">
                    <li>
                        <button onclick="window.print()" class="px-5 py-2.5 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold rounded-xl text-sm shadow-sm transition flex items-center gap-2 cursor-pointer">
                            🖨️ Guardar o Imprimir PDF
                        </button>
                    </li>
                </menu>
            </li>
        </ul>
    </nav>

    <!-- Contenedor principal de la hoja del contrato -->
    <main class="max-w-5xl mx-auto p-12 bg-white shadow-xl my-8 rounded-2xl border border-slate-100 print:my-0 print:shadow-none print:p-0 print:border-none">
        <header class="flex justify-between items-center mb-8 border-b border-slate-100 pb-6">
            <hgroup>
                <h1 class="text-3xl font-bold text-gray-800">Fantasync</h1>
                <p class="text-gray-500 text-sm">Salones de Eventos</p>
            </hgroup>
            <section class="text-right">
                <h2 class="text-2xl font-bold uppercase text-slate-900">{{ $contrato->estado === 'cotizacion' ? 'Cotización' : 'Contrato' }}</h2>
                @if($contrato->id)
                    <p class="text-slate-600 font-medium">Folio: #{{ str_pad($contrato->id, 6, '0', STR_PAD_LEFT) }}</p>
                @endif
                <p class="text-slate-500 text-sm">
                    Fecha: <time datetime="{{ now()->format('Y-m-d') }}">{{ now()->format('d/m/Y') }}</time>
                </p>
            </section>
        </header>

        <section class="mb-8">
            <h3 class="text-xl font-bold border-b-2 border-slate-200 pb-2 mb-4 text-slate-800">Información del Cliente</h3>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6 text-sm text-slate-700 list-none p-0 m-0">
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Cliente:</strong>
                    <span>{{ $cliente }}</span>
                </li>
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Correo:</strong>
                    <span>{{ $correo }}</span>
                </li>
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Teléfono:</strong>
                    <span>{{ $telefono }}</span>
                </li>
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Domicilio:</strong>
                    <span>{{ $clienteDomicilio }}</span>
                </li>
                <li class="flex gap-2 col-span-1 md:col-span-2">
                    <strong class="font-bold text-slate-900">INE:</strong>
                    <span>{{ $clienteIne }}</span>
                </li>
            </ul>
        </section>

        <section class="mb-8">
            <h3 class="text-xl font-bold border-b-2 border-slate-200 pb-2 mb-4 text-slate-800">Detalles del Evento</h3>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6 text-sm text-slate-700 list-none p-0 m-0">
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Tipo de Evento:</strong>
                    <span>{{ $tipoEvento }}</span>
                </li>
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Festejado(a):</strong>
                    <span>{{ $festejado }}</span>
                </li>
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Fecha del Evento:</strong>
                    <span><time datetime="{{ $contrato->evento_fecha?->format('Y-m-d') }}">{{ $eventoFecha }}</time></span>
                </li>
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Recepción:</strong>
                    <span>{{ $recepcionHora }}</span>
                </li>
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Inicio:</strong>
                    <span>{{ $inicioHora }}</span>
                </li>
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Duración:</strong>
                    <span>{{ $horasEvento }} horas</span>
                </li>
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Adultos:</strong>
                    <span>{{ $numAdultos }}</span>
                </li>
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Niños:</strong>
                    <span>{{ $numNinos }}</span>
                </li>
            </ul>
        </section>

        <section class="mb-8">
            <h3 class="text-xl font-bold border-b-2 border-slate-200 pb-2 mb-4 text-slate-800">Salón y Servicios</h3>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6 text-sm text-slate-700 list-none p-0 m-0">
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Salón:</strong>
                    <span>{{ $salonNombre }}</span>
                </li>
                <li class="flex gap-2">
                    <strong class="font-bold text-slate-900">Sucursal:</strong>
                    <span>{{ $salonSucursal }}</span>
                </li>
                <li class="flex gap-2 col-span-1 md:col-span-2">
                    <strong class="font-bold text-slate-900">Mantelería:</strong>
                    <span>{{ $manteleriaColor }}</span>
                </li>
            </ul>
        </section>

        <section class="mb-8">
            <h3 class="text-xl font-bold border-b-2 border-slate-200 pb-2 mb-4 text-slate-800">Servicios Contratados</h3>
            
            <h4 class="font-bold text-lg mb-2 text-slate-800">Menú</h4>
            <table class="w-full mb-4 border-collapse">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="p-2.5 text-left text-sm font-bold text-slate-700">Descripción</th>
                        <th class="p-2.5 text-right text-sm font-bold text-slate-700">Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menuItems as $item)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                            <td class="p-2.5 text-slate-700 text-sm">{{ $item['nombre'] }} <span class="text-xs text-slate-500">({{ $item['detalle'] }})</span></td>
                            <td class="p-2.5 text-right text-slate-700 text-sm font-medium">${{ number_format($item['precio'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-4 text-center text-slate-500 text-sm">No se han seleccionado platillos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if(!empty($extras))
                <h4 class="font-bold text-lg mb-2 mt-6 text-slate-800">Extras</h4>
                <table class="w-full border-collapse">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="p-2.5 text-left text-sm font-bold text-slate-700">Descripción</th>
                            <th class="p-2.5 text-right text-sm font-bold text-slate-700">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($extras as $extra)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                <td class="p-2.5 text-slate-700 text-sm font-medium">{{ $extra['nombre'] }}</td>
                                <td class="p-2.5 text-right text-slate-700 text-sm font-semibold">${{ number_format($extra['precio'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @e        <!-- Resumen Financiero -->
        <section class="flex justify-end mb-8">
            <aside class="w-full md:w-1/2">
                <h3 class="text-xl font-bold border-b-2 border-slate-200 pb-2 mb-4 text-slate-800">Resumen Financiero</h3>
                <ul class="space-y-2 text-sm text-slate-700 list-none p-0 m-0">
                    <li class="flex justify-between">
                        <span>Subtotal Menú:</span>
                        <strong class="font-medium">${{ number_format($subtotalMenu, 2) }}</strong>
                    </li>
                    <li class="flex justify-between">
                        <span>Subtotal Extras:</span>
                        <strong class="font-medium">${{ number_format($subtotalExtras, 2) }}</strong>
                    </li>
                    <li class="flex justify-between font-bold text-lg border-t border-slate-200 pt-2 text-slate-900">
                        <span>Total:</span>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </li>
                </ul>
            </aside>
        </section>

        <!-- Plan de Pagos Sugerido -->
        <section class="mb-8">
            <h3 class="text-xl font-bold border-b-2 border-slate-200 pb-2 mb-4 text-slate-800">Plan de Pagos Sugerido</h3>
            <ul class="list-disc list-inside space-y-1 text-sm text-slate-700">
                @foreach($payments as $payment)
                    <li>{{ $payment['label'] }}: <strong>{{ is_numeric($payment['value']) ? '$' . number_format($payment['value'], 2) : $payment['value'] }}</strong></li>
                @endforeach
            </ul>
        </section>

        <!-- Sección de Cláusulas Legales -->
        <section class="mb-8 page-break-before">
            <h3 class="text-xl font-bold border-b-2 border-slate-200 pb-2 mb-4 text-slate-800">Cláusulas del Contrato</h3>
            <article class="border border-slate-200 rounded-xl p-6 bg-slate-50 text-[11px] text-slate-600 leading-relaxed max-h-96 overflow-y-auto print:max-h-none print:overflow-visible print:bg-white print:p-0 print:border-none">
                <section class="space-y-4">
                    <p><strong>PRIMERA (Servicio de Salón):</strong> “OPERADORA DE FIESTAS FANTASY” renta a “EL CLIENTE” su salón ubicado en Calle San Rafael no.254, Col. Vicente Villada, Ciudad Nezahualcóyotl, Estado de México, los servicios solicitados y detallados en este documento.</p>
                    <p><strong>SEGUNDA (Régimen General del Evento):</strong> Comprende el arrendamiento del salón social por {{ $horasEvento }} horas para un total de {{ $numAdultos + $numNinos }} comensales ({{ $numAdultos }} adultos y {{ $numNinos }} niños). En fecha de celebración {{ $eventoFecha }} en horario de recepción {{ $recepcionHora }} y hora de inicio {{ $inicioHora }}.</p>
                    <p><strong>TERCERA (Calidad del Servicio):</strong> Proporcionará los servicios especificados empezando a trabajar con instalaciones y equipo completo en buen estado y en óptimo funcionamiento.</p>
                    <p><strong>CUARTA (Responsabilidad por Daños):</strong> Cualquier daño material parcial o total que por accidente o negligencia por parte de “EL CLIENTE” o sus invitados sufran los equipos o instalaciones, será cubierto en su totalidad por “EL CLIENTE”.</p>
                    <p><strong>QUINTA (Acceso con Boleto):</strong> Todas las personas ingresarán a las instalaciones sin excepción alguna con su respectivo boleto de control.</p>
                    <p><strong>SEXTA (Cuidado de Menores):</strong> La empresa y su personal no se hacen responsables de los accidentes que sufran o puedan sufrir los menores; los padres o tutores se harán completamente responsables de su cuidado.</p>
                    <p><strong>SÉPTIMA (Restricciones Comerciales y Pista):</strong> No se admite el ingreso de fritangas ni venta de productos por terceros. Los grupos musicales o shows deberán cubrir el derecho de pista correspondiente y resarcir cualquier daño eléctrico.</p>
                    <p><strong>OCTAVA (Normas de Acceso):</strong> No se permitirá el acceso o estancia a personas en malas condiciones de higiene, comportamiento inapropiado o estado de ebriedad.</p>
                    <p><strong>NOVENA (Prohibición de Armas y Sustancias):</strong> Queda estrictamente prohibido el acceso con armas de cualquier tipo, la introducción de bebidas alcohólicas externas sin descorche autorizado, así como de enervantes.</p>
                    <p><strong>DÉCIMA (Restricción de Materiales de Riesgo):</strong> Queda prohibido el uso de confeti, papeles de aluminio, espuma, bazuca metálica, pirotecnia (fría o caliente) o bombas de humo por la seguridad de los niños.</p>
                    <p><strong>DÉCIMA PRIMERA (Objetos Extraviados):</strong> La empresa no se responsabiliza por objetos de valor o personales extraviados que no hayan sido depositados en la Gerencia, debiendo recogerse cualquier material al día siguiente en horario hábil.</p>
                    <p><strong>DÉCIMA SEGUNDA (Servicio de Fotografía y Video):</strong> La empresa responde por trabajos liquidados por un plazo máximo de 30 días posteriores al evento; trabajos con anticipo quedarán en pausa hasta su total liquidación.</p>
                    <p><strong>DÉCIMA TERCERA (Cancelación):</strong> Si después de contratado el servicio “EL CLIENTE” decide rescindir o cancelar el evento, pagará a la empresa una penalización equivalente al 50% del costo total del servicio.</p>
                    <p><strong>DÉCIMA CUARTA (Causas de Fuerza Mayor):</strong> La empresa queda liberada de responsabilidad en caso de suspensión del servicio debido a fallas eléctricas, lluvias torrenciales, sismos, manifestaciones o pandemias.</p>
                    <p><strong>DÉCIMA QUINTA (Reprogramación):</strong> El cambio de fecha sólo se admitirá con un mínimo de 2 meses de anticipación, aplicando una penalización fija de $5,000.00 MXN y el respectivo ajuste de precios en caso de cambio de tarifa anual.</p>
                    <p><strong>DÉCIMA SÉPTIMA (Consentimiento de Imagen):</strong> “EL CLIENTE” autoriza expresamente la captación y uso decoroso y digno de material fotográfico/videográfico del evento para fines estrictamente publicitarios y de difusión en redes de la empresa.</p>
                    <p><strong>DÉCIMA OCTAVA (Mickey Móvil):</strong> En caso de contratarse, el servicio de transporte comprende el domicilio del cliente, templo de la ceremonia y finalmente este salón de eventos. Los pasajeros deberán observar orden, no sobrepasar el límite de personas, ni ingerir bebidas alcohólicas a bordo. La puntualidad del itinerario es exclusiva del cliente.</p>
                    <p><strong>DÉCIMA NOVENA (Servicio de Bebidas y Copeo):</strong> Queda estrictamente prohibido colocar botellas o envases de bebidas alcohólicas directamente sobre las mesas. Todo consumo se realizará sin excepción bajo la modalidad de copeo servido por el personal de barra del salón, debiéndose entregar todo insumo de forma previa.</p>
                    <p><strong>VIGÉSIMA (Jurisdicción):</strong> Ambas partes se someten libre e irrevocablemente a la Jurisdicción y Competencia de los Tribunales correspondientes a Ciudad Nezahualcóyotl, Estado de México, renunciando a cualquier otro fuero.</p>
                </section>
            </article>
        </section>

        <!-- Firmas y Pie de Página -->
        <footer class="mt-16">
            <p class="text-center text-sm text-gray-500">Este documento es una {{ $contrato->estado === 'cotizacion' ? 'cotización' : 'vista previa del contrato' }} y está sujeto a cambios. Válido por 15 días.</p>
            <section class="flex justify-around mt-16 pt-8 border-t border-slate-200">
                <section class="text-center">
                    <p class="border-t-2 border-gray-400 pt-2 mt-8 w-64 text-sm font-bold text-slate-800">Firma del Cliente</p>
                    <p class="text-slate-700 text-sm font-medium">{{ $cliente }}</p>
                </section>
                <section class="text-center">
                    <p class="border-t-2 border-gray-400 pt-2 mt-8 w-64 text-sm font-bold text-slate-800">Firma del Representante</p>
                    <p class="text-slate-700 text-sm font-medium">Fantasync</p>
                </section>
            </section>
        </footer>
    </main>
</body>
</html>