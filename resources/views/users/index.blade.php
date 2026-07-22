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

    <header class="dashboard-header header-users-module">
        <header class="flex-between-center">
            <a href="{{ route('dashboard') }}" class="btn-back-nav btn-back-users">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
            <x-user-menu />
        </header>
        
        <search class="search-users-bar">
            <hgroup>
                <p class="eyebrow">Administración</p>
                <h1 class="dashboard-title title-large">Gestión de Usuarios</h1>
            </hgroup>
            <a href="{{ route('users.create') }}" class="btn-create-user">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Usuario
            </a>
        </search>
    </header>

    <main class="dashboard-layout main-users-layout-index">
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

        <section class="users-table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th>Fecha de Registro</th>
                        <th class="th-actions">Acciones</th>
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
                            <td class="td-actions">
                                <a href="{{ route('users.edit', $user) }}" class="btn-action btn-edit">Editar</a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');" class="form-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">Eliminar</button>
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
