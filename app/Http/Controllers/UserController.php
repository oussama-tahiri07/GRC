<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('index');
        $this->middleware('auth:admin')->except('index');
    }

    public function index()
    {
        return view('users.home');
    }

    public function liste_utilisateur()
    {
        $utilisateurs = User::all();
        return view('admins.gestion_utilisateurs', compact('utilisateurs'));
    }

    // Ajouter un utilisateur
    public function create_utilisateur()
    {
        return view('admins.create_utilisateur');
    }

    public function store_utilisateur(UserRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('liste_utilisateur')->with(['success' => 'Utilisateur ajouté avec succès']);
    }

    // Modifier un utilisateur
    public function edit_utilisateur($utilisateur_id)
    {
        $utilisateur = User::find($utilisateur_id);
        if (!$utilisateur) {
            return redirect()->route('liste_utilisateur')->with(['error' => 'Utilisateur introuvable']);
        }

        return view('admins.edit_utilisateur', compact('utilisateur'));
    }

    public function update_utilisateur(UserRequest $request, $utilisateur_id)
    {
        $utilisateur = User::find($utilisateur_id);
        if (!$utilisateur) {
            return redirect()->route('liste_utilisateur')->with(['error' => 'Utilisateur introuvable']);
        }

        $validated = $request->validated();
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $utilisateur->update($updateData);

        return redirect()->route('liste_utilisateur')->with(['success' => 'Utilisateur modifié avec succès']);
    }

    // Supprimer utilisateur avec ses réclamations
    public function delete_utilisateur($utilisateur_id)
    {
        $utilisateur = User::find($utilisateur_id);
        if (!$utilisateur) {
            return redirect()->route('liste_utilisateur')->with(['error' => 'Utilisateur introuvable']);
        }

        // Clean up associated reclamation images
        foreach ($utilisateur->reclamations as $reclamation) {
            if ($reclamation->image && file_exists(public_path('images/reclamations/' . $reclamation->image))) {
                @unlink(public_path('images/reclamations/' . $reclamation->image));
            }
            $reclamation->delete();
        }

        $utilisateur->delete();

        return redirect()->route('liste_utilisateur')->with(['success' => 'Utilisateur supprimé avec succès']);
    }
}
