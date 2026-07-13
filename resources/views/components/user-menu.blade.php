<nav class="user-menu-container" aria-label="Menú de usuario">
    <button class="user-menu-btn" aria-expanded="false" onclick="toggleUserMenu()">
        <span class="user-avatar">
            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
        </span>
        <span class="user-name">{{ auth()->user()->name ?? 'Usuario' }}</span>
        <svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    
    <menu class="user-dropdown" id="userDropdown">
        <li>
            <a href="{{ route('eventos.index') }}" class="dropdown-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Agenda de Eventos
            </a>
        </li>
        <li>
            <a href="{{ route('contratos.crear') }}" class="dropdown-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Gestión de Contratos
            </a>
        </li>
        <li>
            <a href="{{ route('nominas.index') }}" class="dropdown-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Nómina
            </a>
        </li>
        <li>
            <a href="{{ route('logs.logins') }}" class="dropdown-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Bitácora de Accesos
            </a>
        </li>
        @if(auth()->check() && auth()->user()->isSuperadmin())
        <li>
            <a href="{{ route('users.index') }}" class="dropdown-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Gestión de Usuarios
            </a>
        </li>
        @endif
        <li>
            <form method="POST" action="{{ route('logout') }}" class="form-no-margin">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Cerrar sesión
                </button>
            </form>
        </li>
    </menu>
</nav>

<!-- Script del menú de usuario -->
<script>
    if (typeof toggleUserMenu !== 'function') {
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            const btn = document.querySelector('.user-menu-btn');
            if(btn && dropdown) {
                const isExpanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', !isExpanded);
                dropdown.classList.toggle('show');
            }
        }

        document.addEventListener('click', function(event) {
            const container = document.querySelector('.user-menu-container');
            if (container && !container.contains(event.target)) {
                const dropdown = document.getElementById('userDropdown');
                const btn = document.querySelector('.user-menu-btn');
                if (dropdown) dropdown.classList.remove('show');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        });
    }
</script>
