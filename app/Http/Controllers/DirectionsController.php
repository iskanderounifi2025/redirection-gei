<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DirectionsController extends Controller
{
    public function index()
    {
        $directions = Direction::all();
        return view('themes.ajouterdirection', compact('directions'));
    }


    public function indexForAddRevendeur(): View
    {
        $directions = Direction::all();
        return view('themes.ajouterrevendeur', compact('directions'));
    }


    public function create()
    {
        return view('themes.ajouterdirection');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:directions',
            'telephone' => 'nullable|string|max:15',
            'poste' => 'nullable|string|max:100',
        ]);

        Direction::create($request->all());

        return redirect()->route('themes.ajouterdirection')->with('success', 'Direction ajoutée avec succès.');
    }

    public function edit($id)
    {
        $direction = Direction::findOrFail($id);
        return view('directions.edit', compact('direction'));
    }
    
        /**
         * Met à jour une direction.
         */
        public function update(Request $request, $id)
        {
            // Valider les données du formulaire
            $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'telephone' => 'required|string|max:15',
                'poste' => 'required|string|max:255',
            ]);
    
            // Récupérer la direction à modifier
            $direction = Direction::findOrFail($id);
    
            // Mettre à jour les champs de la direction
            $direction->nom = $request->input('nom');
            $direction->prenom = $request->input('prenom');
            $direction->email = $request->input('email');
            $direction->telephone = $request->input('telephone');
            $direction->poste = $request->input('poste');
            $direction->save(); // Sauvegarde des modifications
    
            // Redirection avec un message de succès
            return redirect()->route('directions.index')->with('success', 'Direction modifiée avec succès.');
        }
    
    
    public function destroy($id)
    {
        $direction = Direction::findOrFail($id);
        $direction->delete();

        return redirect()->route('themes.ajouterdirection')->with('success', 'Direction supprimée avec succès.');
    }
}
