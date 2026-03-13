<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        #form-errors {
            border-left: 4px solid #dc3545;
            background-color: #fff8f8;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        #form-errors ul {
            margin: 0;
            padding-left: 20px;
        }
        
        #form-errors li {
            color: #dc3545;
            margin-bottom: 5px;
        }
    </style>
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