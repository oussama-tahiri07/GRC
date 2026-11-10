@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex bd-highlight mb-3">
                <div class="me-auto p-2 bd-highlight"><h2>Détail de la réclamation #{{ $reclamation->id }}</h2></div>
                <div class="p-2 bd-highlight">
                    <a class="btn btn-secondary" href="{{ route('gestion_reclamation') }}">Retourner</a>
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
                    <hr>
                    <div class="alert alert-success">
                        <strong>Solution proposée : </strong>{{ $reclamation->solution }}
                    </div>
                    @if($reclamation->etat !== 'fermée')
                    <form action="{{ route('accepte_solution_reclamation', $reclamation->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Accepter la solution et clore</button>
                    </form>
                    @endif
                    @endif

                    @if($reclamation->raison_du_refus)
                    <hr>
                    <div class="alert alert-danger">
                        <strong>Raison du refus : </strong>{{ $reclamation->raison_du_refus }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
