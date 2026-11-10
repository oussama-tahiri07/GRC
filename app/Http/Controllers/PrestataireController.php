<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Illuminate\Http\Request;

class PrestataireController extends Controller
{
    public function index()
    {
        return view('prestataires.home');
    }

    public function inbox()
    {
        $reclamations = Reclamation::whereIn('etat', ['acceptée', 'en cours', 'en attente'])
            ->take(50)
            ->get();
        return view('prestataires.inbox', compact('reclamations'));
    }

    public function get_reclamation_details($reclamation_id)
    {
        $reclamation = Reclamation::find($reclamation_id);
        if (!$reclamation) {
            return redirect()->route('prestataire_inbox')->with(['error' => 'Réclamation introuvable']);
        }
        return view('prestataires.reclamation_details', compact('reclamation'));
    }

    public function resoudre_reclamation($reclamation_id)
    {
        $reclamation = Reclamation::find($reclamation_id);
        if (!$reclamation) {
            return redirect()->route('prestataire_inbox')->with(['error' => 'Réclamation introuvable']);
        }

        return view('prestataires.resoudre_reclamation', compact('reclamation'));
    }

    public function reclamation_resolue(Request $request, $reclamation_id)
    {
        $request->validate([
            'etat' => 'required|string|in:traitée,en cours',
            'solution' => 'required|string|max:2000',
        ]);

        $reclamation = Reclamation::find($reclamation_id);
        if (!$reclamation) {
            return redirect()->route('prestataire_inbox')->with(['error' => 'Réclamation introuvable']);
        }

        $reclamation->update([
            'etat' => $request->input('etat'),
            'solution' => $request->input('solution'),
        ]);

        return redirect()->route('prestataire_inbox')->with(['success' => 'Réclamation numéro ' . $reclamation_id . ' a été mise à jour']);
    }
}
