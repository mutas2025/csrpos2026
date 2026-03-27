<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Franchising</title>
    <link rel="stylesheet" href="dist/css/font.css">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="icon" type="image/png" sizes="40x16" href="dist/img/splogo.png">
    <style>
        body {
            font-family: "Asap", sans-serif;
            background: #6c757d !important;
        }
        .login-box {
            width: 400px;
            margin: 0 auto;
        }
        .login {
            background: white;
            padding: 40px 30px 30px 30px;
            border-radius: 10px;
            position: relative;
            box-shadow: 5px 10px 10px rgba(2, 128, 144, 0.2);
            overflow: hidden;
        }
        .login::before,
        .login::after {
            content: "";
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 40% 45% 35% 40%;
            z-index: -1;
        }
        .login::before {
            left: 15%;
            bottom: -102%;
            background-color: rgba(69, 105, 144, 0.15);
            animation: wawes 6s infinite linear;
        }
        .login::after {
            left: 22%;
            bottom: -112%;
            background-color: rgba(2, 128, 144, 0.2);
            animation: wawes 7s infinite;
        }
        @keyframes wawes {
            from { transform: rotate(0); }
            to { transform: rotate(360deg); }
        }
        .login input {
            display: block;
            border-radius: 5px;
            font-size: 16px;
            background: white;
            width: 100%;
            border: 1px solid #ced4da;
            padding: 10px 10px;
            margin: 15px 0;
        }
        .login button {
            cursor: pointer;
            color: #fff;
            font-size: 16px;
            text-transform: uppercase;
            width: 100%;
            border: 0;
            padding: 10px 0;
            margin-top: 10px;
            border-radius: 5px;
            transition: background-color 300ms;
        }
        .login-btn {
            background-color: grey;
        }
        .login-btn:hover {
            background-color: #f24353;
        }
        .register-btn {
            background-color: #007bff;
        }
        .register-btn:hover {
            background-color: #0056b3;
        }
        center {
            font-size: 25px;
            display: block;
            color: black;
            margin-bottom: 15px;
        }
        .error-message {
            color: #dc3545;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
            background: #f8d7da;
            padding: 8px;
            border-radius: 5px;
        }
        .img-logo {
            mix-blend-mode: multiply;
            width: 67px;
            position: absolute;
            right: 20px;
            bottom: 20px;
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div class="login-box bg-secondary card">
        <form method="post" class="login" id="loginForm">
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                     src="dist/img/splogo.png"
                     alt="User profile picture">
            </div>
            <center>Sample Login</center>

            <input type="text" id="username" name="username" placeholder="Username or Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <button type="submit" id="submit_login" class="login-btn">Login</button>
            <button type="button" id="go_register" class="register-btn">REGISTER HERE</button>

            <img class="profile-user-img img-fluid border-0 img-logo"
                 src="dist/img/itcsologo.png"
                 alt="ITCSO Logo">
        </form>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2@11.js"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        // Handle login via AJAX
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            const username = $('#username').val();
            const password = $('#password').val();

            // Disable submit button to prevent double submission
            const submitBtn = $('#submit_login');
            submitBtn.prop('disabled', true).text('Logging in...');

            $.ajax({
                url: 'api/login.php', // Pointing to the API endpoint
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({ username, password }),
                success: function(response) {
                    if (response.status === 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: response.message || 'Login successful!'
                        });
                        setTimeout(function() {
                            window.location.href = 'pages/home/home.php';
                        }, 1000);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message || 'Login failed!'
                        });
                        submitBtn.prop('disabled', false).text('Login');
                    }
                },
                error: function(xhr) {
                    let errMsg = 'Server error! Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    Toast.fire({
                        icon: 'error',
                        title: errMsg
                    });
                    submitBtn.prop('disabled', false).text('Login');
                }
            });
        });

        // Register redirect
        $('#go_register').click(function() {
            window.location.href = 'registration.php';
        });
    </script>
</body>
</html>