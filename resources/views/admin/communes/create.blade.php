@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Créer une nouvelle commune</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.communes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nom de la commune</label>
            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Code de la commune</label>
            <input type="text" name="code" id="code" class="form-control" required maxlength="10" value="{{ old('code') }}">
        </div>

        <div class="mb-3">
            <label for="region" class="form-label">Région</label>
            <select name="region" id="region" class="form-select" required>
                <option value="">-- Sélectionner une région --</option>
                @foreach ($regions as $region)
                    <option value="{{ $region }}" {{ old('region') == $region ? 'selected' : '' }}>
                        {{ $region }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Créer</button>
        <a href="{{ route('admin.communes.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
