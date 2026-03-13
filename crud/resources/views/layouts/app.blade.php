<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <script>
        (function() {
            const token = localStorage.getItem('token');
            const currentPath = window.location.pathname;
            
            // Si no hay token y no estamos en login, redirigir
            if (!token && currentPath !== '/login') {
                window.location.href = '/login';
            }
            
            // Si hay token y estamos en login, redirigir al home
            if (token && currentPath === '/login') {
                window.location.href = '/';
            }
        })();
    </script>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand">Sistema de Gestión de Pacientes</span>
            <div class="d-flex text-white" id="user-info" style="display: none;">
                <span class="me-3" id="user-name">
                    <i class="fas fa-user-circle"></i> <span id="user-display-name"></span>
                </span>
                <button class="btn btn-sm btn-light" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </button>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        @yield('content')
    </div>

    
    <script>
    // Mostrar información del usuario
    document.addEventListener('DOMContentLoaded', function() {
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        const token = localStorage.getItem('token');
        
        if (token && user.name) {
            document.getElementById('user-info').style.display = 'flex';
            document.getElementById('user-display-name').textContent = user.name;
        }
    });

    function logout() {
        // Llamar al API de logout
        fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        }).finally(() => {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        });
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>