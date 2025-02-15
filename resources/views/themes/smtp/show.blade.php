@extends('layouts.app')

@section('content')
<h1>Détails du message</h1>
<table class="table">
    <tr>
        <th>Expéditeur</th>
        <td>{{ $message['from'] }}</td>
    </tr>
    <tr>
        <th>Sujet</th>
        <td>{{ $message['subject'] }}</td>
    </tr>
    <tr>
        <th>Date</th>
        <td>{{ \Carbon\Carbon::parse($message['date'])->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <th>Contenu</th>
        <td>{{ $message['body'] }}</td>
    </tr>
</table>
<a href="{{ route('inbox') }}" class="btn btn-secondary">Retour</a>
@endsection
