<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios | FantaSync</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
    <style>
        .users-table-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(122, 40, 138, 0.15);
            overflow: hidden;
            border: 1px solid var(--border-color);
            margin-top: 2rem;
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
        }
        .users-table th {
            background: #f8fafc;
            color: var(--text-muted);
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.85rem;
            text-transform: uppercase;
            font-weight: 800;
        }
        .users-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 800;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
        }
        .btn-edit {
            background: rgba(122, 40, 138, 0.1);
            color: var(--primary-purple);
        }
        .btn-edit:hover {
            background: var(--primary-purple);
            color: white;
        }
        .btn-delete {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        .btn-delete:hover {
            background: #dc3545;
            color: white;
        }
        .btn-create-user {
            background: var(--accent-yellow);
            color: var(--primary-purple);
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: var(--shadow-yellow);
            transition: all 0.3s;
        }
        .btn-create-user:hover {
            background: var(--accent-gold);
            transform: translateY(-2px);
        }
        .role-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-weight: 800;
            font-size: 0.8rem;
            text-transform: uppercase;
        }
        .role-superadmin {
            background: var(--primary-purple);
            color: white;
        }
        .role-user {
            background: #e2e8f0;
            color: #475569;
        }
    </style>
</head>
<body class="dashboard-page">
    <figure class="dashboard-background" aria-hidden="true"></figure>

    <header class="dashboard-header" style="max-width: 1100px; margin: 0 auto; text-align: left; padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('dashboard') }}" class="btn-back-nav" style="margin: 0;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
            <x-user-menu />
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 2rem;">
            <hgroup>
                <p class="eyebrow">Administración</p>
                <h1 class="dashboard-title" style="font-size: 2.5rem; text-shadow: none;">Gestión de Usuarios</h1>
            </hgroup>
            <a href="{{ route('users.create') }}" class="btn-create-user">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Usuario
            </a>
        </div>
    </header>

    <main class="dashboard-layout" style="padding-top: 0; max-width: 1100px; margin: 0 auto;">
        @if(session('success'))
            <div style="background: rgba(76, 175, 80, 0.15); color: #2e7d32; padding: 1rem; border-radius: 1rem; border: 1px solid rgba(76, 175, 80, 0.3); font-weight: 800;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: rgba(220, 53, 69, 0.15); color: #dc3545; padding: 1rem; border-radius: 1rem; border: 1px solid rgba(220, 53, 69, 0.3); font-weight: 800;">
                {{ session('error') }}
            </div>
        @endif

        <div class="users-table-container">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th>Fecha de Registro</th>
                        <th style="text-align: right;">Acciones</th>
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
                            <td style="text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="{{ route('users.edit', $user) }}" class="btn-action btn-edit">Editar</a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');" style="margin: 0;">
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
                <div style="padding: 1rem; border-top: 1px solid var(--border-color); display: flex; justify-content: center;">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </main>
</body>
</html>
