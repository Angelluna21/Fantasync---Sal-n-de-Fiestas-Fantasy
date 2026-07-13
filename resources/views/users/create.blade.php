<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario | FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
    <style>
        .form-card {
            background: white;
            border-radius: 1.5rem;
            padding: 2.5rem;
            box-shadow: var(--shadow-sm);
            max-width: 600px;
            margin: 2rem auto;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 800;
            color: var(--text-main);
        }
        .form-control {
            width: 100%;
            box-sizing: border-box;
            padding: 0.85rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 1rem;
            font-family: 'Nunito', sans-serif;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--accent-yellow);
            box-shadow: 0 0 0 3px rgba(255, 213, 79, 0.3);
        }
        .error-message {
            color: #dc3545;
            font-size: 0.85rem;
            font-weight: 800;
            margin-top: 0.5rem;
            display: block;
        }
        .btn-submit {
            background: linear-gradient(to right, var(--primary-purple), var(--secondary-purple));
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 2rem;
            font-weight: 800;
            font-size: 1.05rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(122, 40, 138, 0.2);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(155, 48, 176, 0.3);
        }
    </style>
</head>
<body class="dashboard-page">
    <figure class="dashboard-background" aria-hidden="true"></figure>

    <header class="dashboard-header" style="max-width: 1100px; margin: 0 auto; text-align: left; padding: 2rem;">
        <a href="{{ route('users.index') }}" class="btn-back-nav" style="margin: 0;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver a Usuarios
        </a>
    </header>

    <main class="dashboard-layout" style="padding-top: 0;">
        <fieldset class="form-card">
            <legend class="sr-only">Formulario de Nuevo Usuario</legend>
            <h1 style="margin: 0 0 0.5rem 0; color: var(--primary-purple);">Crear Usuario</h1>
            <p style="color: var(--text-muted); margin-bottom: 2rem;">Registra un nuevo usuario en la plataforma.</p>

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

                <section class="form-group" style="margin-bottom: 2.5rem;">
                    <label class="form-label" for="password_confirmation">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </section>

                <button type="submit" class="btn-submit">Guardar Usuario</button>
            </form>
        </fieldset>
    </main>
</body>
</html>
