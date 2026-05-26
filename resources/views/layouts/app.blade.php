<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FantaSync - Sistema de Gestión</title>
    
    @livewireStyles
    
    <style>
        body { 
            font-family: system-ui, -apple-system, sans-serif; 
            background-color: #f4f6f9; 
            color: #333;
            margin: 0; 
            padding: 2rem; 
        }
        .container { 
            max-width: 1000px; 
            margin: 0 auto; 
        }
        /* Estilos básicos para la tarjeta que ya tienes */
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card-header { border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .btn { padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; }
        .primary { background: #667eea; color: white; }
    </style>
</head>
<body>
    
    <main class="container">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>