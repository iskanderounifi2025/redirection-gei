<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commercial; 
use App\Models\Direction; 

use Illuminate\View\View;

use Illuminate\Support\Facades\Storage;

class CommercialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nomprenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:commercial,email',
            'telephone' => 'required|string|max:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'direction_id' => 'nullable|integer', // Validation de l'état
            'id_team'=>'nullable|integer',

        ]);

        $commercial = new Commercial();
        $commercial->nomprenom = $request->input('nomprenom');
        $commercial->email = $request->input('email');
        $commercial->telephone = $request->input('telephone');
        $commercial->direction_id = $request->input('direction_id');
$commercial->id_team=$request->input('id_team');
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('commercial', 'public'); // Store in the 'public/commercial' directory
            $commercial->image = $imagePath;
        }

        $commercial->save();

        return redirect()->back()->with('success', 'Commercial ajouté avec succès.');
    }

    public function create(): View
    {
        $commercials = Commercial::all();
       $directions = Direction::all();
           
           // Passer les revendeurs à la vue 'themes.ajouterredirection'
           return view('themes.ajoutercommercial', compact('commercials', 'directions'));
     
        
    }
   
    public function destroy($id)
    {
        $commercial = Commercial::findOrFail($id);

        if ($commercial->image) {
            Storage::disk('public')->delete($commercial->image);
        }

        $commercial->delete();

        return redirect()->route('commercials.index')->with('success', 'Commercial supprimé avec succès!');
    }

    public function edit($id)
{
    // Récupérer le commercial par son ID
    $commercial = Commercial::findOrFail($id);

    // Retourner une réponse JSON avec les données du commercial
    return response()->json($commercial);
}

     
public function update(Request $request, $id)
{
    // Validation des données envoyées
    $request->validate([
        'nomprenom' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:commercial,email,' . $id, // Exclure l'email actuel
        'telephone' => 'required|string|max:15',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation pour l'image
        'direction_id' => 'nullable|integer',
        'id_team' => 'nullable|integer',
    ]);

    // Trouver le commercial à modifier
    $commercial = Commercial::findOrFail($id);
    $commercial->nomprenom = $request->input('nomprenom');
    $commercial->email = $request->input('email');
    $commercial->telephone = $request->input('telephone');
    $commercial->direction_id = $request->input('direction_id');
    $commercial->id_team = $request->input('id_team');

    // Mettre à jour l'image si une nouvelle image a été téléchargée
    if ($request->hasFile('image')) {
        // Vérifier si une ancienne image existe et la supprimer
        if ($commercial->image) {
            Storage::delete('public/' . $commercial->image);
        }

        // Stocker la nouvelle image
        $imagePath = $request->file('image')->store('commercial', 'public');
        $commercial->image = $imagePath;
    }

    // Sauvegarder les modifications dans la base de données
    $commercial->save();

    // Rediriger après la mise à jour avec un message de succès
    return redirect()->route('themes.ajoutercommercial')->with('success', 'Commercial modifié avec succès.');
}




    public function index()
    {
        $commercials = Commercial::all();
        return view('themes.ajoutercommercial', compact('commercials'));
         
    }

    public function indexForAddCommercial(): View
    {
        $commercials = Commercial::all(); // Récupérer tous les commerciaux
        return view('themes.ajoutermarque', compact('commercials')); // Passer les commerciaux à la vue ajoutercommercial
    }
   
    public function indexForUpdateCommercial(): View
    {
        $commercialsUP = Commercial::all(); // Récupérer tous les commerciaux
        return view('themes.listerevendeur', compact('commercialsUP')); // Passer les commerciaux à la vue ajoutercommercial
    }


    
}
