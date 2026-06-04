<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Recuperar contraseña | FantaSync</title>
        @vite(['resources/css/app.css', 'resources/css/auth.css'])
    </head>
    <body class="auth-page">
        <figure class="auth-background" aria-hidden="true"></figure>

        <main class="auth-card" role="main">
            <article class="auth-card__content">
                <header class="auth-header">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FantaSync" class="auth-logo">
                    
                    <hgroup class="auth-card__brand">
                        <p class="eyebrow">Recuperación</p>
                        <h1 class="auth-card__title">Contraseña</h1>
                        <p class="auth-card__description">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
                    </hgroup>
                </header>

                @if (session('status'))
                    <output class="form-error form-error--global" style="background: rgba(76, 175, 80, 0.15); color: #2e7d32; border-color: rgba(76, 175, 80, 0.3);">
                        {{ session('status') }}
                    </output>
                @endif

                <form method="POST" action="{{ route('password.email') }}" novalidate class="auth-form">
                    @csrf

                    <fieldset class="form-group border-0 p-0 m-0">
                        <label class="form-label" for="email">Correo electrónico</label>
                        <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="tu@correo.com">
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <footer class="form-actions">
                        <button type="submit" class="button-submit">Enviar enlace de recuperación</button>
                    </footer>

                    <footer class="form-footnote" style="margin-top: 1.5rem;">
                        <a href="{{ route('login') }}" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; text-decoration: none;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Volver al inicio de sesión
                        </a>
                    </footer>
                </form>
            </article>
        </main>
    </body>
</html>
