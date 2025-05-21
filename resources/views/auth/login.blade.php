<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

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
    }

    .password-toggle:hover {
      color: var(--primary);
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
  </style>
</head>
<body>

<div class="login-container">
  <div class="login-card">
    <div class="login-header">
      <div class="logo">
        <i class="fas fa-shield-alt"></i>
      </div>
      <h1 class="login-title">Bienvenue</h1>
      <p class="login-subtitle">Entrez vos identifiants pour accéder à votre compte</p>
    </div>

    <div class="login-body">
      <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <!-- Email -->
        <div class="mb-3">
          <label for="email" class="form-label">Adresse email</label>
          <input
            type="email"
            class="form-control @error('email') is-invalid @enderror"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
            autocomplete="email"
            autofocus
            placeholder="ex: utilisateur@example.com"
          >
          @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <!-- Mot de passe -->
        <div class="mb-3">
          <label for="password" class="form-label">Mot de passe</label>
          <div class="password-field">
            <input
              type="password"
              class="form-control @error('password') is-invalid @enderror"
              id="password"
              name="password"
              required
              autocomplete="current-password"
              placeholder="Entrez votre mot de passe"
            >
            <span class="password-toggle" onclick="togglePasswordVisibility()">
              <i class="fa fa-eye" id="toggleIcon"></i>
            </span>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Se souvenir de moi -->
        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
          <label class="form-check-label" for="remember">Se souvenir de moi</label>
        </div>

        <!-- Bouton de connexion -->
        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-primary">Se connecter</button>
        </div>

        <!-- Lien mot de passe oublié -->
        @if (Route::has('password.request'))
        <div class="text-center mb-2">
          <a class="forgot-link" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
        </div>
        @endif

        <!-- Lien d'inscription -->
        <div class="register-link mt-3">
          Vous n'avez pas de compte ?
          <a href="{{ route('register') }}">Créer un compte</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function togglePasswordVisibility() {
    const passwordInput = document.getElementById("password");
    const toggleIcon = document.getElementById("toggleIcon");
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      toggleIcon.classList.remove("fa-eye");
      toggleIcon.classList.add("fa-eye-slash");
    } else {
      passwordInput.type = "password";
      toggleIcon.classList.remove("fa-eye-slash");
      toggleIcon.classList.add("fa-eye");
    }
  }
</script>

</body>
</html>
