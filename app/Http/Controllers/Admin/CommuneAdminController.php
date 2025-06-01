<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use Illuminate\Http\Request; // Assurez-vous que cette ligne est présente
use Illuminate\Support\Facades\DB;

class CommuneAdminController extends Controller
{
    // Liste des communes avec compteur de documents et fonctionnalité de recherche
    public function index(Request $request) // <-- Ajout de l'injection de Request ici
    {
        $query = Commune::query()->withCount('documents'); // Commence la requête pour les communes

        // Logique de recherche dynamique
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%"); // Ajout de la recherche par code
        }

        // Pagination à 15 éléments
        $communes = $query->paginate(15); // <-- Changé de 20 à 15

        return view('admin.communes.index', compact('communes'));
    }

    // Formulaire de création d'une commune
    public function create()
    {
        $regions = Commune::select('region')->distinct()->orderBy('region')->pluck('region');
        return view('admin.communes.create',compact('regions'));
    }

    // Enregistrement d'une nouvelle commune
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:communes|max:30',
            'code' => 'required|string|max:15',
            'region' => 'required|string|max:50'
        ]);

        Commune::create($request->only(['name', 'code', 'region']));

        return redirect()->route('admin.communes.index')->with('success', 'Commune créée avec succès !');
    }

    // Formulaire de modification d'une commune
    public function edit(Commune $commune)
    {
        return view('admin.communes.edit', compact('commune'));
    }

    // Mise à jour d'une commune
    public function update(Request $request, Commune $commune)
    {
        $request->validate([
            'name' => 'required|string|max:30',
            'code' => 'required|string|max:15|unique:communes,code,' . $commune->id,
            'region' => 'required|string|max:50'
        ]);

        $commune->update($request->only(['name', 'code', 'region']));

        return redirect()->route('admin.communes.index')->with('success', 'Commune mise à jour avec succès !');
    }

    // Suppression d'une commune
    public function destroy(Commune $commune)
    {
        $commune->delete();
        return redirect()->route('admin.communes.index')->with('success', 'Commune supprimée avec succès !');
    }

    // Liste des régions distinctes avec pagination et recherche
    public function regionsIndex(Request $request) // <-- Ajout de l'injection de Request ici
    {
        $query = DB::table('communes')
            ->select('region', DB::raw('count(*) as lieux_count'))
            ->groupBy('region')
            ->orderBy('region');

        // Logique de recherche pour les régions
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where('region', 'like', "%{$search}%");
        }

        // Pagination à 10 éléments (tel que demandé précédemment pour les régions)
        $regions = $query->paginate(10);

        return view('admin.regions.index', compact('regions'));
    }

    // Création d'une région (via une commune vide)
    public function regionStore(Request $request)
    {
        $request->validate([
            'region' => 'required|string|max:100|unique:communes,region'
        ]);

        Commune::create([
            'name' => 'à définir',
            'code' => uniqid(),
            'region' => $request->region,
        ]);

        return back()->with('success', 'Région ajoutée avec succès !');
    }

    // Mise à jour du nom d'une région
    public function regionUpdate(Request $request)
    {
        $request->validate([
            'old_region' => 'required|string|exists:communes,region',
            'new_region' => 'required|string|max:100|unique:communes,region'
        ]);

        Commune::where('region', $request->old_region)
            ->update(['region' => $request->new_region]);

        return back()->with('success', 'Région mise à jour avec succès !');
    }

   public function regionDestroy(string $region) // Le paramètre $region est maintenant directement injecté
    {
        // Validation: Vérifie si la région existe dans la table 'communes'
        // et qu'elle est bien une chaîne de caractères.
        // La validation 'exists' est appliquée directement sur le paramètre injecté.
        // Note: Si vous utilisez le Route Model Binding pour un ID, le 'exists' serait implicite.
        // Ici, comme c'est une string, nous devons valider manuellement.
        $validated = validator(['region' => $region], [
            'region' => 'required|string|exists:communes,region'
        ])->validate();

        // Suppression de toutes les communes appartenant à cette région
        Commune::where('region', $validated['region'])->delete();

        return back()->with('success', 'Région supprimée avec succès !');
    }
}
