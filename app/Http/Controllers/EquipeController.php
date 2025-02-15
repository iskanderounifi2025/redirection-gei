<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipe;
use App\Models\Brand; // Assurez-vous que ce modèle existe
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Importation de Hash
use Illuminate\Support\Facades\Mail;

use Illuminate\View\View;

class EquipeController extends Controller
{
    // Afficher la liste des équipes
    public function index(): View
    {
        // Récupérer toutes les équipes avec leurs marques
        $equipes = Equipe::all();
        
        // Récupérer toutes les marques
        $brands = Brand::all();

        // Retourner la vue avec les données des équipes et des marques
        return view('themes.listeequipe', compact('equipes', 'brands'));
    }

    // Afficher le formulaire de création
    public function create()
    {
        return view('Equipes.create');
    }

    // Enregistrer une nouvelle équipe
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nometprenom' => 'required|string',
            'username' => 'required|string|unique:team,username',
            'email' => 'required|email|unique:team,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:1,2',
            'image' => 'nullable|image|max:2048',
            'marque_id' => 'required|exists:marques,id', 
            'id_team' => 'required|exists:team,id',

        ]);

        $equipe = new Equipe();
        $equipe->fill($validated); // Le mot de passe sera haché automatiquement grâce au mutateur

        // Si une image est téléchargée, stockez-la dans le dossier 'team' du disque public
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('team', 'public');
            $equipe->image = $imagePath;
        }

        $equipe->save();

        return redirect()->back()->with('success', 'Équipe ajoutée avec succès !');
    }
 
    // Activer ou désactiver une équipe
    public function toggleStatus($id)
    {
        $equipe = Equipe::findOrFail($id);
        $equipe->etat = $equipe->etat === 'active' ? 'inactive' : 'active';
        $equipe->save();

        return redirect()->back()->with('success', 'État de l’équipe modifié avec succès !');
    }

    // Relation entre équipe et marque
    public function marque()
    {
        return $this->belongsTo(Brand::class, 'marque_id');
    }
  
    // Mutateur pour hacher le mot de passe
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function login(Request $request)
    {
        // Vérifier si l'utilisateur est déjà authentifié
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('success', 'Vous êtes déjà connecté');
        }
    
        // Valider les entrées
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    
        // Recherche de l'utilisateur par son nom d'utilisateur
        $user = Equipe::where('email', $request->email)->first();
    
        // Si l'utilisateur n'existe pas, renvoyer une erreur
        if (!$user) {
            return back()->withErrors(['email' => 'Nom d\'utilisateur incorrect.']);
        }
    
        // Vérifier si le mot de passe correspond (en utilisant Hash::check pour comparer les mots de passe)
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }
    
        // Connexion de l'utilisateur
        Auth::login($user);
    
        // Rediriger vers la route 'dashboard'
        return redirect()->route('dashboard')->with('success', 'Connexion réussie');
    }
    

    // Afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('themes.login');
    }
    // Méthode de déconnexion
    public function logout()
    {
        Auth::logout(); // Déconnecte l'utilisateur actuel

        // Rediriger vers la page de connexion ou une autre page après la déconnexion
        return redirect()->route('themes.login')->with('success', 'Déconnexion réussie');
    }
/*Edit */

public function edit()
{
    $team = session('team'); // Récupère l'utilisateur connecté
    return view('team.edit', compact('team'));
}
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'nometprenom' => 'required|string',
        'email' => 'required|email|unique:team,email,' . $id,
        'role' => 'required|in:1,2',
        'image' => 'nullable|image|max:2048',
        'marque_id' => 'required|exists:marques,id',
    ]);

    $equipe = Equipe::findOrFail($id);
    $equipe->fill($validated);

    if ($request->hasFile('image')) {
        // Supprimer l'ancienne image si elle existe
        if ($equipe->image) {
            Storage::disk('public')->delete($equipe->image);
        }
        $imagePath = $request->file('image')->store('team', 'public');
        $equipe->image = $imagePath;
    }

    $equipe->save();

    return redirect()->back()->with('success', 'Équipe modifiée avec succès !');
}
  


public function affecter(Request $request, $id)
{
    // Valider les données
    $validated = $request->validate([
        'marque_id' => 'required|exists:marques,id', // Vérifie si la marque existe
    ]);

    // Trouver l'équipe par ID
    $equipe = Equipe::findOrFail($id);

    // Vérifier si la marque est déjà associée à cette équipe
    if ($equipe->marque_id == (int)$validated['marque_id']) {
        return redirect()->back()->with('error', 'Cette marque est déjà associée à cette équipe.');
    }

    // Assurez-vous que marque_id est bien un entier
    $equipe->marque_id = (int)$validated['marque_id']; // Conversion explicite en entier
    $equipe->save();

    return redirect()->back()->with('success', 'Marque mise à jour avec succès pour cette équipe.');
}
public function showForgotPasswordForm()
{
    return view('themes.password-oublier');
}

public function sendPasswordByEmail(Request $request)
{
    // Valider l'email
    $request->validate([
        'email' => 'required|email',
    ]);

    // Rechercher l'équipe par e-mail
    $team = Equipe::where('email', $request->email)->first();

    if (!$team) {
        return back()->withErrors(['email' => 'Aucun utilisateur trouvé avec cet email.']);
    }

    // Récupérer le mot de passe (ATTENTION : uniquement si le mot de passe est stocké en clair)
    $password = $team->password;

    // Envoyer l'e-mail contenant le mot de passe
    Mail::raw("Votre mot de passe est : $password", function ($message) use ($team) {
        $message->to($team->email)
                ->subject('Votre mot de passe oublié');
    });

    // Alerte de succès
    return back()->with('success', 'Un e-mail contenant votre mot de passe a été envoyé.');
}




}
