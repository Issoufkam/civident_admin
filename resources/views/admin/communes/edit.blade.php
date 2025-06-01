@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modification de la commune</h2>

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

    <form action="{{ route('admin.communes.update', $commune->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nom de la commune</label>
            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $commune->name) }}">
        </div>

        <div class="mb-3">
            <label for="code" class="form-label">Code de la commune</label>
            <input type="text" name="code" id="code" class="form-control" required maxlength="15" value="{{ old('code', $commune->code) }}">
        </div>

        <div class="mb-3">
            <label for="region" class="form-label">Région</label>
            <input type="text" name="region" id="region" class="form-control" required value="{{ old('region', $commune->region) }}">
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('admin.communes.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
