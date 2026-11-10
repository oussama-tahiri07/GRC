@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex bd-highlight mb-3">
                <div class="me-auto p-2 bd-highlight"><h2>Mes réclamations traitées</h2></div>
                <div class="p-2 bd-highlight">
                    <a class="btn btn-secondary" href="{{ route('gestion_reclamation') }}">Back</a>
                </div>
            </div>

            @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
            @endif

            @if(Session::has('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
            @endif

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Type</th>
                            <th scope="col">Etat</th>
                            <th scope="col">Description</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="mytable">
                        @forelse($reclamations as $reclamation)
                        <tr>
                            <th scope="row">{{ $reclamation->id }}</th>
                            <td>{{ $reclamation->name }}</td>
                            <td>{{ $reclamation->type }}</td>
                            <td><span class="badge bg-success">{{ $reclamation->etat }}</span></td>
                            <td>{{ Str::limit($reclamation->description, 50) }}</td>
                            <td>
                                <a class="btn btn-light btn-sm" href="{{ route('show_reclamation', $reclamation->id) }}">Show</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucune réclamation traitée.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
