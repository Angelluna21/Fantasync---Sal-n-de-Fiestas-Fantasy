<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Salón · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/salones.css'])

</head>

<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>

    <main class="dashboard-layout">
        <!-- Navegación superior -->
        <nav class="top-nav" aria-label="Menú superior">
            <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo">
            </a>

            <x-user-menu />
        </nav>

        <!-- Contenedor -->
        <section class="show-container">
            <a href="{{ route('salones.index') }}" class="btn-back-nav">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a la lista de salones
            </a>

            @if(session('success'))
            <aside role="alert" style="background-color: rgba(76, 175, 80, 0.15); color: #2e7d32; padding: 1rem 1.5rem; border-radius: 1rem; border: 1px solid rgba(76, 175, 80, 0.3); margin-bottom: 1.5rem; font-weight: 700;">
                {{ session('success') }}
            </aside>
            @endif

            <!-- Tarjeta -->
            <article class="detail-card">
                <header class="detail-header">
                    <hgroup>
                        <h1 class="detail-title">{{ $salon->nombre }}</h1>
                        @if($salon->alias)
                        <span class="salon-alias">{{ $salon->alias }}</span>
                        @endif
                    </hgroup>
                    <span class="salon-badge">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </span>
                </header>

                <section class="detail-body">
                    <!-- Datos Técnicos -->
                    <section class="detail-section" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; border-bottom: 1px solid rgba(122, 40, 138, 0.1); padding-bottom: 1.5rem;">
                        <article class="detail-block">
                            <span class="detail-label" style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Capacidad Máxima</span>
                            <span class="detail-value" style="font-size: 1.15rem; font-weight: 800; color: var(--primary-purple);">
                                {{ $salon->capacidad ? "{$salon->capacidad} personas" : 'No especificada' }}
                            </span>
                        </article>

                        <article class="detail-block">
                            <span class="detail-label" style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Estado del Salón</span>
                            <span class="status-badge status-{{ $salon->estado === 'activo' ? 'verde' : ($salon->estado === 'mantenimiento' ? 'amarillo' : 'rojo') }}" style="display: inline-block;">
                                @if($salon->estado === 'activo')
                                    🟢 Activo
                                @elseif($salon->estado === 'mantenimiento')
                                    🟡 Mantenimiento
                                @else
                                    🔴 Inactivo
                                @endif
                            </span>
                        </article>

                        @if($salon->alias)
                        <article class="detail-block">
                            <span class="detail-label" style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 0.25rem;">Sobrenombre / Alias</span>
                            <span class="detail-value" style="font-size: 1.1rem; font-weight: 700; color: var(--text-main);">
                                {{ $salon->alias }}
                            </span>
                        </article>
                        @endif
                    </section>

                    @if($salon->descripcion)
                    <!-- Descripción y Amenidades -->
                    <section class="detail-section">
                        <h2 class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Descripción y Amenidades
                        </h2>
                        <p style="font-size: 0.95rem; color: var(--text-muted); line-height: 1.6; margin: 0; background: rgba(122, 40, 138, 0.02); padding: 1rem; border-radius: 12px; border: 1px solid rgba(122, 40, 138, 0.08);">
                            {{ $salon->descripcion }}
                        </p>
                    </section>
                    @endif

                    <!-- Dirección y Google Maps -->
                    <section class="detail-section">
                        <h2 class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Ubicación
                        </h2>
                        @if($salon->direccion)
                            <p class="section-text" style="margin-bottom: 1rem; color: var(--text-main); font-weight: 700;">
                                {{ $salon->direccion }}
                            </p>
                            <div class="map-container" style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(122, 40, 138, 0.15); box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                                <iframe 
                                    width="100%" 
                                    height="350" 
                                    frameborder="0" 
                                    style="border:0; display: block;" 
                                    src="https://maps.google.com/maps?q={{ urlencode($salon->direccion) }}&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @else
                            @if($salon->sucursal)
                                <p class="section-text">{{ $salon->sucursal->nombre }}</p>
                                <p style="margin: 0.25rem 0 1rem 0; font-size: 0.9rem; color: #8c8c8c; font-weight: 500;">
                                    {{ $salon->sucursal->direccion }}
                                </p>
                                <div class="map-container" style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(122, 40, 138, 0.15); box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                                    <iframe 
                                        width="100%" 
                                        height="350" 
                                        frameborder="0" 
                                        style="border:0; display: block;" 
                                        src="https://maps.google.com/maps?q={{ urlencode($salon->sucursal->direccion) }}&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            @else
                                <p class="section-text" style="color: #bcbcbc; font-style: italic;">Sin ubicación configurada</p>
                            @endif
                        @endif
                    </section>

                    <!-- Calendario de Eventos -->
                    <section class="detail-section">
                        <h2 class="section-title">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Calendario de Eventos
                        </h2>
                        
                        <figure class="calendar-wrapper">
                            <header class="calendar-header">
                                <button type="button" class="btn-calendar-nav" id="prev-month" aria-label="Mes anterior">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <h3 class="calendar-month-year" id="calendar-month-year"></h3>
                                <button type="button" class="btn-calendar-nav" id="next-month" aria-label="Mes siguiente">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </header>
                            
                            <table class="calendar-table">
                                <thead>
                                    <tr>
                                        <th>Dom</th>
                                        <th>Lun</th>
                                        <th>Mar</th>
                                        <th>Mié</th>
                                        <th>Jue</th>
                                        <th>Vie</th>
                                        <th>Sáb</th>
                                    </tr>
                                </thead>
                                <tbody id="calendar-body">
                                    <!-- Se generará dinámicamente -->
                                </tbody>
                            </table>
                        </figure>

                        <!-- Ficha de Detalles del Evento -->
                        <article id="event-detail-card" class="event-detail-card" style="display: none;">
                            <!-- Rellenado dinámicamente con JS -->
                        </article>
                    </section>

                    <!-- Acciones -->
                    <menu class="detail-actions">
                        <a href="{{ route('salones.edit', $salon->id) }}" class="btn-show-edit">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar Salón
                        </a>

                        <button type="button" class="btn-show-delete" onclick="confirmDelete('{{ route('salones.destroy', $salon->id) }}', '{{ $salon->nombre }}')">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar Salón
                        </button>
                    </menu>
                </section>
            </article>
        </section>
    </main>

    <footer class="page-footer" style="text-align: center; margin-top: 3rem; padding-bottom: 2rem; color: #8c8c8c; font-size: 0.9rem;">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
    <!-- Diálogo de Confirmación Customizado -->
    <dialog id="delete-confirm-dialog" class="delete-dialog">
        <section class="dialog-content">
            <figure class="dialog-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </figure>
            <h3 class="dialog-title">¿Eliminar Salón?</h3>
            <p class="dialog-message">¿Estás seguro de que deseas eliminar permanentemente el salón <strong id="delete-salon-name"></strong>? Esta acción no se puede deshacer y eliminará todos los eventos asociados.</p>

            <form id="delete-confirm-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <menu class="dialog-actions">
                    <button type="button" class="btn-dialog-cancel" onclick="closeDeleteDialog()">Cancelar</button>
                    <button type="submit" class="btn-dialog-confirm">Eliminar</button>
                </menu>
            </form>
        </section>
    </dialog>

    <script>
        const deleteDialog = document.getElementById('delete-confirm-dialog');
        const deleteForm = document.getElementById('delete-confirm-form');
        const deleteSalonNameEl = document.getElementById('delete-salon-name');

        function confirmDelete(url, salonName) {
            if (deleteDialog && deleteForm && deleteSalonNameEl) {
                deleteForm.action = url;
                deleteSalonNameEl.textContent = salonName;
                deleteDialog.showModal();
            }
        }

        function closeDeleteDialog() {
            if (deleteDialog) {
                deleteDialog.close();
            }
        }

        // Cerrar dialog haciendo clic en el backdrop
        if (deleteDialog) {
            deleteDialog.addEventListener('click', (e) => {
                const dialogDimensions = deleteDialog.getBoundingClientRect();
                if (
                    e.clientX < dialogDimensions.left ||
                    e.clientX > dialogDimensions.right ||
                    e.clientY < dialogDimensions.top ||
                    e.clientY > dialogDimensions.bottom
                ) {
                    deleteDialog.close();
                }
            });
        }
        // Lógica del Calendario de Eventos
        const salonEvents = @json($salon->eventos);
        const calendarMonthYear = document.getElementById('calendar-month-year');
        const calendarBody = document.getElementById('calendar-body');
        const eventDetailCard = document.getElementById('event-detail-card');
        const prevMonthBtn = document.getElementById('prev-month');
        const nextMonthBtn = document.getElementById('next-month');

        let currentDate = new Date();

        const monthNames = [
            "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];

        function renderCalendar(date) {
            const year = date.getFullYear();
            const month = date.getMonth();

            // Primer día del mes
            const firstDay = new Date(year, month, 1).getDay();
            // Número de días en el mes
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            calendarMonthYear.textContent = `${monthNames[month]} ${year}`;
            calendarBody.innerHTML = '';

            let dateCounter = 1;
            let rows = [];

            for (let i = 0; i < 6; i++) {
                let cells = [];
                let rowHasDays = false;

                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDay) {
                        cells.push('<td class="calendar-day empty"></td>');
                    } else if (dateCounter > daysInMonth) {
                        cells.push('<td class="calendar-day empty"></td>');
                    } else {
                        rowHasDays = true;
                        const dayString = String(dateCounter).padStart(2, '0');
                        const monthString = String(month + 1).padStart(2, '0');
                        const dateKey = `${year}-${monthString}-${dayString}`;

                        // Buscar si hay eventos este día
                        const dayEvents = salonEvents.filter(e => {
                            if (!e.fecha) return false;
                            const evFecha = e.fecha.split('T')[0];
                            return evFecha === dateKey;
                        });

                        const isToday = new Date().toDateString() === new Date(year, month, dateCounter).toDateString();
                        let classes = 'calendar-day';
                        if (isToday) classes += ' today';
                        if (dayEvents.length > 0) classes += ' has-event';

                        cells.push(`
                            <td>
                                <span class="${classes}" data-date="${dateKey}" onclick="selectDay(this, ${JSON.stringify(dayEvents).replace(/"/g, '&quot;')})">
                                    ${dateCounter}
                                </span>
                            </td>
                        `);
                        dateCounter++;
                    }
                }

                if (rowHasDays) {
                    rows.push(`<tr>${cells.join('')}</tr>`);
                }
            }

            calendarBody.innerHTML = rows.join('');
        }

        function selectDay(element, dayEvents) {
            // Deseleccionar días anteriores
            document.querySelectorAll('.calendar-day.selected').forEach(el => el.classList.remove('selected'));

            if (!element.classList.contains('empty')) {
                element.classList.add('selected');
            }

            if (dayEvents && dayEvents.length > 0) {
                const event = dayEvents[0]; // Mostrar el primer evento del día

                // Traducir estados
                const states = {
                    'cotizacion': 'Cotización',
                    'confirmado': 'Confirmado',
                    'finalizado': 'Finalizado',
                    'cancelado': 'Cancelado'
                };
                const stateLabel = states[event.estado] || event.estado;

                // Formatear fecha legible
                const dateParts = event.fecha.split('T')[0].split('-');
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const formattedDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]).toLocaleDateString('es-ES', options);

                // Formatear horas
                const formatTime = (timeStr) => {
                    if (!timeStr) return 'N/A';
                    const parts = timeStr.split(':');
                    return `${parts[0]}:${parts[1]} hrs`;
                };

                eventDetailCard.innerHTML = `
                    <header class="event-detail-header">
                        <hgroup>
                            <h4 class="event-detail-title">${event.titulo || 'Sin título'}</h4>
                            <p style="margin: 0.25rem 0 0 0; font-size: 0.9rem; color: var(--text-muted); font-weight: 700;">
                                Celebración: ${event.tipo_evento || 'Otro'}
                            </p>
                        </hgroup>
                        <span class="event-detail-badge ${event.estado}">${stateLabel}</span>
                    </header>
                    
                    <section class="event-detail-grid">
                        <article class="event-meta-item">
                            <span class="event-meta-label">Fecha</span>
                            <span class="event-meta-value" style="text-transform: capitalize;">${formattedDate}</span>
                        </article>
                        <article class="event-meta-item">
                            <span class="event-meta-label">Festejado(a)</span>
                            <span class="event-meta-value">${event.nombre_festejado || 'No especificado'}</span>
                        </article>
                        <article class="event-meta-item">
                            <span class="event-meta-label">Recepción</span>
                            <span class="event-meta-value">${formatTime(event.hora_recepcion)}</span>
                        </article>
                        <article class="event-meta-item">
                            <span class="event-meta-label">Inicio / Duración</span>
                            <span class="event-meta-value">${formatTime(event.hora_inicio)} (${event.horas_duracion} hrs)</span>
                        </article>
                        <article class="event-meta-item">
                            <span class="event-meta-label">Mantelería</span>
                            <span class="event-meta-value">${event.color_manteleria || 'Estándar'}</span>
                        </article>
                    </section>

                    \${event.notas ? `
                    <section class="event-detail-notes-section">
                        <h5 class="event-detail-notes-title">Notas del Evento</h5>
                        <p class="event-detail-notes-text">\${event.notas}</p>
                    </section>
                    ` : ''}

                    <footer class="event-detail-actions">
                        <a href="/eventos/\${event.id}" class="btn-event-link">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Ver Logística Completa
                        </a>
                    </footer>
                `;
                eventDetailCard.style.display = 'block';
            } else {
                eventDetailCard.style.display = 'none';
            }
        }

        if (prevMonthBtn && nextMonthBtn) {
            prevMonthBtn.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
                eventDetailCard.style.display = 'none';
            });

            nextMonthBtn.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
                eventDetailCard.style.display = 'none';
            });
        }

        // Inicializar calendario
        if (calendarBody) {
            renderCalendar(currentDate);
        }
    </script>
</body>

</html>