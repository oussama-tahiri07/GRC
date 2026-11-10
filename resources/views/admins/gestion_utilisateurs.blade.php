@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex bd-highlight mb-3">
                <div class="me-auto p-2 bd-highlight"><h2>Utilisateurs</h2></div>
                <div class="p-2 bd-highlight">
                    <a class="btn btn-secondary" href="{{ route('create_utilisateur') }}">Create</a>
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
                            <th scope="col">Email</th>
                            <th scope="col">Created at</th>
                            <th scope="col">Updated at</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="mytable">
                        @forelse($utilisateurs as $utilisateur)
                        <tr>
                            <th scope="row">{{ $utilisateur->id }}</th>
                            <td>{{ $utilisateur->name }}</td>
                            <td>{{ $utilisateur->email }}</td>
                            <td>{{ $utilisateur->created_at }}</td>
                            <td>{{ $utilisateur->updated_at }}</td>
                            <td>
                                <a class="btn btn-success btn-sm" href="{{ route('edit_utilisateur', $utilisateur->id) }}">Edit</a>
                                <form action="{{ route('delete_utilisateur', $utilisateur->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ainsi que toutes ses réclamations ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun utilisateur trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
