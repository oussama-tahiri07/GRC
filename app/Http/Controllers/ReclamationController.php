<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Http\Requests\StorereclamationRequest;
use App\Http\Requests\UpdatereclamationRequest;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reclamations = Reclamation::where('user_id', Auth::id())
            ->whereNotIn('etat', ['fermée', 'refusée'])
            ->get();

        return view('users.gestion_reclamation', compact('reclamations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create_reclamation');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorereclamationRequest $request)
    {
        $file_name = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $extension = $request->file('image')->extension();
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
            if (in_array(strtolower($extension), $allowedExtensions, true)) {
                $file_name = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                $request->file('image')->move(public_path('images/reclamations'), $file_name);
            }
        }

        Reclamation::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'etat' => 'en attente',
            'user_id' => Auth::id(),
            'image' => $file_name,
        ]);

        return redirect()->route('gestion_reclamation')->with(['success' => 'Réclamation créée avec succès']);
    }

    /**
     * Display the specified resource.
     */
    public function show($reclamation_id)
    {
        $reclamation = Reclamation::where('id', $reclamation_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('users.show_reclamation', compact('reclamation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($reclamation_id)
    {
        $reclamation = Reclamation::where('id', $reclamation_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$reclamation) {
            return redirect()->route('gestion_reclamation')->with(['error' => 'Réclamation introuvable ou accès non autorisé']);
        }

        return view('users.edit_reclamation', compact('reclamation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatereclamationRequest $request, $reclamation_id)
    {
        $reclamation = Reclamation::where('id', $reclamation_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$reclamation) {
            return redirect()->route('gestion_reclamation')->with(['error' => 'Réclamation introuvable ou accès non autorisé']);
        }

        $updateData = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $extension = $request->file('image')->extension();
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
            if (in_array(strtolower($extension), $allowedExtensions, true)) {
                // Delete previous image if exists
                if ($reclamation->image && file_exists(public_path('images/reclamations/' . $reclamation->image))) {
                    @unlink(public_path('images/reclamations/' . $reclamation->image));
                }

                $file_name = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                $request->file('image')->move(public_path('images/reclamations'), $file_name);
                $updateData['image'] = $file_name;
            }
        }

        $reclamation->update($updateData);

        return redirect()->route('gestion_reclamation')->with(['success' => 'Réclamation mise à jour avec succès']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($reclamation_id)
    {
        $reclamation = Reclamation::where('id', $reclamation_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$reclamation) {
            return redirect()->route('gestion_reclamation')->with(['error' => 'Réclamation introuvable ou accès non autorisé']);
        }

        if ($reclamation->image && file_exists(public_path('images/reclamations/' . $reclamation->image))) {
            @unlink(public_path('images/reclamations/' . $reclamation->image));
        }

        $reclamation->delete();

        return redirect()->route('gestion_reclamation')->with(['delete-success' => 'Réclamation supprimée']);
    }

    public function show_reclamations_traitee()
    {
        $reclamations = Reclamation::where('user_id', Auth::id())
            ->where('etat', 'traitée')
            ->get();

        return view('users.reclamations_traitee', compact('reclamations'));
    }

    public function show_reclamations_refusee()
    {
        $reclamations = Reclamation::where('user_id', Auth::id())
            ->where('etat', 'refusée')
            ->get();

        return view('users.reclamations_refusee', compact('reclamations'));
    }

    //*************** Solution ****************//
    public function accepte_solution_reclamation($reclamation_id)
    {
        $reclamation = Reclamation::where('id', $reclamation_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$reclamation) {
            return redirect()->route('liste_reclamations_traitee')->with(['error' => 'Réclamation introuvable ou accès non autorisé']);
        }

        $reclamation->update(['etat' => 'fermée']);

        return redirect()->route('liste_reclamations_traitee')->with(['success' => 'Réclamation ' . $reclamation_id . ' fermée']);
    }
}
