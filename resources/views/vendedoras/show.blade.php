<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Vendedora · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/forms.css'])
    <style>
        .details-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(122, 40, 138, 0.15);
        }
        .details-header {
            border-bottom: 2px solid rgba(122, 40, 138, 0.1);
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .vendedora-name {
            font-size: 2.2rem;
            color: var(--primary-purple);
            font-weight: 800;
            margin: 0;
        }
        .status-badge {
            padding: 0.5rem 1.5rem;
            border-radius: 2rem;
            font-weight: 800;
            font-size: 0.95rem;
            text-transform: uppercase;
        }
        .status-activo {
            background-color: #e6f7ed;
            color: #1b8544;
            border: 1px solid #c8ecd4;
        }
        .status-inactivo {
            background-color: #fdf2f2;
            color: #ca3c3c;
            border: 1px solid #fbd5d5;
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        .detail-item {
            background: #fdfaf6;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid rgba(122, 40, 138, 0.08);
        }
        .detail-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .detail-value {
            font-size: 1.15rem;
            color: var(--text-main);
            font-weight: 600;
            margin: 0;
        }
        .actions-footer {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(122, 40, 138, 0.1);
            display: flex;
            gap: 1rem;
        }
    </style>
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <!-- Navegación superior y Encabezado Unificado -->
        <nav class="top-nav" style="align-items: flex-start; margin-bottom: 2rem; padding-bottom: 0;">
            <!-- Lado Izquierdo: Logo y Botones Nav -->
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 3rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none; text-align: center;">
                <hgroup style="text-align: center;">
                    <p class="eyebrow" style="margin: 0 auto; color: rgba(255, 255, 255, 0.8);">Gestión de Personal</p>
                    <h1 class="dashboard-title" style="margin: 0 auto; font-size: 2.5rem; color: white;">Detalle de Vendedora</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; max-width: 600px; color: rgba(255, 255, 255, 0.9);">Consulta la información de contacto y estado actual.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <aside style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </aside>
        </nav>

        <section class="form-section" aria-label="Detalles de la vendedora">
            <section class="form-container">
                <a href="{{ route('vendedoras.index') }}" class="btn-back-nav">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="nav-icon-large"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a la lista
                </a>
                
                <article class="details-card">
                    <header class="details-header">
                        <h2 class="vendedora-name">{{ $vendedora->nombre }} {{ $vendedora->apellidos }}</h2>
                        <span class="status-badge status-{{ $vendedora->estado }}">
                            {{ ucfirst($vendedora->estado) }}
                        </span>
                    </header>

                    <div class="details-grid">
                        <div class="detail-item">
                            <p class="detail-label">Teléfono</p>
                            <p class="detail-value">{{ $vendedora->telefono ? $vendedora->telefono : 'No registrado' }}</p>
                        </div>
                        
                        <div class="detail-item">
                            <p class="detail-label">Correo Electrónico (Email)</p>
                            <p class="detail-value">{{ $vendedora->email ? $vendedora->email : 'No registrado' }}</p>
                        </div>
                        
                        <div class="detail-item">
                            <p class="detail-label">Fecha de Registro</p>
                            <p class="detail-value">{{ $vendedora->created_at ? $vendedora->created_at->format('d/m/Y') : 'Desconocida' }}</p>
                        </div>
                    </div>

                    <footer class="actions-footer">
                        <a href="{{ route('vendedoras.edit', $vendedora->id) }}" class="btn-submit" style="text-decoration: none; display: inline-block; text-align: center;">
                            Editar Información
                        </a>
                    </footer>
                </article>
            </section>
        </section>
    </main>

    <footer class="dashboard-footer">
        <p>© 2026 FantaSync · Sistema de Gestión de Eventos Gastronómicos</p>
    </footer>
</body>
</html>
