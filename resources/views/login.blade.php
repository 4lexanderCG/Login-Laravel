<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laravel Auth</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="wrapper">
        <div class="form-wrapper sign-in active">
            <form id="loginForm">
                @csrf
                <h2>Sign In</h2>
                <div class="input-group">
                    <input type="email" name="email" id="email" required>
                    <label>Email</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="password" required>
                    <label>Password</label>
                </div>
                
                <button type="submit" id="btnLogin">Sign In</button>

                <div class="social-platform">
                    <p>Or sign in with</p>
                    <div class="social-icons">
                        <a href="javascript:void(0)" onclick="loginGoogle()"><i class="fa-brands fa-google"></i></a>
                    </div>
                </div>

                <div class="signUp-link">
                    <p><a href="{{ route('recuperar') }}" class="forgotPassword-link">Forgot Password?</a></p>
                    <p>Don't have an account? <a href="{{ route('register') }}">Sign Up</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btnLogin');
            btn.disabled = true;
            btn.innerText = 'Cargando...';

            const formData = {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            };

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    localStorage.setItem('token', data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    Swal.fire({
                        icon: 'success',
                        title: '¡Bienvenido!',
                        text: 'Inicio de sesión exitoso',
                        background: '#1b1b1b',
                        color: '#fff',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/home';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.error || 'Credenciales incorrectas',
                        background: '#1b1b1b',
                        color: '#fff'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor',
                    background: '#1b1b1b',
                    color: '#fff'
                });
            } finally {
                btn.disabled = false;
                btn.innerText = 'Sign In';
            }
        });

        async function loginGoogle() {
            try {
                const response = await fetch('/api/auth/google');
                const data = await response.json();
                if (data.url) {
                    window.location.href = data.url;
                }
            } catch (error) {
                console.error('Error al conectar con Google:', error);
            }
        }
    </script>
</body>
</html>