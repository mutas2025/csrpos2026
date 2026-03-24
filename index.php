<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Franchising</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="dist/css/font.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="icon" type="image/png" sizes="40x16" href="dist/img/splogo.png">
    <style>
        body {
            font-family: "Asap", sans-serif;
        }

        .login {
            overflow: hidden;
            background: white;
            padding: 40px 30px 30px 30px;
            border-radius: 10px;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 400px;
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            -webkit-transition: -webkit-transform 300ms, box-shadow 300ms;
            -moz-transition: -moz-transform 300ms, box-shadow 300ms;
            transition: transform 300ms, box-shadow 300ms;
            box-shadow: 5px 10px 10px rgba(2, 128, 144, 0.2);
        }

        .login::before,
        .login::after {
            content: "";
            position: absolute;
            width: 600px;
            height: 600px;
            border-top-left-radius: 40%;
            border-top-right-radius: 45%;
            border-bottom-left-radius: 35%;
            border-bottom-right-radius: 40%;
            z-index: -1;
        }

        .login::before {
            left: 15%;
            bottom: -102%;
            background-color: rgba(69, 105, 144, 0.15);
            -webkit-animation: wawes 6s infinite linear;
            -moz-animation: wawes 6s infinite linear;
            animation: wawes 6s infinite linear;
        }

        .login::after {
            left: 22%;
            bottom: -112%;
            background-color: rgba(2, 128, 144, 0.2);
            -webkit-animation: wawes 7s infinite;
            -moz-animation: wawes 7s infinite;
            animation: wawes 7s infinite;
        }

        .login>input {
            font-family: "Asap", sans-serif;
            display: block;
            border-radius: 5px;
            font-size: 16px;
            background: white;
            width: 100%;
            border: 0;
            padding: 10px 10px;
            margin: 15px -10px;
        }

        .login>button {
            font-family: "Asap", sans-serif;
            cursor: pointer;
            color: #fff;
            font-size: 16px;
            text-transform: uppercase;
            width: 100%;
            border: 0;
            padding: 10px 0;
            margin-top: 10px;
            border-radius: 5px;
            background-color: #0056b3;
            -webkit-transition: background-color 300ms;
            -moz-transition: background-color 300ms;
            transition: background-color 300ms;
        }

        .login>button:hover {
            background-color: #004494;
        }

        .login>button:disabled {
            background-color: #a0c4e8;
            cursor: not-allowed;
        }

        @-webkit-keyframes wawes {
            from {
                -webkit-transform: rotate(0);
            }
            to {
                -webkit-transform: rotate(360deg);
            }
        }

        @-moz-keyframes wawes {
            from {
                -moz-transform: rotate(0);
            }
            to {
                -moz-transform: rotate(360deg);
            }
        }

        @keyframes wawes {
            from {
                -webkit-transform: rotate(0);
                -moz-transform: rotate(0);
                -ms-transform: rotate(0);
                -o-transform: rotate(0);
                transform: rotate(0);
            }
            to {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        a {
            text-decoration: none;
            color: rgba(255, 255, 255, 0.6);
            position: absolute;
            right: 10px;
            bottom: 10px;
            font-size: 12px;
        }

        center {
            font-size: 25px;
            z-index: 99 !important;
            display: block;
            color: black;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="hold-transition login-page bg-secondary">

    <div class="login-box bg-secondary card">
        <form id="loginForm" class="login">
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="dist/img/splogo.png" alt="User profile picture">
            </div>
            <center>CSR POS Login</center>
            <input type="text" id="username" name="username" placeholder="Username or Email" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit" id="submit_login">
                <span class="spinner" style="display: none;"></span>
                <span class="btn-text">Login</span>
            </button>
            <div style="text-align: center; margin-top: 15px;">
                <a href="register.php" style="color: #0056b3; position: static;">Don't have an account? Register here</a>
            </div>
            <img class="profile-user-img img-fluid border-0" style="mix-blend-mode: multiply; width: 67px; position: absolute; right: 39px; bottom: 21px;" src="dist/img/itcsologo.png" alt="User profile picture">
        </form>
    </div>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <script src="plugins/fontawesomekit/a757e6f388.js"></script>
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

        $('#loginForm').submit(function(e) {
            e.preventDefault();
            
            const username = $('#username').val().trim();
            const password = $('#password').val();
            
            // Validate inputs
            if (!username || !password) {
                Toast.fire({
                    icon: 'error',
                    title: 'Please enter username/email and password!'
                });
                return;
            }
            
            // Show loading state
            const submitBtn = $('#submit_login');
            const spinner = submitBtn.find('.spinner');
            const btnText = submitBtn.find('.btn-text');
            
            submitBtn.prop('disabled', true);
            spinner.show();
            btnText.text('Logging in...');
            
            $.ajax({
                url: 'api/login.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ username, password }),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: response.message || 'Login successful!',
                            timer: 2000
                        });
                        setTimeout(function() {
                            window.location.href = 'pages/home/index.php';
                        }, 2000);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message || 'Login failed!'
                        });
                        // Reset button
                        submitBtn.prop('disabled', false);
                        spinner.hide();
                        btnText.text('Login');
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Server error!';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        errorMessage = response.message || errorMessage;
                    } catch(e) {}
                    
                    Toast.fire({
                        icon: 'error',
                        title: errorMessage
                    });
                    // Reset button
                    submitBtn.prop('disabled', false);
                    spinner.hide();
                    btnText.text('Login');
                }
            });
        });
        
        // Allow Enter key to submit
        $('#username, #password').keypress(function(e) {
            if (e.which === 13) {
                $('#loginForm').submit();
            }
        });
    </script>
</body>

</html>