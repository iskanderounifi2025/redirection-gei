<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
   public function index()
{
    $categories = Categorie::all(); // Make sure to use the correct variable name
    return view('themes.ajoutercategorie', compact('categories'));
}
    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Categorie::create($request->all());

        return redirect()->route('themes.ajoutercategorie')
                         ->with('success', 'Categorie created successfully.');
    }

    public function show(Categorie $categorie)
    {
        return view('categories.show', compact('categorie'));
    }

    public function edit(Categorie $categorie)
    {
        return view('categories.edit', compact('categorie'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $categorie = Categorie::findOrFail($id);
        $categorie->name = $request->name;
        $categorie->save();

        return redirect()->route('themes.ajoutercategorie')->with('success', 'La catégorie a été mise à jour avec succès.');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();

        return redirect()->route('themes.ajoutercategorie')
                         ->with('success', 'Categorie deleted successfully.');
    }
}
