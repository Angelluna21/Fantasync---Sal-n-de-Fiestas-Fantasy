<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Iniciar sesión | {{ config('app.name', 'Fantasync') }}</title>
        <link rel="stylesheet" href="{{ asset('fiori.css') }}">
    </head>
    <body class="auth-page">
        <main class="auth-card" role="main">
            <article class="auth-card__content">
                <header>
                    <hgroup class="auth-card__brand">
                        <p class="status-label">Acceso</p>
                        <h1 class="auth-card__title">Iniciar sesión</h1>
                        <p class="auth-card__description">Ingresa con tu correo y contraseña para acceder al panel de Fantasync.</p>
                    </hgroup>
                </header>

                <form method="POST" action="{{ route('login.submit') }}" novalidate>
                    @csrf

                    <fieldset class="form-group border-0 p-0 m-0">
                        <label class="form-label" for="email">Correo electrónico</label>
                        <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <fieldset class="form-group border-0 p-0 m-0">
                        <label class="form-label" for="password">Contraseña</label>
                        <input id="password" name="password" type="password" class="form-control" required>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    @if(session('error'))
                        <p class="form-error">{{ session('error') }}</p>
                    @endif

                    <footer class="form-actions">
                        <button type="submit" class="button-submit">Entrar al panel</button>
                        <a href="{{ route('google.redirect') }}" class="button-google" rel="nofollow">Continuar con Google</a>
                    </footer>

                    <footer class="form-footnote">
                        <p>No compartas tu contraseña con terceros.</p>
                        <a href="#">¿Olvidaste tu contraseña?</a>
                    </footer>
                </form>
            </article>
        </main>
    </body>
</html>
