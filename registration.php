<?php
// registration.php
// Public-facing User Registration Page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register | System Portal</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- Google Font -->
    <link rel="stylesheet" href="../../dist/css/font.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">

    <style>
        body {
            height: 100vh;
            background-color: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .register-box {
            width: 100%;
            max-width: 600px; /* Wider for form columns */
        }

        .register-logo a {
            color: #495057 !important;
            font-weight: 300;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border-top: 3px solid #17a2b8; /* Info color border */
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .input-group-text {
            background-color: #fff;
            border-right: none;
        }

        .form-control {
            border-left: none;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #17a2b8;
        }

        .input-group .form-control:focus + .input-group-append .input-group-text,
        .input-group .form-control:focus ~ .input-group-prepend .input-group-text {
            border-color: #17a2b8;
        }

        /* Password Strength Meter */
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 2px;
            transition: all 0.3s ease;
            background-color: #e9ecef;
        }

        .strength-weak { width: 33%; background-color: #dc3545; }
        .strength-medium { width: 66%; background-color: #ffc107; }
        .strength-strong { width: 100%; background-color: #28a745; }

        /* Validation feedback icons */
        .form-control.is-valid { background-image: none; }
        .form-control.is-invalid { background-image: none; }
        
        /* Custom footer link */
        .register-footer {
            margin-top: 1rem;
            text-align: center;
        }
        
        .register-footer a {
            color: #17a2b8;
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="hold-transition register-page">

    <div class="register-box">
        <div class="register-logo">
            <a href="#"><b>System</b>Portal</a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Register a new membership</p>

                <form id="registerForm">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="idno" id="idno" placeholder="ID Number" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-id-card"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Full Name" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-users"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="contactno" id="contactno" placeholder="Contact No." required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-phone"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="department" id="department" placeholder="Department" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-building"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <select name="user_type" class="form-control" required>
                                    <option value="" disabled selected>Select User Type</option>
                                    <option value="staff">Staff</option>
                                    <option value="cashier">Cashier</option>
                                    <option value="manager">Manager</option>
                                </select>
                            </div>
                            
                            <!-- Password Section -->
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                    <div class="input-group-append" style="cursor: pointer;" onclick="togglePassword('password', this)">
                                        <div class="input-group-text">
                                            <span class="fas fa-eye"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="password-strength" id="strengthBar"></div>
                                <small class="text-muted" style="font-size: 0.75rem;">Min 6 chars, 1 number, 1 special char.</small>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" class="form-control" name="repassword" id="repassword" placeholder="Retype password" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
                                <label for="agreeTerms">
                                    I agree to the <a href="#">terms</a>
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat" id="btnRegister">
                                Register
                            </button>
                        </div>
                    </div>
                </form>

                <div class="social-auth-links text-center">
                    <p>- OR -</p>
                    <a href="../pages/login/login.php" class="btn btn-block btn-outline-secondary btn-flat">
                        I already have a membership
                    </a>
                </div>
            </div>
        </div>
    </div>

<!-- Scripts -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

<script>
    $(document).ready(function() {
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

        // Toggle Password Visibility
        window.togglePassword = function(inputId, iconBtn) {
            const input = document.getElementById(inputId);
            const icon = iconBtn.querySelector('span');
            
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Form Submission
        $('#registerForm').on('submit', function(e) {
            e.preventDefault();

            // Manual Match Check
            if ($('#password').val() !== $('#repassword').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Mismatch',
                    text: 'Passwords do not match!',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }

            // Serialize Form Data
            const formData = $(this).serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            // Add terms agreed flag
            formData.terms_agreed = 1;

            // Loading State
            const btn = $('#btnRegister');
            const originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

            $.ajax({
                url: API_URL,
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful!',
                            text: response.message,
                            confirmButtonColor: '#28a745',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect to login
                                window.location.href = '../pages/login/login.php';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: response.message,
                            confirmButtonColor: '#dc3545'
                        });
                        btn.prop('disabled', false).html(originalText);
                    }
                },
                error: function(xhr, status, error) {
                    let errMsg = "An unexpected error occurred.";
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: errMsg,
                        confirmButtonColor: '#dc3545'
                    });
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });
    });
</script>
</body>
</html>