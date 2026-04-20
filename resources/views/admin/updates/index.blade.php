@extends('admin.layouts.admin')

@section('title', 'Actualités')

@section('header_actions')
  <a href="/admin/updates/create" class="btn btn-primary">+ Nouvelle actualité</a>
@endsection

@section('content')
<div class="page-actions"><h2 class="page-title">Actualités</h2></div>

<div class="card">
  @if($updates->isEmpty())
    <div class="empty-state"><p>Aucune actualité. <a href="/admin/updates/create" style="color:var(--color-violet);">Publier</a></p></div>
  @else
    <div class="table-wrapper">
      <table>
        <thead><tr><th>Date</th><th>Contenu</th><th>Visible</th><th>Actions</th></tr></thead>
        <tbody>
          @foreach($updates as $update)
          <tr>
            <td style="font-size:0.8125rem; color:var(--text-muted); white-space:nowrap;">{{ $update->published_at?->format('d/m/Y') ?? '—' }}</td>
            <td style="max-width:400px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $update->body }}</td>
            <td>
              <label class="toggle">
                <input type="checkbox" class="toggle-active" data-id="{{ $update->id }}" data-model="Update" data-field="visible" {{ $update->visible ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
              </label>
            </td>
            <td class="table-actions">
              <a href="/admin/updates/{{ $update->id }}/edit" class="btn btn-sm btn-secondary">Modifier</a>
              <form method="POST" action="/admin/updates/{{ $update->id }}" style="display:inline;">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger btn-delete">Suppr.</button></form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($updates->hasPages())
      <div class="pagination">{{ $updates->links() }}</div>
    @endif
  @endif
</div>
@endsection
