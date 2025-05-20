<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <style>
    :root {
      --primary: #4361ee;
      --primary-dark: #3a56d4;
      --secondary: #7209b7;
      --accent: #f72585;
      --success: #38b000;
      --warning: #f9c74f;
      --error: #d90429;
      --light: #f8f9fa;
      --dark: #212529;
      --neutral-100: #f8f9fa;
      --neutral-200: #e9ecef;
      --neutral-300: #dee2e6;
      --neutral-400: #ced4da;
      --neutral-500: #adb5bd;
      --neutral-600: #6c757d;
      --neutral-700: #495057;
      --neutral-800: #343a40;
      --neutral-900: #212529;
    }

    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-container {
      max-width: 430px;
      width: 100%;
      margin: 0 auto;
    }

    .login-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: all 0.3s ease;
      position: relative;
    }

    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .login-header {
      padding: 30px 30px 0;
      text-align: center;
    }

    .login-header .logo {
      font-size: 32px;
      color: var(--primary);
      margin-bottom: 10px;
    }

    .login-body {
      padding: 20px 30px 30px;
    }

    .login-title {
      font-weight: 600;
      color: var(--neutral-800);
      margin-bottom: 0.5rem;
    }

    .login-subtitle {
      color: var(--neutral-600);
      font-size: 0.95rem;
      margin-bottom: 1.5rem;
    }

    .form-label {
      font-weight: 500;
      color: var(--neutral-700);
    }

    .form-control {
      padding: 0.75rem 1rem;
      border-radius: 8px;
      border: 1.5px solid var(--neutral-300);
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
    }

    .password-field {
      position: relative;
    }

    .password-toggle {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--neutral-600);
      transition: all 0.2s ease;
      z-index: 10;
    }

    .password-toggle:hover {
      color: var(--primary);
    }

    .forgot-link {
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s ease;
      font-size: 0.9rem;
    }

    .forgot-link:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .btn-primary {
      background-color: var(--primary);
      border-color: var(--primary);
      border-radius: 8px;
      padding: 0.75rem 1rem;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background-color: var(--primary-dark);
      border-color: var(--primary-dark);
      transform: translateY(-2px);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .separator {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 1.5rem 0;
    }

    .separator::before,
    .separator::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid var(--neutral-300);
    }

    .separator span {
      padding: 0 1rem;
      color: var(--neutral-600);
      font-size: 0.9rem;
    }

    .social-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.2s ease;
      margin-bottom: 10px;
      color: var(--neutral-800);
      background-color: var(--neutral-100);
      border: 1.5px solid var(--neutral-300);
    }

    .social-btn:hover {
      background-color: var(--neutral-200);
      transform: translateY(-2px);
    }

    .social-btn i {
      margin-right: 8px;
      font-size: 18px;
    }

    .social-btn.google i {
      color: #ea4335;
    }

    .social-btn.facebook i {
      color: #1877f2;
    }

    .register-link {
      text-align: center;
      font-size: 0.95rem;
      color: var(--neutral-700);
    }

    .register-link a {
      color: var(--primary);
      font-weight: 500;
      text-decoration: none;
    }

    .register-link a:hover {
      text-decoration: underline;
    }

    .form-check-input:checked {
      background-color: var(--primary);
      border-color: var(--primary);
    }

    .invalid-feedback {
      color: var(--error);
      font-size: 0.85rem;
      margin-top: 0.25rem;
    }

    .is-invalid {
      border-color: var(--error) !important;
    }

    /* Animation for form elements */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .form-group {
      animation: fadeInUp 0.4s ease forwards;
      opacity: 0;
    }

    .form-group:nth-child(1) {
      animation-delay: 0.1s;
    }

    .form-group:nth-child(2) {
      animation-delay: 0.2s;
    }

    .form-group:nth-child(3) {
      animation-delay: 0.3s;
    }

    .login-button {
      animation: fadeInUp 0.4s ease forwards;
      animation-delay: 0.4s;
      opacity: 0;
    }

    /* Modal styles */
    .modal-content {
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
      border-bottom: none;
      padding: 1.5rem 1.5rem 0.5rem;
    }

    .modal-title {
      font-weight: 600;
      color: var(--neutral-800);
    }

    .modal-body {
      padding: 1rem 1.5rem;
    }

    .modal-footer {
      border-top: none;
      padding: 0.5rem 1.5rem 1.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
      .login-container {
        padding: 0 15px;
      }
      
      .login-card {
        border-radius: 12px;
      }
      
      .login-header,
      .login-body {
        padding-left: 20px;
        padding-right: 20px;
      }
    }
  </style>
</head>
<body>

<div class="login-container">
  <div class="login-card">
    <div class="login-header">
      <div class="logo">
        <i class="fas fa-shield-alt"></i>
      </div>
      <h1 class="login-title">Bienvennue </h1>
      <p class="login-subtitle">Entrez vos identifiants pour accéder à votre compte</p>
    </div>
    
    <div class="login-body">
      <form id="loginForm" novalidate>
        <div class="form-group mb-3">
          <label for="email" class="form-label">Adresse email</label>
          <input type="email" class="form-control" id="email" placeholder="kouassi@example.com" required>
          <div class="invalid-feedback">S'il vous plaît, mettez une adresse email valide.</div>
        </div>
        
        <div class="form-group mb-3">
          <label for="password" class="form-label">Mot de passe </label>
          <div class="password-field">
            <input type="password" class="form-control" id="password" placeholder="Entrer votre mot de passe" required minlength="6">
            <span class="password-toggle" id="passwordToggle">
              <i class="fa-regular fa-eye"></i>
            </span>
            <div class="invalid-feedback">Le mot de passe doit comporter au moins 6 caractères.</div>
          </div>
        </div>
        
        <div class="form-group d-flex justify-content-between align-items-center mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="rememberMe">
            <label class="form-check-label" for="rememberMe">
              Se souvenir de moi 
            </label>
          </div>
          <a href="#" class="forgot-link" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Mot de passe oublié?</a>
        </div>
        
        <div class="login-button">
          <button type="submit" class="btn btn-primary w-100" id="loginBtn">Connexsion</button>
        </div>
        
        
        
        
      </form>
    </div>
  </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-4">Entrez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
        <form id="resetPasswordForm" novalidate>
          <div class="mb-3">
            <label for="resetEmail" class="form-label">Adresse email</label>
            <input type="email" class="form-control" id="resetEmail" placeholder="kouassi@example.com" required>
            <div class="invalid-feedback">S'il vous plaît, mettez une adresse email valide.</div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Retour</button>
        <button type="button" class="btn btn-primary" id="sendResetLink">Envoyer</button>
      </div>
    </div>
  </div>
</div>

<!-- Sign Up Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="signupModalLabel">Create Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="signupForm" novalidate>
          <div class="mb-3">
            <label for="fullName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullName" placeholder="Enter your full name" required>
            <div class="invalid-feedback">Please enter your full name.</div>
          </div>
          <div class="mb-3">
            <label for="signupEmail" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="signupEmail" placeholder="name@example.com" required>
            <div class="invalid-feedback">Please enter a valid email address.</div>
          </div>
          <div class="mb-3">
            <label for="signupPassword" class="form-label">Password</label>
            <div class="password-field">
              <input type="password" class="form-control" id="signupPassword" placeholder="Create a password" required minlength="6">
              <span class="password-toggle" id="signupPasswordToggle">
                <i class="fa-regular fa-eye"></i>
              </span>
              <div class="invalid-feedback">Password must be at least 6 characters.</div>
            </div>
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <div class="password-field">
              <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm your password" required minlength="6">
              <div class="invalid-feedback">Passwords do not match.</div>
            </div>
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="termsAgree" required>
            <label class="form-check-label" for="termsAgree">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
            <div class="invalid-feedback">You must agree to the terms and conditions.</div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="createAccount">Create Account</button>
      </div>
    </div>
  </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <i class="fas fa-check-circle text-success" style="font-size: 4rem; margin-bottom: 1rem;"></i>
        <h4>Operation Successful!</h4>
        <p id="successMessage" class="text-muted">Your action has been completed successfully.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it</button>
      </div>
    </div>
  </div>
</div>


<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Focus on email field when page loads
    document.getElementById('email').focus();
    
    // Password visibility toggle
    const setupPasswordToggle = (passwordId, toggleId) => {
      const passwordInput = document.getElementById(passwordId);
      const passwordToggle = document.getElementById(toggleId);
      
      if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', function() {
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);
          
          // Toggle icon
          const icon = this.querySelector('i');
          if (type === 'text') {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
          } else {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
          }
        });
      }
    };
    
    setupPasswordToggle('password', 'passwordToggle');
    setupPasswordToggle('signupPassword', 'signupPasswordToggle');
    
    // Remember me functionality
    const rememberMeCheckbox = document.getElementById('rememberMe');
    const emailInput = document.getElementById('email');
    
    // Check if we have saved data
    const savedEmail = localStorage.getItem('rememberEmail');
    const rememberMe = localStorage.getItem('rememberMe') === 'true';
    
    if (savedEmail && rememberMe) {
      emailInput.value = savedEmail;
      rememberMeCheckbox.checked = true;
    }
    
    // Form validation for login form
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    loginForm.addEventListener('submit', function(event) {
      event.preventDefault();
      
      if (!loginForm.checkValidity()) {
        event.stopPropagation();
        validateForm(loginForm);
      } else {
        // Save remember me preference
        if (rememberMeCheckbox.checked) {
          localStorage.setItem('rememberEmail', emailInput.value);
          localStorage.setItem('rememberMe', 'true');
        } else {
          localStorage.removeItem('rememberEmail');
          localStorage.setItem('rememberMe', 'false');
        }
        
        // Simulate login (in a real app, this would be an API call)
        loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Signing In...';
        loginBtn.disabled = true;
        
        setTimeout(() => {
          showSuccessModal('You have successfully logged in!');
          loginBtn.innerHTML = 'Sign In';
          loginBtn.disabled = false;
        }, 1500);
      }
      
      loginForm.classList.add('was-validated');
    });
    
    // Reset password form validation
    const resetForm = document.getElementById('resetPasswordForm');
    const sendResetBtn = document.getElementById('sendResetLink');
    
    if (sendResetBtn) {
      sendResetBtn.addEventListener('click', function() {
        if (!resetForm.checkValidity()) {
          validateForm(resetForm);
        } else {
          sendResetBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
          sendResetBtn.disabled = true;
          
          setTimeout(() => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
            modal.hide();
            showSuccessModal('Password reset link has been sent to your email!');
            sendResetBtn.innerHTML = 'Send Reset Link';
            sendResetBtn.disabled = false;
            resetForm.reset();
            resetForm.classList.remove('was-validated');
          }, 1500);
        }
        
        resetForm.classList.add('was-validated');
      });
    }
    
    // Sign up link
    const signupLink = document.getElementById('signupLink');
    
    if (signupLink) {
      signupLink.addEventListener('click', function(e) {
        e.preventDefault();
        const signupModal = new bootstrap.Modal(document.getElementById('signupModal'));
        signupModal.show();
      });
    }
    
    // Sign up form validation
    const signupForm = document.getElementById('signupForm');
    const createAccountBtn = document.getElementById('createAccount');
    const signupPassword = document.getElementById('signupPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    
    if (createAccountBtn) {
      createAccountBtn.addEventListener('click', function() {
        // Custom validation for password match
        if (signupPassword.value !== confirmPassword.value) {
          confirmPassword.setCustomValidity('Passwords do not match');
        } else {
          confirmPassword.setCustomValidity('');
        }
        
        if (!signupForm.checkValidity()) {
          validateForm(signupForm);
        } else {
          createAccountBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...';
          createAccountBtn.disabled = true;
          
          setTimeout(() => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('signupModal'));
            modal.hide();
            showSuccessModal('Your account has been created successfully!');
            createAccountBtn.innerHTML = 'Create Account';
            createAccountBtn.disabled = false;
            signupForm.reset();
            signupForm.classList.remove('was-validated');
          }, 1500);
        }
        
        signupForm.classList.add('was-validated');
      });
    }
    
    // Password confirmation validation
    if (confirmPassword) {
      confirmPassword.addEventListener('input', function() {
        if (signupPassword.value !== confirmPassword.value) {
          confirmPassword.setCustomValidity('Passwords do not match');
        } else {
          confirmPassword.setCustomValidity('');
        }
      });
    }
    
    // Utility function to validate a form
    function validateForm(form) {
      Array.from(form.elements).forEach(input => {
        if (input.type !== 'submit' && input.type !== 'button') {
          // Add blur event to validate on blur
          input.addEventListener('blur', function() {
            checkInput(input);
          });
          
          // Add input event to validate as typing
          input.addEventListener('input', function() {
            checkInput(input);
          });
          
          // Validate now
          checkInput(input);
        }
      });
    }
    
    function checkInput(input) {
      if (!input.validity.valid) {
        input.classList.add('is-invalid');
      } else {
        input.classList.remove('is-invalid');
      }
    }
    
    // Show success modal
    function showSuccessModal(message) {
      const successMessage = document.getElementById('successMessage');
      successMessage.textContent = message;
      
      const successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    }
  });
</script>
</body>
</html>