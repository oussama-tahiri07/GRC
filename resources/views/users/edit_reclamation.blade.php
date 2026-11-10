@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex bd-highlight mb-3">
                <div class="me-auto p-2 bd-highlight"><h2>Modifier la réclamation</h2></div>
                <div class="p-2 bd-highlight">
                    <a class="btn btn-secondary" href="{{ route('gestion_reclamation') }}">Back</a>
                </div>
            </div>

            @if(Session::has('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form class="form-card fc" action="{{ route('update_reclamation', $reclamation->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" class="form-control" value="{{ old('name', $reclamation->name) }}" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" name="type" required>
                        <option value="Remarque" {{ old('type', $reclamation->type) == 'Remarque' ? 'selected' : '' }}>Remarque</option>
                        <option value="Demande d'intervention" {{ old('type', $reclamation->type) == "Demande d'intervention" ? 'selected' : '' }}>Demande d'intervention</option>
                        <option value="Excalation" {{ old('type', $reclamation->type) == 'Excalation' ? 'selected' : '' }}>Excalation</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" maxlength="2000" rows="5" name="description" required>{{ old('description', $reclamation->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image (optionnel, max 5MB)</label>
                    @if($reclamation->image)
                    <div class="mb-2">
                        <small class="text-muted">Image actuelle :</small><br>
                        <img src="{{ asset('images/reclamations/' . $reclamation->image) }}" width="150px" class="img-thumbnail" alt="Image de réclamation" />
                    </div>
                    @endif
                    <input type="file" class="form-control" accept="image/*" name="image">
                    <small class="text-muted">Laisser vide pour conserver l'image existante.</small>
                </div>

                <div class="mt-4">
                    <button class="btn btn-primary" type="submit">Modifier</button>
                    <a class="btn btn-secondary" href="{{ route('gestion_reclamation') }}">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
