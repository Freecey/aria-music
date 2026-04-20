@extends('admin.layouts.admin')

@section('title', 'Médias')

@section('content')
<div class="page-actions"><h2 class="page-title">Médias</h2></div>

@if($files->isEmpty())
  <div class="card"><div class="empty-state"><p>Aucun fichier uploadé.</p></div></div>
@else
  <div class="media-grid">
    @foreach($files as $file)
    <div class="media-item">
      <div class="media-preview">
        @if($file['type'] === 'image')
          <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}">
        @else
          <span class="media-placeholder">✦</span>
        @endif
      </div>
      <div class="media-info">
        <p class="media-name">{{ $file['name'] }}</p>
        <p style="font-size:0.6875rem; color:var(--text-muted);">{{ number_format($file['size'] / 1024, 1) }} KB</p>
        <form method="POST" action="/admin/media/{{ urlencode($file['path']) }}" style="margin-top:0.5rem;">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-sm btn-danger btn-delete" style="width:100%;">Supprimer</button>
        </form>
      </div>
    </div>
    @endforeach
  </div>
@endif
@endsection
