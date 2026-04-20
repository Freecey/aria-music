@extends('admin.layouts.admin')

@section('title', 'Liens Sociaux')

@section('header_actions')
  <a href="/admin/links/create" class="btn btn-primary">+ Nouveau lien</a>
@endsection

@section('content')
<div class="page-actions"><h2 class="page-title">Liens Sociaux</h2></div>

<div class="card">
  @if($links->isEmpty())
    <div class="empty-state"><p>Aucun lien. <a href="/admin/links/create" style="color:var(--color-violet);">Ajouter</a></p></div>
  @else
    <div class="table-wrapper">
      <table>
        <thead><tr><th>Ordre</th><th>Icône</th><th>Plateforme</th><th>Label</th><th>URL</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          @foreach($links as $link)
          <tr>
            <td class="sort-handle" style="cursor:grab;">☰</td>
            <td style="width:40px;"><div style="width:32px;height:32px;color:var(--color-violet);">{!! $link->icon_svg ?? '🔗' !!}</div></td>
            <td style="font-weight:500;">{{ $link->platform }}</td>
            <td>{{ $link->label }}</td>
            <td style="font-size:0.75rem; color:var(--text-muted); max-width:200px; overflow:hidden; text-overflow:ellipsis;">{{ $link->url }}</td>
            <td>
              <label class="toggle">
                <input type="checkbox" class="toggle-active" data-id="{{ $link->id }}" data-model="SocialLink" data-field="active" {{ $link->active ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
              </label>
            </td>
            <td class="table-actions">
              <a href="/admin/links/{{ $link->id }}/edit" class="btn btn-sm btn-secondary">Modifier</a>
              <form method="POST" action="/admin/links/{{ $link->id }}" style="display:inline;">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger btn-delete">Suppr.</button></form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection
