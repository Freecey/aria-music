@extends('admin.layouts.admin')

@section('title', 'Tracks')

@section('header_actions')
  <a href="/admin/tracks/create" class="btn btn-primary">+ Nouvelle track</a>
@endsection

@section('content')
<div class="page-actions">
  <h2 class="page-title">Tracks</h2>
  <form method="GET" action="/admin/tracks" style="display:flex; gap:0.5rem; align-items:center;">
    <select name="album_id" class="form-control" onchange="this.form.submit()" style="width:auto;">
      <option value="">Tous les albums</option>
      @foreach($albums as $album)
        <option value="{{ $album->id }}" {{ $albumId == $album->id ? 'selected' : '' }}>{{ $album->title }}</option>
      @endforeach
    </select>
  </form>
</div>

<div class="card">
  @if($tracks->isEmpty())
    <div class="empty-state"><p>Aucune track.</p></div>
  @else
    <div class="table-wrapper">
      <table>
        <thead><tr><th>Titre</th><th>Album</th><th>Platform</th><th>Durée</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @foreach($tracks as $track)
          <tr>
            <td style="font-weight:500;">{{ $track->title }}</td>
            <td><a href="/admin/albums/{{ $track->album_id }}/edit" style="color:var(--text-secondary);">{{ $track->album->title ?? '—' }}</a></td>
            <td><span class="badge badge--{{ $track->platform }}">{{ ucfirst($track->platform) }}</span></td>
            <td>{{ $track->duration ?? '—' }}</td>
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
    @if($tracks->hasPages())
      {{ $tracks->links('admin.partials.pagination') }}
    @endif
  @endif
</div>
@endsection
