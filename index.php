<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Franchising</title>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="dist/css/font.css">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="icon" type="image/png" sizes="40x16" href="dist/img/splogo.png">
    
    <style>
        /* =================================================================
           1. BODY & BACKGROUND IMAGE STYLING
           ================================================================= */
        body {
            font-family: "Asap", sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* The Background Image */
        #bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Replace this URL with your local image path, e.g., 'dist/img/bg-city.jpg' */
            background-image: url('bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }

        /* Dark Overlay for readability */
        #bg-overlay::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* 50% Black Opacity */
            z-index: -1;
        }

        /* =================================================================
           2. LOGIN CARD STYLING
           ================================================================= */
        .login {
            overflow: hidden;
            background: rgba(255, 255, 255, 0.95); /* Slight transparency for glass effect */
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
            box-shadow: 0 15px 25px rgba(0,0,0,0.3); /* Enhanced shadow for contrast */
            z-index: 100;
        }

        /* Decorative Background Circles (Animated) */
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

        /* Input Styling */
        .login > input {
            font-family: "Asap", sans-serif;
            display: block;
            border-radius: 5px;
            font-size: 16px;
            background: #f8f9fa; /* Light grey background for inputs */
            width: 100%;
            border: 1px solid #ced4da;
            padding: 12px 15px;
            margin: 15px 0;
            box-sizing: border-box;
            transition: all 0.3s;
        }

        .login > input:focus {
            outline: none;
            border-color: #028090;
            background: white;
            box-shadow: 0 0 0 2px rgba(2, 128, 144, 0.2);
        }

        /* Button Styling */
        .login > button {
            font-family: "Asap", sans-serif;
            cursor: pointer;
            color: #fff;
            font-size: 16px;
            text-transform: uppercase;
            width: 80px;
            border: 0;
            padding: 10px 0;
            margin-top: 10px;
            margin-left: -5px;
            border-radius: 5px;
            background-color: #6c757d; /* Matching AdminLTE grey/dark */
            -webkit-transition: background-color 300ms;
            -moz-transition: background-color 300ms;
            transition: background-color 300ms;
        }

        .login > button:hover {
            background-color: #f24353;
        }

        /* Register Link Styling */
        .register-link {
            display: inline-block;
            margin-left: 15px;
            font-size: 14px;
            color: #fff; /* White text for contrast against dark bg */
            text-decoration: none;
            position: relative;
            top: -2px;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        .register-link:hover {
            color: #ddd;
            text-decoration: underline;
        }

        /* Animations */
        @-webkit-keyframes wawes {
            from { -webkit-transform: rotate(0); }
            to { -webkit-transform: rotate(360deg); }
        }

        @-moz-keyframes wawes {
            from { -moz-transform: rotate(0); }
            to { -moz-transform: rotate(360deg); }
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

        /* Text Elements */
        center {
            font-size: 25px;
            z-index: 99 !important;
            display: block;
            color: #333;
            margin-bottom: 10px;
            font-weight: bold;
        }

        /* Logo styling */
        .img-logo {
            mix-blend-mode: multiply;
            width: 67px;
            position: absolute;
            right: 20px;
            bottom: 20px;
            opacity: 0.8;
        }

        .profile-user-img {
            width: 100px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="hold-transition login-page">

    <!-- NEW: Background Image Overlay -->
    <div id="bg-overlay"></div>

    <!-- Login Box -->
    <div class="login-box bg-secondary card">
        <form method="post" class="login" id="loginForm">
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                     src="dist/img/splogo.png"
                     alt="User profile picture">
            </div>
            <center>Sample Login</center>

            <input type="text" id="username" name="username" placeholder="Username or Email" required autocomplete="off">
            <input type="password" id="password" name="password" placeholder="Password" required>

            <!-- Login Button and Register Text Side by Side -->
            <button type="submit" id="submit_login">Login</button>
            <a href="pages/home/register.php" class="register-link">Register</a>

            <img class="profile-user-img img-fluid border-0 img-logo"
                 src="dist/img/itcsologo.png"
                 alt="ITCSO Logo">
        </form>
    </div>

    <!-- Scripts -->
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
            const originalText = submitBtn.text();
            submitBtn.prop('disabled', true).text('...');

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
                        submitBtn.prop('disabled', false).text(originalText);
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
                    submitBtn.prop('disabled', false).text(originalText);
                }
            });
        });
    </script>
</body>
</html>