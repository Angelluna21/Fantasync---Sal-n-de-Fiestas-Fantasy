<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Fantasync') }}</title>
        @fonts
        <link rel="stylesheet" href="{{ asset('fiori.css') }}">
    </head>
    <body class="page-root">
        <main class="welcome-panel" role="main">
            <section class="hero-band">
                <header class="hero-copy-block">
                    <hgroup>
                        <p class="eyebrow">Operadora de Fiestas Fantasy</p>
                        <h1 class="hero-title">Tu primer flujo de contrato y operación, listo para crecer.</h1>
                        <p class="hero-copy">
                            Fantasync te ayuda a transformar el contrato de eventos en un flujo real: salón, menú, servicios, pagos y control operativo.
                            Esta primera página está pensada para que empieces con una visión clara del producto y entres al panel con un solo clic.
                        </p>
                    </hgroup>
                    <nav class="action-row" aria-label="Acciones principales">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="button-primary">Iniciar sesión</a>
                        @endif
                        <a href="#flujo" class="button-secondary">Ver el flujo base</a>
                    </nav>
                    <ul class="hero-pills" aria-label="Elementos clave del proyecto" style="list-style:none; padding:0; display:flex; flex-wrap:wrap; gap:0.5rem;">
                        <li><span>Contrato</span></li>
                        <li><span>Salón</span></li>
                        <li><span>Platillos</span></li>
                        <li><span>Pagos</span></li>
                        <li><span>Operación</span></li>
                    </ul>
                </header>
                <aside class="hero-card">
                    <p class="hero-card-label">Qué verás primero</p>
                    <ul>
                        <li>Reservas de salón con reglas del contrato</li>
                        <li>Cálculo de platillos y servicios adicionales</li>
                        <li>Seguimiento de pagos y vencimientos</li>
                        <li>Operación del evento con validaciones</li>
                    </ul>
                </aside>
            </section>

            <section class="info-grid" id="flujo">
                <article class="info-card">
                    <p class="info-card-label">1. Contrato</p>
                    <h2>Convierte el PDF en reglas de negocio</h2>
                    <p>Define el salón, el tipo de evento, la persona, el paquete y los servicios que se van a ofrecer.</p>
                </article>
                <article class="info-card">
                    <p class="info-card-label">2. Menú</p>
                    <h2>Calcula el costo por categoría</h2>
                    <p>Organiza platillos, bebidas, extras y validaciones de cantidad para obtener el total del evento.</p>
                </article>
                <article class="info-card">
                    <p class="info-card-label">3. Pagos</p>
                    <h2>Controla las fechas y el cumplimiento</h2>
                    <p>Gestiona anticipo, abonos, 50% mínimo y liquidación final a 15 días antes del evento.</p>
                </article>
                <article class="info-card">
                    <p class="info-card-label">4. Operación</p>
                    <h2>Evita incidencias en el día del evento</h2>
                    <p>Aplica reglas de seguridad, descorche, pista, transporte y uso de imagen sin perder trazabilidad.</p>
                </article>
            </section>

            <section class="status-bar" aria-labelledby="status-title">
                <hgroup>
                    <p class="eyebrow">Listo para comenzar</p>
                    <h2 id="status-title" class="status-title">La primera pantalla debe guiar al usuario hacia el contrato y el panel.</h2>
                </hgroup>
                <p class="status-text">
                    Esta landing page recomienda iniciar con una narrativa clara del negocio, una vista rápida del flujo y un CTA visible.
                    Después puedes evolucionarla con una tabla de contratos abiertos, un resumen de pagos y un calendario de eventos.
                </p>
            </section>

            <footer class="footer-note">
                <p>Operadora de Fiestas Fantasy</p>
                <p>Fantasync</p>
            </footer>
        </main>
    </body>
</html>
