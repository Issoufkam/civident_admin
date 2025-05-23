<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommuneAdminController extends Controller
{
    // Liste des communes avec compteur de documents
    public function index()
    {
        $communes = Commune::withCount('documents')->paginate(20);
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
            'name' => 'required|string|max:30',
            'code' => 'required|string|unique:communes,code|max:10',
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
            'code' => 'required|string|max:10|unique:communes,code,' . $commune->id,
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

    // Liste des régions distinctes
    public function regionsIndex()
    {
        $regions = DB::table('communes')
            ->select('region', DB::raw('count(*) as lieux_count'))
            ->groupBy('region')
            ->orderBy('region')
            ->paginate(10);

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

    // Suppression d'une région (supprime toutes les communes de cette région)
    public function regionDestroy(Request $request)
    {
        $request->validate([
            'region' => 'required|string|exists:communes,region'
        ]);

        Commune::where('region', $request->region)->delete();

        return back()->with('success', 'Région supprimée avec succès !');
    }
}
