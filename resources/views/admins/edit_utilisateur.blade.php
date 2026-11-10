@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex bd-highlight mb-3">
                <div class="me-auto p-2 bd-highlight"><h2>Modifier un Utilisateur</h2></div>
                <div class="p-2 bd-highlight">
                    <a class="btn btn-secondary" href="{{ route('liste_utilisateur') }}">Back</a>
                </div>
            </div>

            @if(Session::has('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
            @endif

            <form class="form-card fc" action="{{ route('update_utilisateur', $utilisateur->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $utilisateur->name) }}" name="name" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $utilisateur->email) }}" name="email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Nouveau mot de passe (laisser vide pour ne pas modifier)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Nouveau mot de passe" name="password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control" placeholder="Confirmer le nouveau mot de passe" name="password_confirmation">
                </div>

                <div class="mt-4">
                    <button class="btn btn-primary" type="submit">Modifier</button>
                    <a class="btn btn-secondary" href="{{ route('liste_utilisateur') }}">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
