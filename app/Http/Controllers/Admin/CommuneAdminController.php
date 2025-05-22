<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CommuneAdminController extends Controller
{
    public function index()
    {
        $communes = Commune::withCount('documents')->paginate(20);
        return view('admin.communes.index', compact('communes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:communes|max:10',
            'region' => 'required|string|max:100'
        ]);

        Commune::create($request->all());
        return back()->with('success', 'Commune créée !');
    }

    public function update(Request $request, Commune $commune)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:communes,code,'.$commune->id,
            'region' => 'required|string|max:100'
        ]);

        $commune->update($request->all());
        return back()->with('success', 'Commune mise à jour !');
    }

    public function destroy(Commune $commune)
    {
        $commune->delete();
        return back()->with('success', 'Commune supprimée !');
    }


    public function regionsIndex()
    {
        $regions = DB::table('communes')
                    ->select('region', DB::raw('count(*) as lieux_count'))
                    ->groupBy('region')
                    ->orderBy('region')
                    ->paginate(10); // ou ->get() si tu veux tout afficher

        return view('admin.regions.index', compact('regions'));
    }


    // Ajoute ceci dans CommuneAdminController

    public function regionStore(Request $request)
    {
        $request->validate([
            'region' => 'required|string|max:100|unique:communes,region'
        ]);

        // Crée une "commune" vide avec juste la région si elle n'existe pas déjà
        Commune::create([
            'name' => 'à définir',
            'code' => uniqid(),
            'region' => $request->region,
        ]);

        return back()->with('success', 'Région ajoutée avec succès !');
    }

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

    public function regionDestroy(Request $request)
    {
        $request->validate([
            'region' => 'required|string|exists:communes,region'
        ]);

        Commune::where('region', $request->region)->delete();

        return back()->with('success', 'Région supprimée avec succès !');
    }



}
