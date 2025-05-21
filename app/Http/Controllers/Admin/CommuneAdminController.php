<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commune;
use Illuminate\Http\Request;

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
        // Récupérer toutes les régions distinctes
        $regions = Commune::select('region')->distinct()->paginate(20);

        return view('admin.regions.index', compact('regions'));
    }

}
