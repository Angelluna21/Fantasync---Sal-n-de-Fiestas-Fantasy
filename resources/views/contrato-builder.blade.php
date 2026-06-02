<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Contratos | FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/contract.css'])
    
    <!-- ESTILO INCRUSTADO: FLECHAS Y FORMATO DE IMPRESIÓN -->
    <style>
        /* Borra las flechas en Chrome, Safari, Edge, Opera */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        /* Borra las flechas en Firefox */
        input[type="number"] {
            -moz-appearance: textfield;
        }

        /* Estilos de la caja de cláusulas en pantalla */
        .clauses-box {
            background: #f9f9f9;
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            border-radius: 0.5rem;
            height: 300px;
            overflow-y: scroll;
            font-size: 0.85rem;
            color: #444;
            line-height: 1.6;
        }
        .clauses-box p { margin-bottom: 0.8rem; text-align: justify; }

        /* Botón de impresión */
        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: #e0e0e0;
            color: #333;
            font-family: 'Nunito', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 3rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-print:hover { background: #d0d0d0; transform: translateY(-3px); }

        /* =========================================
           MAGIA DE IMPRESIÓN (Transforma la web a papel) 
           ========================================= */
        @media print {
            body { background: white !important; color: black !important; font-size: 12px; }
            .contract-background, .top-nav, .form-actions, .contract-header p, .section-desc { display: none !important; }
            .contract-layout { padding: 0; max-width: 100%; margin: 0; }
            .contract-card { box-shadow: none; padding: 0; border: none; }
            .contract-title { font-size: 18px; text-align: center; margin-bottom: 20px; color: black; }
            
            /* Quita bordes de los inputs para que parezca texto normal */
            .form-control { border: none; border-bottom: 1px solid black; border-radius: 0; padding: 2px 5px; height: auto; background: transparent; }
            select { -webkit-appearance: none; appearance: none; }
            
            .form-section { border: none; padding: 0; margin-bottom: 15px; page-break-inside: avoid; }
            .form-section legend { background: transparent; color: black; padding: 0; font-size: 14px; box-shadow: none; border-bottom: 2px solid black; border-radius: 0; width: 100%; font-weight: bold; margin-bottom: 10px; }
            
            /* Las cláusulas deben salir completas sin scroll */
            .clauses-box { height: auto !important; overflow: visible !important; border: none; padding: 0; font-size: 10px; line-height: 1.2; }
            
            /* Firmas al final de la página impresa */
            .signatures-print {
                display: flex !important;
                justify-content: space-around;
                margin-top: 50px;
                page-break-inside: avoid;
            }
            .signatures-print section { text-align: center; width: 40%; }
            .signatures-print hr { border: 1px solid black; margin-bottom: 5px; }
        }

        /* Ocultar sección de firmas en pantalla (solo se ve al imprimir) */
        @media screen { .signatures-print { display: none; } }
    </style>
</head>
<body class="contract-page">
    <figure class="contract-background" aria-hidden="true"></figure>

    <main class="contract-layout">
        <nav class="top-nav" aria-label="Navegación del sistema">
            <a href="{{ url('/dashboard') }}" aria-label="Volver al inicio" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>
            <a href="{{ url('/dashboard') }}" class="btn-back">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
        </nav>

        <header class="contract-header">
            <hgroup>
                <p class="eyebrow">FantaSync</p>
                <h1 class="contract-title">Contrato Nuevo</h1>
            </hgroup>
        </header>

        <section class="contract-card">
            @if ($errors->any())
                <aside class="form-error--global" style="margin-bottom: 2rem; color: #d32f2f; background: #ffebee; padding: 1rem; border-radius: 1rem;">
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul style="margin-top: 0.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </aside>
            @endif

            <form action="{{ route('contratos.store') }}" method="POST" class="contract-form">
                @csrf

                <!-- SECCIÓN 1: DATOS DEL CLIENTE -->
                <fieldset class="form-section">
                    <legend>Datos del Cliente</legend>
                    <section class="input-grid grid-3">
                        <article class="input-wrapper">
                            <label for="cliente">Nombre del cliente *</label>
                            <input type="text" id="cliente" name="cliente" class="form-control" required minlength="5" placeholder="Nombre completo">
                        </article>
                        <article class="input-wrapper">
                            <label for="cliente_ine">Clave INE *</label>
                            <input type="text" id="cliente_ine" name="cliente_ine" class="form-control" required 
                                minlength="18" maxlength="18" pattern="[A-Za-z0-9]{18}" 
                                placeholder="18 caracteres" onkeyup="this.value = this.value.toUpperCase()">
                        </article>
                        <article class="input-wrapper">
                            <label for="correo">Correo electrónico *</label>
                            <input type="email" id="correo" name="correo" class="form-control" required placeholder="correo@ejemplo.com">
                        </article>
                    </section>
                    <section class="input-grid grid-2" style="margin-top: 1.5rem;">
                        <article class="input-wrapper">
                            <label for="cliente_domicilio">Domicilio completo *</label>
                            <input type="text" id="cliente_domicilio" name="cliente_domicilio" class="form-control" required placeholder="Calle, Número, Colonia">
                        </article>
                        <article class="input-wrapper">
                            <label for="cp">C.P. *</label>
                            <input type="text" id="cp" name="cp" class="form-control" required maxlength="5" pattern="[0-9]{5}" placeholder="Ej. 57000">
                        </article>
                    </section>
                    <section class="input-grid grid-2" style="margin-top: 1.5rem;">
                        <article class="input-wrapper">
                            <label for="tel_casa">Tel. de casa *</label>
                            <input type="tel" id="tel_casa" name="tel_casa" class="form-control" required maxlength="10" minlength="10" pattern="[0-9]{10}" placeholder="10 dígitos">
                        </article>
                        <article class="input-wrapper">
                            <label for="telefono">Celular *</label>
                            <input type="tel" id="telefono" name="telefono" class="form-control" required maxlength="10" minlength="10" pattern="[0-9]{10}" placeholder="10 dígitos">
                        </article>
                    </section>
                </fieldset>

                <!-- SECCIÓN 2: DETALLES DEL EVENTO -->
                <fieldset class="form-section">
                    <legend>Detalles del Evento</legend>
                    <section class="input-grid grid-2">
                        <article class="input-wrapper">
                            <label for="salon_id">Salón Asignado *</label>
                            <select id="salon_id" name="salon_id" class="form-control" required>
                                <option value="" disabled selected>Seleccione un salón...</option>
                                <option value="1">Salón Jardín</option>
                                <option value="2">Salón Infantil</option>
                            </select>
                        </article>
                        <article class="input-wrapper">
                            <label for="estado">Estado del Contrato *</label>
                            <select id="estado" name="estado" class="form-control" required>
                                <option value="cotizacion">Cotización</option>
                                <option value="confirmado">Confirmado</option>
                                <option value="cancelado">Cancelado</option>
                                <option value="liquidado">Liquidado</option>
                            </select>
                        </article>
                    </section>
                    <section class="input-grid grid-4" style="margin-top: 1.5rem;">
                        <article class="input-wrapper">
                            <label for="tipo_evento">Tipo de Evento *</label>
                            <select id="tipo_evento" name="tipo_evento" class="form-control" required>
                                <option value="Bautizo">Bautizo</option>
                                <option value="Presentación">Presentación</option>
                                <option value="Cumpleaños">Cumpleaños</option>
                                <option value="Comunión">Comunión</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </article>
                        <article class="input-wrapper">
                            <label for="festejado">Nombre del festejado(a) *</label>
                            <input type="text" id="festejado" name="festejado" class="form-control" required>
                        </article>
                        <article class="input-wrapper">
                            <label for="evento_fecha">Fecha del evento *</label>
                            <input type="date" id="evento_fecha" name="evento_fecha" class="form-control" required>
                        </article>
                        <article class="input-wrapper">
                            <label for="paquete_no">Paquete No.</label>
                            <input type="text" id="paquete_no" name="paquete_no" class="form-control">
                        </article>
                    </section>
                    <section class="input-grid grid-3" style="margin-top: 1.5rem;">
                        <article class="input-wrapper">
                            <label for="horas_evento">Horas de servicio *</label>
                            <input type="number" id="horas_evento" name="horas_evento" class="form-control" required min="1" value="5">
                        </article>
                        <article class="input-wrapper">
                            <label for="recepcion_hora">Hora de Bienvenida</label>
                            <input type="time" id="recepcion_hora" name="recepcion_hora" class="form-control">
                        </article>
                        <article class="input-wrapper">
                            <label for="inicio_hora">Hora Inicio del Evento *</label>
                            <input type="time" id="inicio_hora" name="inicio_hora" class="form-control" required>
                        </article>
                    </section>
                </fieldset>

                <!-- SECCIÓN 3: ALIMENTOS Y CONFIGURACIÓN -->
                <fieldset class="form-section">
                    <legend>Alimentos y Configuración</legend>
                    <section class="input-grid grid-3">
                        <article class="input-wrapper">
                            <label for="tipo_comida">Comida</label>
                            <select id="tipo_comida" name="tipo_comida" class="form-control">
                                <option value="">Seleccione...</option>
                                <option value="Taquiza">Taquiza</option>
                                <option value="Parrillada">Parrillada</option>
                                <option value="Buffet">Buffet</option>
                            </select>
                        </article>
                        <article class="input-wrapper">
                            <label for="num_adultos">Para (Adultos) *</label>
                            <input type="number" id="num_adultos" name="num_adultos" class="form-control" required min="0">
                        </article>
                        <article class="input-wrapper">
                            <label for="num_ninos">Buffet infantil de niños para *</label>
                            <input type="number" id="num_ninos" name="num_ninos" class="form-control" required min="0">
                        </article>
                    </section>
                    <article class="input-wrapper" style="margin-top: 1.5rem;">
                        <label for="seleccion_guisados">Selección de Guisados y</label>
                        <textarea id="seleccion_guisados" name="seleccion_guisados" class="form-control" rows="2"></textarea>
                    </article>
                    <section class="input-grid grid-2" style="margin-top: 1.5rem;">
                        <fieldset class="input-wrapper" style="border: none; padding: 0; margin: 0;">
                            <legend style="font-weight: 700; font-size: 0.95rem; margin-bottom: 0.5rem; color: var(--text-main);">Sabores de Agua (Elige máx. 2)</legend>
                            <section style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                                <label class="checkbox-label"><input type="checkbox" name="sabor_agua[]" value="Jamaica" class="sabor-checkbox"> Jamaica</label>
                                <label class="checkbox-label"><input type="checkbox" name="sabor_agua[]" value="Horchata" class="sabor-checkbox"> Horchata</label>
                                <label class="checkbox-label"><input type="checkbox" name="sabor_agua[]" value="Tamarindo" class="sabor-checkbox"> Tamarindo</label>
                                <label class="checkbox-label"><input type="checkbox" name="sabor_agua[]" value="Limón" class="sabor-checkbox"> Limón</label>
                            </section>
                        </fieldset>
                        <article class="input-wrapper">
                            <label for="otras_bebidas_cual">Otras bebidas ¿Cuál?</label>
                            <input type="text" id="otras_bebidas_cual" name="otras_bebidas_cual" class="form-control">
                        </article>
                    </section>
                    <section class="input-grid grid-4" style="margin-top: 1.5rem;">
                        <article class="input-wrapper checkbox-wrapper"><label class="checkbox-label"><input type="checkbox" name="tiene_pinata" value="1"> Piñata</label></article>
                        <article class="input-wrapper checkbox-wrapper"><label class="checkbox-label"><input type="checkbox" name="tiene_show" value="1"> Show</label></article>
                        <article class="input-wrapper checkbox-wrapper"><label class="checkbox-label"><input type="checkbox" name="arco_globos" value="1"> Arco globos</label></article>
                        <article class="input-wrapper checkbox-wrapper"><label class="checkbox-label"><input type="checkbox" name="derecho_pista_check" value="1"> Der. pista</label></article>
                    </section>
                </fieldset>

                <!-- SECCIÓN 4: DESGLOSE DE COSTOS -->
                <fieldset class="form-section">
                    <legend>Desglose de Costos ($)</legend>
                    <p class="section-desc">Presiona "Enter" en cualquier costo para sumarlo automáticamente. (No incluye anticipo).</p>
                    <section class="input-grid grid-4">
                        <article class="input-wrapper"><label>Renta de Salón</label><input type="number" step="0.01" min="0" name="c_renta_salon" class="form-control cost-input"></article>
                        <article class="input-wrapper"><label>Otras Bebidas</label><input type="number" step="0.01" min="0" name="c_otras_bebidas" class="form-control cost-input"></article>
                        <article class="input-wrapper"><label>Piñata</label><input type="number" step="0.01" min="0" name="c_pinata" class="form-control cost-input"></article>
                        <article class="input-wrapper"><label>Mesa de Dulces</label><input type="number" step="0.01" min="0" name="c_mesa_dulces" class="form-control cost-input"></article>
                        
                        <article class="input-wrapper"><label>Show</label><input type="number" step="0.01" min="0" name="c_show" class="form-control cost-input"></article>
                        <article class="input-wrapper"><label>USB Video c/reseña</label><input type="number" step="0.01" min="0" name="c_usb_video" class="form-control cost-input"></article>
                        <article class="input-wrapper"><label>Álbum Digital</label><input type="number" step="0.01" min="0" name="c_album_digital" class="form-control cost-input"></article>
                        <article class="input-wrapper"><label>Paquete Álbum</label><input type="number" step="0.01" min="0" name="c_album_paquete" class="form-control cost-input"></article>
                        
                        <article class="input-wrapper"><label>Derecho de Pista</label><input type="number" step="0.01" min="0" name="c_derecho_pista" class="form-control cost-input"></article>
                        <article class="input-wrapper"><label>Hora Extra</label><input type="number" step="0.01" min="0" name="c_hora_extra" class="form-control cost-input"></article>
                        <article class="input-wrapper"><label>Cámara 360°</label><input type="number" step="0.01" min="0" name="c_camara_360" class="form-control cost-input"></article>
                        <article class="input-wrapper"><label>Amenización</label><input type="number" step="0.01" min="0" name="c_amenizacion" class="form-control cost-input"></article>
                        
                        <article class="input-wrapper"><label>Personas Adic.</label><input type="number" step="0.01" min="0" name="c_personas_adicionales" class="form-control cost-input"></article>
                        <article class="input-wrapper"><label>Café</label><input type="number" step="0.01" min="0" name="c_cafe" class="form-control cost-input"></article>
                        <article class="input-wrapper"><label>Mickey Móvil</label><input type="number" step="0.01" min="0" name="c_mickey_movil" class="form-control cost-input"></article>
                        <article class="input-wrapper"><label>Otros</label><input type="number" step="0.01" min="0" name="c_otros" class="form-control cost-input"></article>
                    </section>
                    <section class="input-grid grid-2" style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                        <article class="input-wrapper">
                            <label for="monto_total" class="highlight-label">MONTO TOTAL ($) *</label>
                            <input type="number" step="0.01" id="monto_total" name="monto_total" class="form-control total-input" required readonly>
                        </article>
                        <article class="input-wrapper">
                            <label for="total_letra">Total en Letra *</label>
                            <input type="text" id="total_letra" name="total_letra" class="form-control" placeholder="Ej. Diez mil pesos 00/100 M.N." required>
                        </article>
                    </section>
                </fieldset>

                <!-- SECCIÓN 5: PAGOS, NOTAS Y CIERRE -->
                <fieldset class="form-section">
                    <legend>Pagos, Notas y Cierre</legend>
                    <section class="input-grid grid-3">
                        <article class="input-wrapper">
                            <label for="anticipo">Anticipo de Reserva ($)</label>
                            <input type="number" step="0.01" min="0" id="anticipo" name="anticipo" class="form-control cost-input" required>
                        </article>
                        <article class="input-wrapper">
                            <label for="recibo_transferencia">No. de Recibo o Transferencia</label>
                            <input type="text" id="recibo_transferencia" name="recibo_transferencia" class="form-control">
                        </article>
                        <article class="input-wrapper">
                            <label for="invitacion">Invitación</label>
                            <input type="text" id="invitacion" name="invitacion" class="form-control">
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
                <footer class="form-actions" style="gap: 1rem;">
                    <!-- Botón de Imprimir -->
                    <button type="button" class="btn-print" onclick="window.print()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Imprimir Contrato
                    </button>
                    <!-- Botón de Guardar -->
                    <button type="submit" class="btn-submit">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Guardar Contrato
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
                totalInput.value = suma.toFixed(2);
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
        });
    </script>
</body>
</html>