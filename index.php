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
            transform: translate(-50%, -50%);
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
            animation: wawes 6s infinite linear;
        }

        .login::after {
            left: 22%;
            bottom: -112%;
            background-color: rgba(2, 128, 144, 0.2);
            animation: wawes 7s infinite;
        }

        .login>input {
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
            cursor: pointer;
            color: #fff;
            font-size: 16px;
            text-transform: uppercase;
            width: 100%;
            border: 0;
            padding: 10px 0;
            margin-top: 10px;
            border-radius: 5px;
            background-color: grey;
            transition: background-color 300ms;
        }

        .login>button:hover {
            background-color: #f24353;
        }

        /* REGISTER BUTTON STYLE */
        .register-btn {
            background-color: #007bff;
        }

        .register-btn:hover {
            background-color: #0056b3;
        }

        @keyframes wawes {
            from { transform: rotate(0); }
            to { transform: rotate(360deg); }
        }

        center {
            font-size: 25px;
            display: block;
            color: black;
        }
    </style>
</head>

<body class="hold-transition login-page bg-secondary">

    <div class="login-box bg-secondary card">
        <form action="server-side/login/login.php" method="post" class="login">
           
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                     src="dist/img/splogo.png"
                     alt="User profile picture">
            </div>

            <center>Sample Login</center>

            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <!-- LOGIN BUTTON -->
            <button type="button" id="submit_login">Login</button>

            <!-- REGISTER HERE BUTTON -->
            <button type="button" id="go_register" class="register-btn">
                REGISTER HERE
            </button>

            <img class="profile-user-img img-fluid border-0"
                 style="mix-blend-mode: multiply; width: 67px; position: absolute; right: 39px; bottom: 21px;"
                 src="dist/img/itcsologo.png"
                 alt="User profile picture">
        </form>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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

        // LOGIN FUNCTION
        $('#submit_login').click(function(e) {
            e.preventDefault();
            const username = $('#username').val();
            const password = $('#password').val();

            $.ajax({
                url: 'api/login.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ username, password }),
                success: function(response) {
                    if (response.status === 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Login successful!'
                        });
                        setTimeout(function() {
                            window.location.href = 'pages/home/home.php';
                        }, 1000);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message || 'Login failed!'
                        });
                    }
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Server error!'
                    });
                }
            });
        });

        // REGISTER REDIRECT
        $('#go_register').click(function() {
            window.location.href = 'registration.php';
        });
    </script>

</body>
</html>
