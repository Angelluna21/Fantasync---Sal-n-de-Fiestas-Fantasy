import os
import glob
import re

html_replacement = """            <!-- Menú de Usuario -->
            <div class="user-menu-container">
                <button class="user-menu-btn" aria-expanded="false" onclick="toggleUserMenu()">
                    <span class="user-avatar">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </span>
                    <span class="user-name">{{ auth()->user()->name ?? 'Usuario' }}</span>
                    <svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <menu class="user-dropdown" id="userDropdown" style="margin: 0; padding: 0;">
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
            </div>"""

script_injection = """    <script>
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            const btn = document.querySelector('.user-menu-btn');
            const isExpanded = btn.getAttribute('aria-expanded') === 'true';
            
            btn.setAttribute('aria-expanded', !isExpanded);
            dropdown.classList.toggle('show');
        }

        // Cerrar al hacer clic fuera
        document.addEventListener('click', function(event) {
            const container = document.querySelector('.user-menu-container');
            if (container && !container.contains(event.target)) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown) dropdown.classList.remove('show');
                const btn = document.querySelector('.user-menu-btn');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        });
    </script>
</body>"""

css_append = """
/* --- MENÚ DE USUARIO --- */
.user-menu-container {
    position: relative;
    display: inline-block;
}

.user-menu-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.3);
    padding: 0.35rem 1rem 0.35rem 0.35rem;
    border-radius: 3rem;
    cursor: pointer;
    backdrop-filter: blur(8px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    color: white;
}

.user-menu-btn:hover, .user-menu-btn[aria-expanded="true"] {
    background: rgba(255, 255, 255, 0.25);
    border-color: var(--accent-yellow);
    box-shadow: 0 4px 15px rgba(255, 213, 79, 0.3);
}

.user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--accent-yellow);
    color: var(--primary-purple);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 1.2rem;
    box-shadow: inset 0 2px 4px rgba(255,255,255,0.5);
}

.user-name {
    font-family: 'Nunito', sans-serif;
    font-weight: 800;
    font-size: 1rem;
}

.dropdown-icon {
    width: 18px;
    height: 18px;
    transition: transform 0.3s ease;
}

.user-menu-btn[aria-expanded="true"] .dropdown-icon {
    transform: rotate(180deg);
}

.user-dropdown {
    position: absolute;
    top: calc(100% + 0.8rem);
    right: 0;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(122, 40, 138, 0.2);
    min-width: 220px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    border: 1px solid var(--border-color);
    list-style: none;
    z-index: 9999;
}

.user-dropdown.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    width: 100%;
    padding: 1rem 1.2rem;
    border: none;
    background: transparent;
    text-align: left;
    font-family: 'Nunito', sans-serif;
    font-weight: 800;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.dropdown-item.text-danger {
    color: var(--accent-magenta);
}

.dropdown-item.text-danger:hover {
    background: rgba(216, 27, 96, 0.08);
    padding-left: 1.5rem;
}

.dropdown-item svg {
    width: 20px;
    height: 20px;
}
"""

def update_views():
    files = glob.glob('resources/views/**/*.blade.php', recursive=True)
    count = 0
    for f in files:
        if 'dashboard.blade.php' in f or 'auth' in f:
            continue
            
        with open(f, 'r', encoding='utf-8') as file:
            content = file.read()
            
        # Check if already has user-menu
        if 'user-menu-container' in content:
            continue
            
        # Match the old form
        pattern_form = re.compile(r'<!-- Botón Cerrar Sesión -->\s*<form method="POST" action="\{\{ route\(\'logout\'\) \}\}">.*?</form>|            <form method="POST" action="\{\{ route\(\'logout\'\) \}\}">\s*@csrf\s*<button type="submit" class="logout-btn">.*?</button>\s*</form>', re.DOTALL)
        
        if pattern_form.search(content):
            content = pattern_form.sub(html_replacement, content)
            
            # Match </body> to inject script
            if '<script>\n        function toggleUserMenu()' not in content:
                content = content.replace('</body>', script_injection)
                
            with open(f, 'w', encoding='utf-8') as file:
                file.write(content)
            count += 1
            print(f"Updated view: {f}")

def update_css():
    css_files = ['salones.css', 'sucursales.css', 'platillos.css', 'ingredientes.css', 'eventos.css']
    for css_f in css_files:
        path = os.path.join('resources/css', css_f)
        if os.path.exists(path):
            with open(path, 'r', encoding='utf-8') as file:
                content = file.read()
            
            if 'user-menu-container' not in content:
                # Remove old logout-btn CSS if it exists
                old_css_pattern = re.compile(r'\.logout-btn \{.*?\.logout-btn svg \{.*?\}', re.DOTALL)
                content = old_css_pattern.sub('', content)
                
                content += css_append
                with open(path, 'w', encoding='utf-8') as file:
                    file.write(content)
                print(f"Updated CSS: {path}")

if __name__ == '__main__':
    update_views()
    update_css()
