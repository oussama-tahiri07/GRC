@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex bd-highlight mb-3">
                <div class="me-auto p-2 bd-highlight"><h2>Détail de la réclamation #{{ $reclamation->id }}</h2></div>
                <div class="p-2 bd-highlight">
                    <a class="btn btn-secondary" href="{{ route('liste_reclamations') }}">Retourner</a>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <p><strong>Nom de réclamation : </strong>{{ $reclamation->name }}</p>
                    <p><strong>Type : </strong>{{ $reclamation->type }}</p>
                    <p><strong>Etat : </strong><span class="badge bg-info">{{ $reclamation->etat }}</span></p>
                    <p><strong>Description : </strong>{{ $reclamation->description }}</p>

                    @if($reclamation->image)
                    <div class="mb-3">
                        <strong>Fichier : </strong><br>
                        <img src="{{ asset('images/reclamations/' . $reclamation->image) }}" width="200px" class="img-thumbnail mt-2" alt="Fichier réclamation" />
                    </div>
                    @endif

                    @if($reclamation->solution)
                    <div class="alert alert-success mt-3">
                        <strong>Solution du prestataire : </strong>{{ $reclamation->solution }}
                    </div>
                    @endif

                    @if($reclamation->raison_du_refus)
                    <div class="alert alert-danger mt-3">
                        <strong>Raison du refus : </strong>{{ $reclamation->raison_du_refus }}
                    </div>
                    @endif

                    <div class="mt-4">
                        <a class="btn btn-secondary" href="{{ route('liste_reclamations') }}">Retourner</a>

                        @if($reclamation->etat === 'en attente')
                        <form action="{{ route('accepte_reclamation', $reclamation->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">Accepter</button>
                        </form>
                        <a class="btn btn-danger" href="{{ route('refuse_reclamation_form', $reclamation->id) }}">Refuser</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
