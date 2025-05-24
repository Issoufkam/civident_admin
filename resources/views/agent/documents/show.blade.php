@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Détails du document</h2>
        <a href="{{ route('agent.documents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition duration-150">

            Retour à la liste
        </a>
    </div>

    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="p-6 sm:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Numéro de registre</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $document->registry_number }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Type</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $document->type }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Commune</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $document->commune->name ?? '-' }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Statut</h3>
                        <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($document->status === 'APPROUVEE') bg-green-100 text-green-800
                            @elseif($document->status === 'REJETEE') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $document->status }}
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Utilisateur</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $document->user->name ?? '-' }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Agent</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $document->agent->name ?? '-' }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Date de soumission</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            @if ($document->status === 'REJETEE')
                <div class="mt-8 p-4 bg-red-50 rounded-lg border-l-4 border-red-400">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Commentaire de rejet</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>{{ $document->comments }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
