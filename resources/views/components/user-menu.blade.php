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
            <a href="{{ route('logs.logins') }}" class="dropdown-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Bitácora de Accesos
            </a>
        </li>
        <li>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
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
