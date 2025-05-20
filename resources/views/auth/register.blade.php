<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .spinner {
      animation: spin 1s linear infinite;
    }
  </style>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">

  <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Créer un compte</h2>

    @if(session('success'))
      <div class="mb-4 text-green-600 text-sm">
        {{ session('success') }}
      </div>
    @endif

    <form id="registrationForm" method="POST" action="{{ route('register') }}" class="space-y-5">
      @csrf

      {{-- Nom --}}
      <div>
        <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="w-full mt-1 p-2 border rounded-lg @error('nom') border-red-500 @enderror" required>
        @error('nom')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Prénom --}}
      <div>
        <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
        <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" class="w-full mt-1 p-2 border rounded-lg @error('prenom') border-red-500 @enderror" required>
        @error('prenom')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Téléphone --}}
      <div>
        <label for="telephone" class="block text-sm font-medium text-gray-700">Téléphone</label>
        <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}" class="w-full mt-1 p-2 border rounded-lg @error('telephone') border-red-500 @enderror" required>
        @error('telephone')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Email --}}
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full mt-1 p-2 border rounded-lg @error('email') border-red-500 @enderror" required>
        @error('email')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Mot de passe --}}
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
        <div class="relative">
          <input type="password" name="password" id="password" class="w-full mt-1 p-2 border rounded-lg pr-10 @error('password') border-red-500 @enderror" required>
          <button type="button" id="togglePassword" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500">
            👁️
          </button>
        </div>
        @error('password')
          <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Confirmation du mot de passe --}}
      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmation</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full mt-1 p-2 border rounded-lg" required>
      </div>

      <button type="submit" id="submitButton" class="w-full bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition flex justify-center items-center">
        <span class="normal-state">S'inscrire</span>
        <span class="loading-state hidden flex items-center">
          <svg class="spinner mr-2 w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
          </svg>
          Traitement...
        </span>
      </button>

      <p class="text-center text-sm text-gray-600 mt-4">
        Déjà inscrit ?
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>
      </p>
    </form>
  </div>

  {{-- JS --}}
  <script>
    const form = document.getElementById('registrationForm');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const submitButton = document.getElementById('submitButton');

    togglePassword.addEventListener('click', () => {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
    });

    form.addEventListener('submit', () => {
      submitButton.disabled = true;
      submitButton.querySelector('.normal-state').classList.add('hidden');
      submitButton.querySelector('.loading-state').classList.remove('hidden');
    });
  </script>
</body>
</html>
