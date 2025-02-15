<!-- resources/views/evenements/edit.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Modifier l'Événement</h2>
    <form action="{{ route('evenements.update', $evenement->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Indique à Laravel que nous effectuons une mise à jour -->
        
        <div class="form-group">
            <label for="nom">Nom de l'Événement</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ $evenement->nom }}" required>
        </div>
        
        <div class="form-group">
            <label for="date_debut">Date de Début</label>
            <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ $evenement->date_debut }}" required>
        </div>
        
        <div class="form-group">
            <label for="date_fin">Date de Fin</label>
            <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ $evenement->date_fin }}" required>
        </div>

        <div class="form-group">
            <label for="brand_id">Marque</label>
            <select name="brand_id" id="brand_id" class="form-control" required>
                <option value="">Sélectionner une marque</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $evenement->brand_id == $brand->id ? 'selected' : '' }}>
                        {{ $brand->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour l'Événement</button>
    </form>
</div>
@endsection
