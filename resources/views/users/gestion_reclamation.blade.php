@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="d-flex bd-highlight mb-3">
        <div class="me-auto p-2 bd-highlight">
          <h2>Reclamations</h2>
        </div>
        <div class="p-2 bd-highlight">
          <a class="btn btn-success" href="{{ route('liste_reclamations_traitee') }}">Reclamations Traitée</a>
          <a class="btn btn-danger" href="{{ route('liste_reclamations_refusee') }}">Reclamations Refusée</a>
          <a class="btn btn-secondary" href="{{ route('create_reclamation') }}">Create</a>
        </div>
      </div>

      @if(Session::has('success'))
      <div class="alert alert-success">
        {{ Session::get('success') }}
      </div>
      @endif

      @if(Session::has('delete-success'))
      <div class="alert alert-success">
        {{ Session::get('delete-success') }}
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
              <td><span class="badge bg-primary">{{ $reclamation->etat }}</span></td>
              <td>{{ Str::limit($reclamation->description, 50) }}</td>
              <td>
                <a class="btn btn-light btn-sm" href="{{ route('show_reclamation', $reclamation->id) }}">Show</a>
                <a class="btn btn-success btn-sm" href="{{ route('edit_reclamation', $reclamation->id) }}">Edit</a>
                <form action="{{ route('delete_reclamation', $reclamation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center text-muted">Aucune réclamation trouvée.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
