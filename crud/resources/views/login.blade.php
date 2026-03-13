<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h2 {
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .login-header i {
            font-size: 50px;
            color: #667eea;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 38px;
            color: #999;
        }

        .form-control {
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }

        .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            border: none;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .alert-danger {
            background-color: #fff2f0;
            color: #ff4d4f;
            border-left: 4px solid #ff4d4f;
        }

        .alert-success {
            background-color: #f6ffed;
            color: #52c41a;
            border-left: 4px solid #52c41a;
        }

        .footer-links {
            text-align: center;
            margin-top: 20px;
        }

        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #764ba2;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 38px;
            cursor: pointer;
            color: #999;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            margin-top: 15px;
        }

        .loading-spinner.active {
            display: block;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }

        .remember-me input {
            margin-right: 8px;
            cursor: pointer;
        }

        .remember-me label {
            color: #666;
            font-size: 14px;
            cursor: pointer;
        }

        .credentials-hint {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 10px;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            border: 1px dashed #ddd;
        }

        .credentials-hint i {
            color: #667eea;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-hospital-user"></i>
            <h2>Bienvenido</h2>
            <p>Sistema de Gestión de Pacientes</p>
        </div>

        <div id="alert-container"></div>

        <form id="loginForm" onsubmit="event.preventDefault(); login();">
            <div class="form-group">
                <label class="form-label" for="username">
                    <i class="fas fa-user"></i> Usuario
                </label>
                <input type="text" 
                       class="form-control" 
                       id="username" 
                       placeholder="Ingrese su usuario"
                       autocomplete="off"
                       required>
                <i class="fas fa-user input-icon"></i>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">
                    <i class="fas fa-lock"></i> Contraseña
                </label>
                <input type="password" 
                       class="form-control" 
                       id="password" 
                       placeholder="Ingrese su contraseña"
                       required>
                <i class="fas fa-lock input-icon"></i>
                <i class="fas fa-eye password-toggle" onclick="togglePassword()" id="togglePassword"></i>
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember">
                <label for="remember">Recordarme</label>
            </div>

            <button type="submit" class="btn-login" id="btnLogin">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>

            <div class="loading-spinner" id="loadingSpinner">
                <div class="spinner"></div>
                <p style="margin-top: 10px; color: #666;">Verificando credenciales...</p>
            </div>
        </form>

        <div class="credentials-hint">
            <i class="fas fa-info-circle"></i>
            <strong>Credenciales de prueba:</strong><br>
            Usuario: admin<br>
            Contraseña: 1234567890
        </div>

        <div class="footer-links">
            <a href="#"><i class="fas fa-question-circle"></i> ¿Olvidó su contraseña?</a>
        </div>
    </div>

    <script>
        const API_URL = 'http://localhost:8000/api';

        function togglePassword() {
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');
            
            if (password.type === 'password') {
                password.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function showAlert(message, type = 'danger') {
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `
                <div class="alert alert-${type}">
                    <i class="fas ${type === 'danger' ? 'fa-exclamation-circle' : 'fa-check-circle'}"></i>
                    ${message}
                </div>
            `;
            
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        function setLoading(loading) {
            const btn = document.getElementById('btnLogin');
            const spinner = document.getElementById('loadingSpinner');
            
            if (loading) {
                btn.disabled = true;
                btn.style.opacity = '0.7';
                spinner.classList.add('active');
            } else {
                btn.disabled = false;
                btn.style.opacity = '1';
                spinner.classList.remove('active');
            }
        }

        async function login() {
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;

            if (!username || !password) {
                showAlert('Por favor ingrese usuario y contraseña');
                return;
            }

            setLoading(true);

            try {
                const response = await fetch(`${API_URL}/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();

                if (data.success) {
                    // Guardar token
                    localStorage.setItem('token', data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    if (remember) {
                        localStorage.setItem('remember', 'true');
                    }

                    showAlert('¡Login exitoso! Redirigiendo...', 'success');

                    // Redirigir al dashboard
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 1500);
                } else {
                    showAlert(data.message || 'Credenciales incorrectas');
                }
            } catch (error) {
                showAlert('Error de conexión con el servidor');
                console.error('Error:', error);
            } finally {
                setLoading(false);
            }
        }

        // Verificar si ya hay sesión activa
        document.addEventListener('DOMContentLoaded', function() {
            const token = localStorage.getItem('token');
            if (token) {
                // Verificar si el token sigue siendo válido
                fetch(`${API_URL}/me`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '/';
                    } else {
                        localStorage.removeItem('token');
                    }
                })
                .catch(() => {
                    localStorage.removeItem('token');
                });
            }

            // Auto-completar credenciales de prueba (opcional)
            document.getElementById('username').value = 'admin';
            document.getElementById('password').value = '1234567890';
        });

        // Animación de entrada para los inputs
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#667eea';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('.input-icon').style.color = '#999';
            });
        });
    </script>
</body>
</html>