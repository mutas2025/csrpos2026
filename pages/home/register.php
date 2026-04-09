<?php
// registration.php
// Public-facing User Registration Page (Matching Login Design)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>San Carlos City | Registration</title>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../../dist/css/font.css">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">
    
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
            overflow: auto; /* Changed to auto to allow scrolling if needed on small screens */
            position: relative;
        }

        /* The Background Image */
        #bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Adjust path to point to the correct background location relative to this file */
            background-image: url('../../bg.jpg'); 
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
           2. LOGIN CARD STYLING (Adapted for Registration)
           ================================================================= */
        .login {
            overflow: hidden;
            background: rgba(255, 255, 255, 0.95); /* Slight transparency */
            padding: 40px 30px 30px 30px;
            border-radius: 10px;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 450px; /* Slightly wider to accommodate more inputs */
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            -webkit-transition: -webkit-transform 300ms, box-shadow 300ms;
            -moz-transition: -moz-transform 300ms, box-shadow 300ms;
            transition: transform 300ms, box-shadow 300ms;
            box-shadow: 0 15px 25px rgba(0,0,0,0.3);
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

        /* Input Styling - Matching Login */
        .login > input, 
        .login > select {
            font-family: "Asap", sans-serif;
            display: block;
            border-radius: 5px;
            font-size: 14px; /* Slightly smaller font to fit more fields */
            background: #f8f9fa;
            width: 100%;
            border: 1px solid #ced4da;
            padding: 10px 15px;
            margin: 8px 0; /* Tighter margin */
            box-sizing: border-box;
            transition: all 0.3s;
            appearance: none; /* Reset select appearance */
            -webkit-appearance: none;
        }

        .login > input:focus,
        .login > select:focus {
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
            background-color: #6c757d;
            -webkit-transition: background-color 300ms;
            -moz-transition: background-color 300ms;
            transition: background-color 300ms;
        }

        .login > button:hover {
            background-color: #f24353;
        }

        /* Register/Login Link Styling */
        .auth-link {
            display: inline-block;
            margin-left: 15px;
            font-size: 14px;
            color: #fff;
            text-decoration: none;
            position: relative;
            top: -2px;
            font-weight: 600;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        .auth-link:hover {
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
            margin-top: 10px;
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

        /* Password Strength Meter specific */
        .password-strength {
            height: 4px;
            margin-top: -5px;
            margin-bottom: 5px;
            border-radius: 2px;
            transition: all 0.3s ease;
            background-color: #e9ecef;
        }
        .strength-weak { width: 33%; background-color: #dc3545; }
        .strength-medium { width: 66%; background-color: #ffc107; }
        .strength-strong { width: 100%; background-color: #28a745; }
        
        .small-text {
            font-size: 11px;
            color: #666;
            margin-top: -5px;
            display: block;
            margin-bottom: 5px;
        }
        
        .icheck-primary {
            margin-top: 10px;
        }
    </style>
</head>
<body class="hold-transition login-page">

    <!-- Background Image Overlay -->
    <div id="bg-overlay"></div>

    <!-- Login/Registration Box -->
    <div class="login-box bg-secondary card">
        <form method="post" class="login" id="registerForm">
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                     src="../../dist/img/splogo.png"
                     alt="User profile picture">
            </div>
            <center>Create Account</center>

            <!-- Registration Fields -->
            <input type="text" id="idno" name="idno" placeholder="ID Number" required autocomplete="off">
            <input type="text" id="fullname" name="fullname" placeholder="Full Name" required autocomplete="off">
            <input type="text" id="username" name="username" placeholder="Username" required autocomplete="off">
            <input type="email" id="email" name="email" placeholder="Email Address" required autocomplete="off">
            
            <select name="user_type" id="user_type" required>
                <option value="" disabled selected>Select User Type</option>
                <option value="staff">Staff</option>
                <option value="cashier">Cashier</option>
                <option value="manager">Manager</option>
            </select>

            <input type="text" id="contactno" name="contactno" placeholder="Contact Number" required autocomplete="off">
            <input type="text" id="department" name="department" placeholder="Department" required autocomplete="off">
            
            <input type="password" id="password" name="password" placeholder="Password" required>
            <div class="password-strength" id="strengthBar"></div>
            <span class="small-text">Min 6 chars, 1 number, 1 special char.</span>
            
            <input type="password" id="repassword" name="repassword" placeholder="Retype Password" required>

            <!-- Terms Checkbox -->
            <div class="icheck-primary">
                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                <label for="agreeTerms" style="font-size: 13px; font-weight: normal; color: #555;">
                    I agree to the <a href="#" style="color: #028090;">terms</a>
                </label>
            </div>

            <!-- Submit Button and Login Link -->
            <button type="submit" id="submit_register">Sign Up</button>
            <a href="../../index.php" class="auth-link">Back to Login</a>

            <img class="profile-user-img img-fluid border-0 img-logo"
                 src="../../dist/img/itcsologo.png"
                 alt="ITCSO Logo">
        </form>
    </div>

    <!-- Scripts -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2@11.js"></script>
    <!-- SweetAlert2 via CDN as backup if local fails -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        // API Endpoint
        const API_URL = '../../api/routes.php/register';

        // Password Strength Checker
        $('#password').on('keyup', function() {
            const password = $(this).val();
            const strengthBar = $('#strengthBar');
            
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;

            strengthBar.removeClass('strength-weak strength-medium strength-strong');

            if (password.length === 0) {
                strengthBar.css('width', '0');
            } else if (strength < 2) {
                strengthBar.addClass('strength-weak');
            } else if (strength < 4) {
                strengthBar.addClass('strength-medium');
            } else {
                strengthBar.addClass('strength-strong');
            }
        });

        // Handle Registration via AJAX
        $('#registerForm').on('submit', function(e) {
            e.preventDefault();

            // Checkbox validation
            if(!$('#agreeTerms').is(':checked')) {
                Toast.fire({
                    icon: 'warning',
                    title: 'You must agree to the terms.'
                });
                return;
            }

            // Password Match Validation
            const password = $('#password').val();
            const repassword = $('#repassword').val();

            if (password !== repassword) {
                Toast.fire({
                    icon: 'error',
                    title: 'Passwords do not match!'
                });
                return;
            }

            // Serialize Data
            const formData = $(this).serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            // Add terms agreed flag
            formData.terms_agreed = 1;

            // Disable submit button
            const submitBtn = $('#submit_register');
            const originalText = submitBtn.text();
            submitBtn.prop('disabled', true).text('...');

            $.ajax({
                url: API_URL,
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function(response) {
                    if (response.status === 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: response.message || 'Registration successful!'
                        });
                        setTimeout(function() {
                            // Redirect to login page
                            window.location.href = '../../index.php';
                        }, 1500);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: response.message || 'Registration failed!'
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