<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .spinner {
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4">
  <div class="w-full max-w-md">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
      <div class="p-8">
        <div class="flex justify-center mb-6">
          <div class="bg-blue-500 p-3 rounded-full text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
          </div>
        </div>

        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">
          Connexion
        </h2>

        <form id="loginForm" class="space-y-5">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
              Adresse e-mail
            </label>
            <input
              type="email"
              id="email"
              name="email"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 focus:outline-none transition-all duration-200"
              placeholder="exemple@email.com"
              required
            >
            <p class="mt-1 text-sm text-red-500 hidden" id="emailError"></p>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
              Mot de passe
            </label>
            <div class="relative">
              <input
                type="password"
                id="password"
                name="password"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-400 focus:outline-none transition-all duration-200"
                placeholder="••••••••"
                required
              >
              <button
                type="button"
                id="togglePassword"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
              >
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="show-password"><path d="M12 5c-7.333 0-12 6-12 6s4.667 6 12 6 12-6 12-6-4.667-6-12-6Z"/><circle cx="12" cy="11" r="3"/></svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hide-password hidden"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7.333 0 12 6 12 6a13.28 13.28 0 0 1-1.67 1.67"/><path d="M6.61 6.61A13.28 13.28 0 0 0 0 11s4.667 6 12 6c.337 0 .672-.01 1-.03"/><path d="M4.73 4.73 19.27 19.27"/></svg>
              </button>
            </div>
            <p class="mt-1 text-sm text-red-500 hidden" id="passwordError"></p>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                type="checkbox"
                id="remember"
                name="remember"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              >
              <label for="remember" class="ml-2 block text-sm text-gray-700">
                Se souvenir de moi
              </label>
            </div>
            <a href="#" class="text-sm text-blue-600 hover:underline">
              Mot de passe oublié?
            </a>
          </div>

          <button
            type="submit"
            id="submitButton"
            class="w-full py-2.5 px-4 rounded-lg text-white font-medium bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 transition-all duration-300"
          >
            <span class="normal-state">Se connecter</span>
            <span class="loading-state hidden">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Connexion...
            </span>
          </button>
        </form>
      </div>
    </div>

    <p class="text-center mt-6 text-gray-600">
      Pas encore de compte?
      <a href="{{ route('register') }}" class="text-blue-600 hover:underline transition-all duration-200">
        S'inscrire
      </a>
    </p>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('loginForm');
      const togglePassword = document.getElementById('togglePassword');
      const passwordInput = document.getElementById('password');
      const submitButton = document.getElementById('submitButton');
      const showPasswordIcon = togglePassword.querySelector('.show-password');
      const hidePasswordIcon = togglePassword.querySelector('.hide-password');

      // Toggle password visibility
      togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        showPasswordIcon.classList.toggle('hidden');
        hidePasswordIcon.classList.toggle('hidden');
      });

      // Form validation
      function validateForm() {
        let isValid = true;
        const errors = {};

        // Validate email
        if (!form.email.value.trim()) {
          errors.email = 'L\'email est requis';
          isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email.value)) {
          errors.email = 'Adresse email invalide';
          isValid = false;
        }

        // Validate password
        if (!form.password.value) {
          errors.password = 'Le mot de passe est requis';
          isValid = false;
        }

        // Display errors
        ['email', 'password'].forEach(field => {
          const errorElement = document.getElementById(`${field}Error`);
          if (errors[field]) {
            errorElement.textContent = errors[field];
            errorElement.classList.remove('hidden');
            form[field].classList.add('border-red-500');
            form[field].classList.add('focus:ring-red-200');
          } else {
            errorElement.classList.add('hidden');
            form[field].classList.remove('border-red-500');
            form[field].classList.remove('focus:ring-red-200');
          }
        });

        return isValid;
      }

      // Handle form submission
      form.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (validateForm()) {
          // Show loading state
          submitButton.disabled = true;
          submitButton.querySelector('.normal-state').classList.add('hidden');
          submitButton.querySelector('.loading-state').classList.remove('hidden');

          // Simulate form submission
          try {
            // In a real app, you would send the data to a server here
            await new Promise(resolve => setTimeout(resolve, 1000));

            // Reset form after successful submission
            form.reset();
            alert('Connexion réussie!');
          } catch (error) {
            console.error('Error submitting form:', error);
            alert('Une erreur est survenue. Veuillez réessayer.');
          } finally {
            // Reset button state
            submitButton.disabled = false;
            submitButton.querySelector('.normal-state').classList.remove('hidden');
            submitButton.querySelector('.loading-state').classList.add('hidden');
          }
        }
      });

      // Clear errors when user starts typing
      ['email', 'password'].forEach(field => {
        form[field].addEventListener('input', function() {
          const errorElement = document.getElementById(`${field}Error`);
          errorElement.classList.add('hidden');
          this.classList.remove('border-red-500');
          this.classList.remove('focus:ring-red-200');
        });
      });
    });
  </script>
</body>
</html>
