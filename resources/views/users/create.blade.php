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

    <header class="dashboard-header header-users-module">
        <a href="{{ route('users.index') }}" class="btn-back-nav btn-back-users">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver a Usuarios
        </a>
    </header>

    <main class="dashboard-layout main-users-layout">
        <fieldset class="form-card">
            <legend class="sr-only">Formulario de Nuevo Usuario</legend>
            <h1 class="title-primary">Crear Usuario</h1>
            <p class="subtitle-muted">Registra un nuevo usuario en la plataforma.</p>

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

                <button type="submit" class="btn-submit">Guardar Usuario</button>
            </form>
        </fieldset>
    </main>
</body>
</html>
