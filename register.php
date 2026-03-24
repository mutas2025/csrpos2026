<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRPOS - Member Registration</title>
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="icon" type="image/png" sizes="40x16" href="dist/img/splogo.png">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .register-container {
            max-width: 600px;
            margin: 50px auto;
        }
        
        .card {
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: #0056b3;
            color: white;
            text-align: center;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px;
        }
        
        .card-header h3 {
            margin: 0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .required:after {
            content: " *";
            color: red;
        }
        
        .btn-register {
            background-color: #0056b3;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
        }
        
        .btn-register:hover {
            background-color: #004494;
        }
        
        .btn-register:disabled {
            background-color: #a0c4e8;
            cursor: not-allowed;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: #0056b3;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .info-box {
            background-color: #e8f4fd;
            border-left: 4px solid #0056b3;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            background: none;
            border: none;
            color: #999;
        }
        
        .toggle-password:hover {
            color: #0056b3;
        }
        
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.2em;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <div class="container register-container">
        <div class="card">
            <div class="card-header">
                <img src="dist/img/splogo.png" alt="Logo" style="height: 60px; margin-bottom: 10px;">
                <h3>CSR POS Registration</h3>
                <p class="mb-0">Create your account</p>
            </div>
            <div class="card-body">
                <div class="info-box">
                    <i class="fas fa-info-circle"></i> <strong>Note:</strong> After registration, your account will be pending approval. You'll be notified once approved by the administrator.
                </div>
                
                <form id="registerForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required">ID Number</label>
                                <input type="text" class="form-control" id="idNumber" name="idNumber" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required">Department/Office</label>
                                <input type="text" class="form-control" id="department" name="department" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required">User Type</label>
                                <select class="form-control" id="userType" name="userType" required>
                                    <option value="">Select User Type</option>
                                    <option value="admin">Administrator</option>
                                    <option value="manager">Manager</option>
                                    <option value="cashier">Cashier</option>
                                    <option value="staff">Staff</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Contact Number</label>
                        <input type="tel" class="form-control" id="contactNumber" name="contactNumber" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required">Password</label>
                                <div class="password-wrapper">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required">Retype Password</label>
                                <div class="password-wrapper">
                                    <input type="password" class="form-control" id="retypePassword" name="retypePassword" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('retypePassword')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="terms" name="terms" required>
                            <label class="custom-control-label" for="terms">I agree to the <a href="#" target="_blank">terms and conditions</a></label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block btn-register" id="registerBtn">
                        <span class="spinner-border spinner-border-sm" style="display: none;"></span>
                        <span class="btn-text">Register</span>
                    </button>
                    
                    <div class="login-link">
                        Already have an account? <a href="index.php">Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2@11.js"></script>
    
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentElement.querySelector('.toggle-password i');
            
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
        
        $('#registerForm').submit(function(e) {
            e.preventDefault();
            
            const password = $('#password').val();
            const retype = $('#retypePassword').val();
            
            // Client-side validation
            if (password !== retype) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Passwords do not match. Please try again.'
                });
                return;
            }
            
            if (password.length < 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Weak Password',
                    text: 'Password must be at least 6 characters long.'
                });
                return;
            }
            
            if (!$('#terms').is(':checked')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terms & Conditions',
                    text: 'You must agree to the terms and conditions.'
                });
                return;
            }
            
            // Prepare form data
            const formData = {
                idNumber: $('#idNumber').val().trim(),
                fullName: $('#fullName').val().trim(),
                department: $('#department').val().trim(),
                userType: $('#userType').val(),
                username: $('#username').val().trim(),
                email: $('#email').val().trim(),
                contactNumber: $('#contactNumber').val().trim(),
                password: password,
                retypePassword: retype,
                terms: $('#terms').is(':checked') ? 1 : 0
            };
            
            // Show loading state
            const registerBtn = $('#registerBtn');
            const spinner = registerBtn.find('.spinner-border');
            const btnText = registerBtn.find('.btn-text');
            
            registerBtn.prop('disabled', true);
            spinner.show();
            btnText.text('Registering...');
            
            // Submit via AJAX
            $.ajax({
                url: 'api/routes.php/register',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(formData),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful!',
                            text: response.message,
                            confirmButtonColor: '#0056b3'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'index.php';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Registration Failed',
                            text: response.message
                        });
                        // Reset button
                        registerBtn.prop('disabled', false);
                        spinner.hide();
                        btnText.text('Register');
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Server error occurred. Please try again.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        errorMessage = response.message || errorMessage;
                    } catch(e) {}
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                    // Reset button
                    registerBtn.prop('disabled', false);
                    spinner.hide();
                    btnText.text('Register');
                }
            });
        });
    </script>
</body>
</html>