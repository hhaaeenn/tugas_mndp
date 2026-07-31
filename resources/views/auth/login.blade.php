<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IMS Premium</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Premium Login Styling -->
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --font-main: 'Outfit', sans-serif;
        }

        body {
            font-family: var(--font-main);
            background: radial-gradient(circle at 10% 20%, rgb(4, 4, 13) 0%, rgb(18, 18, 38) 90.2%);
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Glowing background blobs */
        .blob {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(0,0,0,0) 70%);
            filter: blur(40px);
            z-index: 1;
        }
        .blob-1 { top: -50px; left: -50px; }
        .blob-2 { bottom: -50px; right: -50px; }

        .login-container {
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 40px 32px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #a5b4fc, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #94a3b8;
            font-size: 0.875rem;
            text-align: center;
            margin-bottom: 32px;
        }

        .form-label {
            color: #cbd5e1;
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 8px;
        }

        .input-group-text {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #94a3b8;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            padding: 12px;
            font-size: 0.95rem;
            transition: all 0.25s ease;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.06);
            border-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            border: none;
            color: white;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
            transition: all 0.25s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6);
            background: linear-gradient(135deg, #4f46e5, var(--primary-hover));
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        .info-box {
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 14px;
            margin-top: 24px;
            font-size: 0.8rem;
            color: #94a3b8;
        }

        .info-box ul {
            padding-left: 16px;
            margin-bottom: 0;
        }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="login-container">
        <div class="login-card">
            <h3 class="login-title"><i class="bi bi-shield-fill-check"></i> IMS PREMIUM</h3>
            <p class="login-subtitle">Sign in to manage sales, purchases, & inventories</p>

            <form id="loginForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="name@example.com" value="superadmin@example.com">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Enter password" value="password">
                    </div>
                </div>

                <button type="submit" class="btn btn-login" id="loginBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="loginSpinner"></span>
                    Sign In
                </button>
            </form>

            <div class="info-box">
                <div class="fw-bold text-white mb-1"><i class="bi bi-info-circle me-1"></i> Demo Credentials:</div>
                <ul class="mb-0">
                    <li><strong>SuperAdmin:</strong> superadmin@example.com (pw: password)</li>
                    <li><strong>Sales:</strong> sales@example.com (pw: password)</li>
                    <li><strong>Purchase:</strong> purchase@example.com (pw: password)</li>
                    <li><strong>Manager:</strong> manager@example.com (pw: password)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                // Show spinner, disable button
                $('#loginSpinner').removeClass('d-none');
                $('#loginBtn').prop('disabled', true);

                $.ajax({
                    url: '/login',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        email: $('#email').val(),
                        password: $('#password').val()
                    },
                    success: function(response) {
                        if (response.access_token) {
                            localStorage.setItem('api_token', response.access_token);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false,
                            background: '#0f172a',
                            color: '#ffffff'
                        }).then(function() {
                            window.location.href = response.redirect;
                        });
                    },
                    error: function(xhr) {
                        $('#loginSpinner').addClass('d-none');
                        $('#loginBtn').prop('disabled', false);

                        var message = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Login Failed',
                            text: message,
                            background: '#0f172a',
                            color: '#ffffff',
                            confirmButtonColor: '#6366f1'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
