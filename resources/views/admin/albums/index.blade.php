@extends('admin.layouts.admin')

@section('title', 'Albums')

@section('header_actions')
  <a href="/admin/albums/create" class="btn btn-primary">+ Nouvel album</a>
@endsection

@section('content')
<div class="page-actions">
  <h2 class="page-title">Albums</h2>
</div>

<div class="card">
  @if($albums->isEmpty())
    <div class="empty-state">
      <p>Aucun album. <a href="/admin/albums/create" style="color:var(--color-violet);">Créer le premier</a></p>
    </div>
  @else
    <div class="table-wrapper">
      <table>
        <thead><tr><th>Couverture</th><th>Titre</th><th>Année</th><th>Platform</th><th>Tracks</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @foreach($albums as $album)
          <tr>
            <td style="width:60px;">
              @if($album->cover_url)
                <img src="{{ $album->cover_url }}" alt="" style="width:48px; height:48px; object-fit:cover; border-radius:6px;">
              @else
                <div style="width:48px; height:48px; background:var(--bg-primary); border-radius:6px; display:flex; align-items:center; justify-content:center; opacity:0.3;">✦</div>
              @endif
            </td>
            <td><a href="/admin/albums/{{ $album->id }}/edit" style="color:var(--text-primary); text-decoration:none; font-weight:500;">{{ $album->title }}</a></td>
            <td>{{ $album->year }}</td>
            <td><span class="badge badge--{{ $album->platform }}">{{ ucfirst($album->platform) }}</span></td>
            <td>{{ $album->tracks->count() }}</td>
            <td>
              <label class="toggle">
                <input type="checkbox" class="toggle-active" data-id="{{ $album->id }}" data-model="Album" data-field="active" {{ $album->active ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
              </label>
            </td>
            <td class="table-actions">
              <a href="/admin/albums/{{ $album->id }}/edit" class="btn btn-sm btn-secondary">Modifier</a>
              <form method="POST" action="/admin/albums/{{ $album->id }}" style="display:inline;">
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
@endsection
