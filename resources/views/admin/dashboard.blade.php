@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header" style="margin-bottom: 2rem;">
  <h2>Bienvenue, {{ auth()->user()->name }} ✦</h2>
</div>

<!-- Stats -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-value">{{ $stats['albums'] }}</div>
    <div class="stat-label">Albums</div>
  </div>
  <div class="stat-card">
    <div class="stat-value">{{ $stats['tracks'] }}</div>
    <div class="stat-label">Tracks</div>
  </div>
  <div class="stat-card">
    <div class="stat-value">{{ $stats['updates'] }}</div>
    <div class="stat-label">Actualités</div>
  </div>
  <div class="stat-card">
    <div class="stat-value">{{ $stats['links'] }}</div>
    <div class="stat-label">Liens sociaux</div>
  </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
  <!-- Recent Albums -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Albums récents</h3>
      <a href="/admin/albums" class="btn btn-sm btn-secondary">Voir tout</a>
    </div>
    @if($recentAlbums->isEmpty())
      <p class="empty-state" style="padding:1.5rem;">Aucun album</p>
    @else
      <div class="table-wrapper">
        <table>
          <thead><tr><th>Titre</th><th>Année</th><th>Tracks</th><th>Status</th></tr></thead>
          <tbody>
            @foreach($recentAlbums as $album)
            <tr>
              <td><a href="/admin/albums/{{ $album->id }}/edit" style="color:var(--text-primary); text-decoration:none;">{{ $album->title }}</a></td>
              <td>{{ $album->year }}</td>
              <td>{{ $album->tracks->count() }}</td>
              <td><span class="badge {{ $album->active ? 'badge--active' : 'badge--inactive' }}">{{ $album->active ? 'Actif' : 'Inactif' }}</span></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <!-- Recent API Logs -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Derniers appels API</h3>
      <a href="/admin/logs" class="btn btn-sm btn-secondary">Voir tout</a>
    </div>
    @if($recentLogs->isEmpty())
      <p class="empty-state" style="padding:1.5rem;">Aucun log</p>
    @else
      <div class="table-wrapper">
        <table>
          <thead><tr><th>Méthode</th><th>Endpoint</th><th>Status</th><th>Date</th></tr></thead>
          <tbody>
            @foreach($recentLogs as $log)
            <tr>
              <td><span class="log-method log-method--{{ $log->method }}">{{ $log->method }}</span></td>
              <td style="font-size:0.75rem; color:var(--text-muted);">{{ $log->endpoint }}</td>
              <td><span class="log-status log-status--{{ $log->status_code >= 200 && $log->status_code < 300 ? '2xx' : ($log->status_code >= 400 && $log->status_code < 500 ? '4xx' : '5xx') }}">{{ $log->status_code }}</span></td>
              <td style="font-size:0.75rem; color:var(--text-muted);">{{ $log->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>

<style>
.page-header h2 { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; }
</style>
@endsection
