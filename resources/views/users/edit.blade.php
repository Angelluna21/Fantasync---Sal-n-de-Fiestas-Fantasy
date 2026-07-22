<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario | FantaSync</title>
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
            <legend class="sr-only">Formulario de Edición de Usuario</legend>
            <h1 class="title-primary">Editar Usuario</h1>
            <p class="subtitle-muted">Modifica los datos o permisos de este usuario.</p>

            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <section class="form-group">
                    <label class="form-label" for="name">Nombre Completo</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    @error('name') <span class="error-message">{{ $message }}</span> @enderror
                </section>

                <section class="form-group">
                    <label class="form-label" for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email') <span class="error-message">{{ $message }}</span> @enderror
                </section>

                <section class="form-group">
                    <label class="form-label" for="role">Rol</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Normal (Usuario)</option>
                        <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Super Administrador</option>
                    </select>
                    @error('role') <span class="error-message">{{ $message }}</span> @enderror
                </section>

                <section class="form-section-optional">
                    <h2 class="subtitle-secondary">Cambiar Contraseña <span class="text-optional">(Opcional)</span></h2>
                </section>

                <section class="form-group">
                    <label class="form-label" for="password">Nueva Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control">
                    @error('password') <span class="error-message">{{ $message }}</span> @enderror
                </section>

                <section class="form-group form-group-last">
                    <label class="form-label" for="password_confirmation">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </section>

                <button type="submit" class="btn-submit">Guardar Cambios</button>
            </form>
        </fieldset>
    </main>
</body>
</html>
