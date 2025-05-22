@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Interface des Régions</h1>

    {{-- Message de succès --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Formulaire d’ajout de région --}}
    <div class="card mb-4">
        <div class="card-header">Ajouter une nouvelle région</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.regions.store') }}">
                @csrf
                <div class="form-group mb-3">
                    <label for="region">Nom de la région</label>
                    <input type="text" name="region" id="region" class="form-control @error('region') is-invalid @enderror" required>
                    @error('region')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

    {{-- Tableau des régions --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nom de la région</th>
                <th>Nombre de lieux d'état civil</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($regions as $region)
                <tr>
                    <td>{{ $loop->iteration + ($regions->currentPage() - 1) * $regions->perPage() }}</td>
                    <td>{{ $region->region }}</td>
                    <td>{{ $region->lieux_count }}</td>
                    <td>
                        {{-- Formulaire de suppression --}}
                        <form action="{{ route('admin.regions.destroy') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="region" value="{{ $region->region }}">
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette région ? Cela supprimera toutes les communes associées.')">
                                Supprimer
                            </button>
                        </form>

                        {{-- Formulaire de modification --}}
                        <form action="{{ route('admin.regions.update') }}" method="POST" class="d-inline ms-2" id="edit-region-form-{{ Str::slug($region->region) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="old_region" value="{{ $region->region }}">

                            <div class="input-group input-group-sm">
                                <input type="text"
                                    name="new_region"
                                    value="{{ old('new_region', $region->region) }}"
                                    class="form-control form-control-sm {{ $errors->has('new_region') ? 'is-invalid' : '' }}"
                                    style="width: 150px"
                                    required
                                    minlength="2"
                                    maxlength="100"
                                    id="region-input-{{ Str::slug($region->region) }}"
                                    onkeydown="return event.key !== 'Enter'"> <!-- Empêche la soumission par Enter -->

                                <button type="submit"
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="tooltip"
                                        title="Enregistrer les modifications">
                                    <i class="bi bi-check-lg"></i>
                                </button>

                                <button type="button"
                                        class="btn btn-sm btn-outline-secondary"
                                        onclick="resetRegionForm('{{ $region->region }}', '{{ Str::slug($region->region) }}')"
                                        data-bs-toggle="tooltip"
                                        title="Annuler">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            @error('new_region')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $regions->links() }}
    </div>
</div>
@endsection
