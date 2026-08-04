<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Juguete · FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/eventos.css'])
</head>
<body>
    <figure class="dashboard-background" aria-hidden="true"></figure>
    
    <main class="dashboard-layout">
        <section class="top-nav" style="align-items: flex-start; margin-bottom: 2rem; padding-bottom: 0;">
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                <a href="{{ route('juguetes.index') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a Juguetes
                </a>
            </section>

            <header class="dashboard-header" style="margin: 2rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Inventario</p>
                    <h1 class="dashboard-title" style="font-size: 2.8rem; margin-top: 0.2rem;">Añadir Nuevo Juguete</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.1rem;">Registra un juguete nuevo para Shows o Bienvenidas.</p>
                </hgroup>
            </header>

            <section style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </section>
        </section>

        <section class="eventos-section" style="margin-top: 8.5rem;">
            <form action="{{ route('juguetes.store') }}" method="POST" class="nomina-form-container" style="max-width: 600px;">
                @csrf
                <fieldset class="form-group border-0 p-0 m-0 form-fieldset-lg">
                    <label class="form-label form-label-semibold">Nombre del Juguete</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" required placeholder="Ej. Varitas luminosas">
                    @error('nombre') <span class="form-error-msg">{{ $message }}</span> @enderror
                </fieldset>

                <fieldset class="form-group border-0 p-0 m-0 form-fieldset-lg">
                    <label class="form-label form-label-semibold">Descripción (Opcional)</label>
                    <input type="text" name="descripcion" value="{{ old('descripcion') }}" class="form-control" placeholder="Detalles adicionales">
                    @error('descripcion') <span class="form-error-msg">{{ $message }}</span> @enderror
                </fieldset>

                <section class="form-flex-row">
                    <fieldset class="form-group border-0 p-0 m-0 form-fieldset-flex">
                        <label class="form-label form-label-semibold">Stock Inicial</label>
                        <input type="number" name="stock_actual" value="{{ old('stock_actual', 0) }}" min="0" class="form-control" required>
                        @error('stock_actual') <span class="form-error-msg">{{ $message }}</span> @enderror
                    </fieldset>
                    
                    <fieldset class="form-group border-0 p-0 m-0 form-fieldset-flex">
                        <label class="form-label form-label-semibold">Stock Mínimo (Alerta)</label>
                        <input type="number" name="stock_minimo" value="{{ old('stock_minimo', 0) }}" min="0" class="form-control" required>
                        @error('stock_minimo') <span class="form-error-msg">{{ $message }}</span> @enderror
                    </fieldset>
                </section>

                <section class="pt-4">
                    <button type="submit" class="button-submit btn-submit-full">Registrar Juguete</button>
                </section>
            </form>
        </section>
    </main>
</body>
</html>
