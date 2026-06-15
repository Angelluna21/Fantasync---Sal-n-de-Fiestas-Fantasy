<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Restablecer contraseña | FantaSync</title>
        @vite(['resources/css/app.css', 'resources/css/auth.css'])
    </head>
    <body class="auth-page">
        <figure class="auth-background" aria-hidden="true"></figure>

        <main class="auth-card" role="main">
            <article class="auth-card__content">
                <header class="auth-header">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="auth-logo">
                    
                    <hgroup class="auth-card__brand">
                        <p class="eyebrow">Nueva contraseña</p>
                        <h1 class="auth-card__title">Seguridad</h1>
                        <p class="auth-card__description">Ingresa tu nueva contraseña para acceder al panel de administración.</p>
                    </hgroup>
                </header>

                <form method="POST" action="{{ route('password.update') }}" novalidate class="auth-form">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <fieldset class="form-group border-0 p-0 m-0">
                        <label class="form-label" for="email">Correo electrónico</label>
                        <input id="email" name="email" type="email" class="form-control" value="{{ $email ?? old('email') }}" required autofocus readonly>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <fieldset class="form-group border-0 p-0 m-0">
                        <label class="form-label" for="password">Nueva Contraseña</label>
                        <input id="password" name="password" type="password" class="form-control" required placeholder="••••••••">
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <fieldset class="form-group border-0 p-0 m-0">
                        <label class="form-label" for="password_confirmation">Confirmar Contraseña</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required placeholder="••••••••">
                    </fieldset>

                    <footer class="form-actions">
                        <button type="submit" class="button-submit">Restablecer Contraseña</button>
                    </footer>
                </form>
            </article>
        </main>
    </body>
</html>
