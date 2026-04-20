@extends('errors.layout')

@section('tab-title', '500 — Perturbation cosmique')
@section('body-class', 'error-rose')
@section('code', '500')
@section('title', 'Perturbation cosmique détectée')
@section('message')
  Une anomalie inattendue a traversé nos serveurs.
  L'équipe est déjà sur le coup — l'univers sera restauré sous peu.
@endsection
@section('actions')
  <a href="/" class="btn btn-primary">← Retour à l'accueil</a>
@endsection
