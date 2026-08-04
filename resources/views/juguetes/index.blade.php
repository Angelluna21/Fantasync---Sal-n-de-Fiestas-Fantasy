<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Juguetes · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/eventos.css'])
    <style>
        .stock-alert {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
            padding: 0.5rem 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            color: #b91c1c;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .form-restar {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .input-restar {
            width: 60px;
            padding: 0.3rem;
            font-size: 0.85rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-restar {
            padding: 0.3rem 0.6rem;
            font-size: 0.85rem;
            background-color: var(--primary-purple);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-restar:hover {
            background-color: #5f1b6d;
        }
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
                <a href="{{ route('dashboard') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Dashboard
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 2rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Gestión</p>
                    <h1 class="dashboard-title" style="font-size: 2.8rem; margin-top: 0.2rem;">Inventario de Juguetes</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.1rem;">Controla el stock y registra salidas para shows o bienvenidas.</p>
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

            <header style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                <form action="{{ route('juguetes.index') }}" method="GET" style="display: flex; gap: 0.5rem; width: 300px;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar juguete..." class="form-control" style="font-size: 0.85rem; padding: 0.5rem;">
                    <button type="submit" class="btn-restar" style="padding: 0.5rem 1rem;">Buscar</button>
                </form>
                <a href="{{ route('juguetes.create') }}" class="btn-event-link generate" style="display: inline-flex; align-items: center; gap: 8px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Añadir Nuevo Juguete
                </a>
            </header>

            <section class="table-wrapper">
                <table class="eventos-table">
                    <thead>
                        <tr>
                            <th>Nombre del Juguete</th>
                            <th>Descripción</th>
                            <th>Stock Actual</th>
                            <th>Stock Mínimo</th>
                            <th>Salida Rápida (Uso)</th>
                            <th class="table-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($juguetes as $juguete)
                            <tr>
                                <td>
                                    <h3 class="event-info-name">{{ $juguete->nombre }}</h3>
                                    @if($juguete->stock_actual <= $juguete->stock_minimo)
                                        <div class="stock-alert" style="margin-top: 0.5rem; margin-bottom: 0;">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            ¡Stock Bajo!
                                        </div>
                                    @endif
                                </td>
                                <td><span class="finance-muted">{{ $juguete->descripcion ?: '-' }}</span></td>
                                <td>
                                    <span style="font-size: 1.25rem; font-weight: bold; color: {{ $juguete->stock_actual <= $juguete->stock_minimo ? '#b91c1c' : '#059669' }};">
                                        {{ $juguete->stock_actual }}
                                    </span>
                                </td>
                                <td><span class="finance-muted">{{ $juguete->stock_minimo }}</span></td>
                                <td>
                                    <form action="{{ route('juguetes.restar', $juguete) }}" method="POST" class="form-restar" style="flex-wrap: wrap;">
                                        @csrf
                                        <select name="contrato_id" class="form-control" style="font-size: 0.75rem; padding: 0.3rem; width: 220px;" required>
                                            <option value="">Contrato...</option>
                                            @foreach($contratos as $contrato)
                                                <option value="{{ $contrato->id }}">{{ $contrato->evento->cliente->nombre_completo ?? 'Sin Cliente' }} ({{ \Carbon\Carbon::parse($contrato->evento->fecha)->format('d/m') }})</option>
                                            @endforeach
                                        </select>
                                        <input type="number" name="cantidad" value="1" min="1" max="{{ $juguete->stock_actual }}" class="input-restar" required>
                                        <button type="submit" class="btn-restar">Restar</button>
                                    </form>
                                </td>
                                <td class="table-center">
                                    <menu class="actions-group">
                                        <a href="{{ route('juguetes.historial', $juguete) }}" class="btn-event-link">
                                            Historial
                                        </a>
                                        <a href="{{ route('juguetes.edit', $juguete) }}" class="btn-event-link">
                                            Editar
                                        </a>
                                        <form action="{{ route('juguetes.destroy', $juguete) }}" method="POST" class="inline-block form-inline-m0" onsubmit="return confirm('¿Seguro que deseas eliminar este juguete del catálogo?');" style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-event-link" style="color: #ef4444;">Eliminar</button>
                                        </form>
                                    </menu>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="table-empty">No hay juguetes registrados en el inventario.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        </section>
    </main>
</body>
</html>
