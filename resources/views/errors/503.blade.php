@extends('errors.layout')

@section('tab-title', '503 — En maintenance')
@section('body-class', 'error-cyan')
@section('code', '503')
@section('title', 'La nébuleuse est en maintenance')
@section('message')
  Aria effectue une mise à jour cosmique.
  Le site sera de retour très bientôt — l'univers se reconfigure.
  @if(!empty($exception) && $exception->getMessage())
    <br><small style="opacity:0.5;font-size:0.8rem;margin-top:0.5rem;display:block;">{{ $exception->getMessage() }}</small>
  @endif
@endsection
@section('actions')
  <a href="javascript:location.reload()" class="btn btn-primary">Réessayer</a>
@endsection
