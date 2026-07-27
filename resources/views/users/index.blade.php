<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios | FantaSync</title>
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
                <a href="{{ route('dashboard') }}" class="btn-back-nav" style="width: fit-content; margin-bottom: 0; padding: 0.4rem 1rem; font-size: 0.85rem; background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Panel
                </a>
            </section>

            <!-- Centro: Encabezado -->
            <header class="dashboard-header" style="margin: 2rem 0 0 0; flex: 2; display: flex; flex-direction: column; justify-content: center; max-width: none;">
                <hgroup>
                    <p class="eyebrow" style="margin-bottom: 0;">Administración</p>
                    <h1 class="dashboard-title" style="font-size: 2.8rem; margin-top: 0.2rem;">Gestión de Usuarios</h1>
                    <p class="dashboard-description" style="margin: 0.5rem auto 0; font-size: 1.1rem;">Administra las cuentas de acceso y roles del personal en FantaSync.</p>
                </hgroup>
            </header>

            <!-- Lado Derecho: Menú Usuario y Botón Crear -->
            <section style="flex: 1; display: flex; flex-direction: column; align-items: flex-end; gap: 1.5rem; padding-top: 15px;">
                <x-user-menu />
                <a href="{{ route('users.create') }}" class="btn-create-user" style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--accent-yellow); color: var(--primary-purple); padding: 0.75rem 1.5rem; border-radius: 2rem; font-weight: 800; text-decoration: none; box-shadow: 0 4px 15px rgba(255, 213, 79, 0.3); white-space: nowrap;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nuevo Usuario
                </a>
            </section>
        </section>

        @if(session('success'))
            <aside class="alert-success-users">
                {{ session('success') }}
            </aside>
        @endif
        @if(session('error'))
            <aside class="alert-error-users">
                {{ session('error') }}
            </aside>
        @endif

        <section class="users-table-container" style="margin-top: 6rem;">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th>Fecha de Registro</th>
                        <th class="th-actions" style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge {{ $user->isSuperadmin() ? 'role-superadmin' : 'role-user' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="td-actions" style="white-space: nowrap; display: flex; gap: 0.6rem; justify-content: flex-end; align-items: center;">
                                <a href="{{ route('users.edit', $user) }}" class="btn-action btn-edit" style="display: inline-flex; align-items: center; padding: 0.4rem 1rem; border-radius: 2rem; text-decoration: none; font-weight: 800; font-size: 0.85rem; background: rgba(122, 40, 138, 0.1); color: var(--primary-purple);">Editar</a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');" style="margin: 0; display: inline-flex;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" style="display: inline-flex; align-items: center; padding: 0.4rem 1rem; border-radius: 2rem; border: none; font-weight: 800; font-size: 0.85rem; background: rgba(220, 53, 69, 0.1); color: #dc3545; cursor: pointer;">Eliminar</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($users->hasPages())
                <footer class="pagination-footer">
                    {{ $users->links() }}
                </footer>
            @endif
        </section>
    </main>
</body>
</html>
