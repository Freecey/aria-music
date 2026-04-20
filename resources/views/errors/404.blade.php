@extends('errors.layout')

@section('tab-title', '404 — Page introuvable')
@section('body-class', 'error-violet')
@section('code', '404')
@section('title', 'Signal perdu dans le cosmos')
@section('message')
  La page que tu cherches s'est évaporée dans le vide intersidéral.
  Elle n'existe pas, ou a été déplacée vers une autre orbite.
@endsection
@section('actions')
  <a href="/" class="btn btn-primary">← Retour à l'accueil</a>
  <a href="javascript:history.back()" class="btn btn-ghost">Page précédente</a>
@endsection
