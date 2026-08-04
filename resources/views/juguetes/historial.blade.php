<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Juguete · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/eventos.css'])
    <style>
        .custom-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .custom-modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }
        .modal-title {
            font-size: 1.25rem;
            color: #1f2937;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .modal-desc {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        .modal-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }
        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }
        .btn-cancel {
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            background: white;
            border-radius: 6px;
            color: #374151;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-confirm {
            padding: 0.5rem 1rem;
            border: none;
            background: #ef4444;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-confirm:hover { background: #dc2626; }
        .btn-cancel:hover { background: #f3f4f6; }
    </style>
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
                <a href="{{ route('juguetes.index') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a Juguetes
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 2rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Historial de Movimientos</p>
                    <h1 class="dashboard-title" style="font-size: 2.8rem; margin-top: 0.2rem;">{{ $juguete->nombre }}</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.1rem;">Registro de salidas para shows y bienvenidas.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <section style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </section>
        </section>

        <section class="eventos-section" style="margin-top: 8.5rem;">
            @if(session('success'))
                <div class="alert alert-success" style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <header style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
                @if($movimientos->count() > 0)
                    <button type="button" onclick="document.getElementById('passwordModal').classList.add('active')" class="btn-event-link" style="color: #ef4444; border-color: #ef4444; display: inline-flex; align-items: center; gap: 8px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Limpiar Historial
                    </button>

                    <!-- Custom Modal -->
                    <div id="passwordModal" class="custom-modal">
                        <div class="modal-content">
                            <h3 class="modal-title">Autorización Requerida</h3>
                            <p class="modal-desc">Por seguridad, ingresa tu contraseña para autorizar la limpieza de todo el historial. Esta acción no se puede deshacer.</p>
                            
                            <form action="{{ route('juguetes.limpiar-historial', $juguete) }}" method="POST" id="formLimpiar">
                                @csrf
                                @method('DELETE')
                                <input type="password" name="password" id="modalPassword" class="modal-input" placeholder="Tu contraseña..." required>
                                
                                <div class="modal-actions">
                                    <button type="button" class="btn-cancel" onclick="document.getElementById('passwordModal').classList.remove('active')">Cancelar</button>
                                    <button type="submit" class="btn-confirm">Limpiar Historial</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </header>

            <section class="table-wrapper">
                <table class="eventos-table">
                    <thead>
                        <tr>
                            <th>Fecha de Salida</th>
                            <th>Tipo de Movimiento</th>
                            <th>Cantidad</th>
                            <th>Contrato Destino (Cliente)</th>
                            <th class="table-center">Enlace a Contrato</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientos as $movimiento)
                            <tr>
                                <td>
                                    <span class="event-info-name">{{ \Carbon\Carbon::parse($movimiento->created_at)->format('d/m/Y H:i') }}</span>
                                </td>
                                <td>
                                    <span class="event-badge {{ $movimiento->tipo == 'salida' ? 'cancelado' : 'confirmado' }}" style="text-transform: capitalize;">
                                        {{ $movimiento->tipo }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-weight: bold; font-size: 1.1rem; color: #b91c1c;">
                                        -{{ $movimiento->cantidad }}
                                    </span>
                                </td>
                                <td>
                                    @if($movimiento->contrato)
                                        <span class="badge-sucursal">{{ $movimiento->contrato->evento->cliente->nombre_completo ?? 'Sin Cliente' }}</span>
                                        <span class="finance-muted" style="display: block; font-size: 0.8rem; margin-top: 2px;">
                                            Evento: {{ \Carbon\Carbon::parse($movimiento->contrato->evento->fecha)->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="finance-muted">Salida manual (sin contrato)</span>
                                    @endif
                                </td>
                                <td class="table-center">
                                    @if($movimiento->contrato)
                                        <a href="{{ route('contratos.show', $movimiento->contrato) }}" class="btn-event-link generate" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;">
                                            Ver Contrato
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="table-empty">No hay movimientos registrados para este juguete.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        </section>
    </main>
</body>
</html>
