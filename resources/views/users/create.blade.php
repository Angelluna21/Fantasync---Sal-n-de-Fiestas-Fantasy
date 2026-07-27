<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario | FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/users.css'])
</head>
<body class="dashboard-page">
    <figure class="dashboard-background" aria-hidden="true"></figure>

    <main class="dashboard-layout">
        <!-- Navegación superior y Encabezado Unificado -->
        <section class="top-nav" aria-label="Menú superior" style="align-items: flex-start; margin-bottom: 2rem; padding-bottom: 0;">
            <!-- Lado Izquierdo: Logo y Botón Volver -->
            <section style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <a href="{{ route('dashboard') }}" aria-label="Volver al panel" class="logo-link" style="width: fit-content;">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="nav-logo" style="height: 100px;">
                </a>
                <a href="{{ route('users.index') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver a Usuarios
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 2rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Administración</p>
                    <h1 class="dashboard-title" style="font-size: 2.8rem; margin-top: 0.2rem;">Crear Usuario</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.1rem;">Registra un nuevo usuario y define su nivel de acceso al sistema.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario -->
            <section style="flex: 1; display: flex; justify-content: flex-end; padding-top: 15px;">
                <x-user-menu />
            </section>
        </section>

        <section class="form-card" style="margin-top: 6rem;">
            <legend class="sr-only">Formulario de Nuevo Usuario</legend>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <section class="form-group">
                    <label class="form-label" for="name">Nombre Completo</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <span class="error-message">{{ $message }}</span> @enderror
                </section>

                <section class="form-group">
                    <label class="form-label" for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <span class="error-message">{{ $message }}</span> @enderror
                </section>

                <section class="form-group">
                    <label class="form-label" for="role">Rol</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Normal (Usuario)</option>
                        <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Administrador</option>
                    </select>
                    @error('role') <span class="error-message">{{ $message }}</span> @enderror
                </section>

                <section class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @error('password') <span class="error-message">{{ $message }}</span> @enderror
                </section>

                <section class="form-group form-group-last">
                    <label class="form-label" for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </section>

                <footer style="display: flex !important; flex-direction: row !important; gap: 1.5rem; justify-content: center; align-items: center; width: 100%; margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0;">
                    <a href="{{ route('users.index') }}" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.8rem 2rem; background-color: #f1f5f9; color: #475569; font-weight: 700; font-size: 0.95rem; border: 1px solid #cbd5e1; border-radius: 2rem; text-decoration: none; transition: all 0.2s; min-width: 160px;">
                        Cancelar
                    </a>
                    <button type="submit" style="display: inline-flex; align-items: center; justify-content: center; padding: 0.8rem 2.5rem; background: linear-gradient(135deg, #7a288a, #d81b60); color: #ffffff; font-weight: 800; font-size: 0.95rem; border: none; border-radius: 2rem; cursor: pointer; box-shadow: 0 4px 15px rgba(122, 40, 138, 0.3); transition: all 0.2s; min-width: 180px;">
                        Guardar Usuario
                    </button>
                </footer>
            </form>
        </section>
    </main>
</body>
</html>
