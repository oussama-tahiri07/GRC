<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\Statistique_Trait;

class AdminController extends Controller
{
    use Statistique_Trait;

    public function index()
    {
        // Rapport de statistique
        $current_month = Carbon::now()->month;
        $premier_du_mois = Carbon::create(date('Y'), $current_month)->startOfMonth();
        $fin_du_mois = $premier_du_mois->copy()->endOfMonth();

        // 1- le nombre total des réclamations par période (mois)
        $nombre_total_des_réclamations = $this->get_nombre_total_des_réclamations($premier_du_mois, $fin_du_mois);
        // 2- le nombre total des réclamations traitées par période (mois)
        $Nombre_des_réclamations_traitées = $this->get_nombre_des_réclamations_traitées($premier_du_mois, $fin_du_mois);
        // 3- le nombre total des réclamations rejetées par période (mois)
        $Nombre_des_réclamations_rejetées = $this->get_nombre_des_réclamations_rejetées($premier_du_mois, $fin_du_mois);
        // 4- le nombre des réclamations en double
        $Nombre_des_réclamations_double = $this->get_nombre_des_réclamations_double();

        /** graphe montrant toutes les réclamations traitées par type de réclamation **/
        $nombre_remarque_traitee = $this->RemarqueGraphe();
        $nombre_excalation_traitee = $this->ExcalationGraphe();
        $nombre_demandeDintervention_traitee = $this->DemandeDinterventionGraphe();

        return view('admins.home', compact(
            'nombre_total_des_réclamations',
            'Nombre_des_réclamations_traitées',
            'Nombre_des_réclamations_rejetées',
            'Nombre_des_réclamations_double',
            'nombre_remarque_traitee',
            'nombre_excalation_traitee',
            'nombre_demandeDintervention_traitee'
        ));
    }

    public function getReclamations()
    {
        $reclamations = Reclamation::whereIn('etat', ['en attente', 'en cours', 'traitee', 'traitée', 'refusee', 'refusée', 'acceptée'])
            ->take(50)
            ->get();

        return view('admins.gestion_reclamations', compact('reclamations'));
    }

    public function show_reclamation($reclamation_id)
    {
        $reclamation = Reclamation::find($reclamation_id);
        if (!$reclamation) {
            return redirect()->route('liste_reclamations')->with(['error' => 'Réclamation introuvable']);
        }

        return view('admins.show_reclamation', compact('reclamation'));
    }

    public function accepte_reclamation($reclamation_id)
    {
        $reclamation = Reclamation::find($reclamation_id);
        if (!$reclamation) {
            return redirect()->route('liste_reclamations')->with(['error' => 'Réclamation introuvable']);
        }

        $reclamation->update(['etat' => 'acceptée']);

        return redirect()->route('liste_reclamations')->with(['success' => 'Réclamation numéro ' . $reclamation_id . ' acceptée']);
    }

    public function refuse_reclamation_form($reclamation_id)
    {
        $reclamation = Reclamation::find($reclamation_id);
        if (!$reclamation) {
            return redirect()->route('liste_reclamations')->with(['error' => 'Réclamation introuvable']);
        }

        return view('admins.rejeter_reclamation', compact('reclamation'));
    }

    public function refuse_reclamation($reclamation_id, Request $request)
    {
        $request->validate([
            'raison_du_refus' => 'required|string|max:1000',
        ]);

        $reclamation = Reclamation::find($reclamation_id);
        if (!$reclamation) {
            return redirect()->route('liste_reclamations')->with(['error' => 'Réclamation introuvable']);
        }

        $reclamation->update([
            'etat' => 'refusée',
            'raison_du_refus' => $request->input('raison_du_refus'),
        ]);

        return redirect()->route('liste_reclamations')->with(['success' => 'Réclamation numéro ' . $reclamation_id . ' refusée']);
    }
}
