{{-- resources/views/admin/settings/prices.blade.php --}}
@extends('layouts.app') {{-- Assurez-vous d'utiliser votre layout principal --}}

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Configuration des Prix des Documents</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.settings.save') }}" method="POST">
                @csrf

                <p class="text-muted mb-4">Définissez les prix pour chaque type de document.</p>

                @foreach ($prices as $type => $price)
                    <div class="mb-3 row">
                        <label for="{{ $type }}_price" class="col-sm-4 col-form-label text-md-end">
                            {{ ucfirst(str_replace('-', ' ', $type)) }} :
                        </label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input type="number"
                                       name="prices[{{ $type }}]"
                                       id="{{ $type }}_price"
                                       class="form-control @error('prices.' . $type) is-invalid @enderror"
                                       value="{{ old('prices.' . $type, $price) }}"
                                       min="0"
                                       step="100" {{-- Par exemple, pas de 100 unités --}}
                                       required>
                                <span class="input-group-text">FCFA</span> {{-- Adaptez la devise --}}
                            </div>
                            @error('prices.' . $type)
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-2"></i> Enregistrer les prix
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
