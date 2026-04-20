@extends('admin.layouts.admin')

@section('title', $album->title)

@section('header_actions')
  <a href="/admin/albums/{{ $album->id }}/edit" class="btn btn-primary">Modifier</a>
@endsection

@section('content')
<div style="max-width: 800px;">
  <div class="page-actions" style="margin-bottom:1.5rem;">
    <h2 class="page-title">{{ $album->title }}</h2>
    <a href="/admin/albums" class="btn btn-secondary">← Albums</a>
  </div>

  <div style="display:grid; grid-template-columns: 1fr 220px; gap:1.5rem; align-items:start;">

    <!-- Main info -->
    <div style="display:flex; flex-direction:column; gap:1rem;">

      <div class="card">
        <div style="display:flex; gap:1.5rem; align-items:flex-start;">
          @if($album->cover_url)
            <img src="{{ $album->cover_url }}" alt="{{ $album->title }}" style="width:96px; height:96px; object-fit:cover; border-radius:8px; flex-shrink:0;">
          @else
            <div style="width:96px; height:96px; background:var(--bg-primary); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:2rem; opacity:0.3; flex-shrink:0;">✦</div>
          @endif
          <div style="flex:1; min-width:0;">
            <h3 style="font-size:1.25rem; font-weight:600; margin-bottom:0.5rem;">{{ $album->title }}</h3>
            <div style="display:flex; gap:0.75rem; align-items:center; flex-wrap:wrap; margin-bottom:0.75rem;">
              <span style="color:var(--text-muted); font-size:0.875rem;">{{ $album->year }}</span>
              <span class="badge badge--{{ $album->platform }}">{{ ucfirst($album->platform) }}</span>
              <span style="font-size:0.75rem; color:{{ $album->active ? 'var(--color-violet)' : 'var(--text-muted)' }};">
                {{ $album->active ? '● Actif' : '○ Inactif' }}
              </span>
            </div>
            @if($album->description)
              <p style="font-size:0.875rem; color:var(--text-secondary); line-height:1.6;">{{ $album->description }}</p>
            @endif
            @if($album->media_url)
              <a href="{{ $album->media_url }}" target="_blank" style="font-size:0.8125rem; color:var(--color-violet); text-decoration:none; display:inline-flex; align-items:center; gap:0.25rem; margin-top:0.5rem;">
                Écouter ↗
              </a>
            @endif
          </div>
        </div>
      </div>

      <!-- Tracks -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Tracks <span style="color:var(--text-muted); font-weight:400;">{{ $album->tracks->count() }}</span></h3>
          <a href="/admin/tracks/create?album_id={{ $album->id }}" class="btn btn-sm btn-primary">+ Ajouter</a>
        </div>
        @if($album->tracks->isEmpty())
          <div class="empty-state">
            <p>Aucune track. <a href="/admin/tracks/create?album_id={{ $album->id }}" style="color:var(--color-violet);">Ajouter la première</a></p>
          </div>
        @else
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th style="width:32px;">#</th>
                  <th>Titre</th>
                  <th>Durée</th>
                  <th>Platform</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($album->tracks as $track)
                <tr>
                  <td style="color:var(--text-muted); font-size:0.8125rem;">{{ $track->sort }}</td>
                  <td style="font-weight:500;">{{ $track->title }}</td>
                  <td style="color:var(--text-muted); font-size:0.875rem;">{{ $track->duration ?? '—' }}</td>
                  <td>
                    @if($track->platform)
                      <span class="badge badge--{{ $track->platform }}">{{ ucfirst($track->platform) }}</span>
                    @else
                      <span style="color:var(--text-muted);">—</span>
                    @endif
                  </td>
                  <td>
                    <label class="toggle">
                      <input type="checkbox" class="toggle-active" data-id="{{ $track->id }}" data-model="Track" data-field="active" {{ $track->active ? 'checked' : '' }}>
                      <span class="toggle-slider"></span>
                    </label>
                  </td>
                  <td class="table-actions">
                    <a href="/admin/tracks/{{ $track->id }}/edit" class="btn btn-sm btn-secondary">Modifier</a>
                    <form method="POST" action="/admin/tracks/{{ $track->id }}" style="display:inline;">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger btn-delete">Suppr.</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

    <!-- Sidebar: cover large -->
    @if($album->cover_url)
    <div class="card" style="padding:0.75rem;">
      <img src="{{ $album->cover_url }}" alt="{{ $album->title }}" style="width:100%; border-radius:6px;">
      <p style="font-size:0.75rem; color:var(--text-muted); text-align:center; margin-top:0.5rem;">{{ $album->title }}</p>
    </div>
    @endif

  </div>
</div>
@endsection
