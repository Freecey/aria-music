@extends('admin.layouts.admin')

@section('title', 'Utilisateurs')

@section('header_actions')
  <a href="/admin/users/create" class="btn btn-primary">+ Nouvel utilisateur</a>
@endsection

@section('content')
<div class="page-actions"><h2 class="page-title">Utilisateurs</h2></div>

<div class="card">
  @if($users->isEmpty())
    <div class="empty-state"><p>Aucun utilisateur.</p></div>
  @else
    <div class="table-wrapper">
      <table>
        <thead><tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Créé le</th><th>Actions</th></tr></thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td style="font-weight:500;">{{ $user->name }}</td>
            <td style="color:var(--text-secondary);">{{ $user->email }}</td>
            <td><span class="badge {{ $user->role === 'admin' ? 'badge--active' : 'badge--inactive' }}">{{ $user->role }}</span></td>
            <td style="font-size:0.8125rem; color:var(--text-muted);">{{ $user->created_at->format('d/m/Y') }}</td>
            <td class="table-actions">
              <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-sm btn-secondary">Modifier</a>
              @if($user->id !== auth()->id())
                <form method="POST" action="/admin/users/{{ $user->id }}" style="display:inline;">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger btn-delete">Suppr.</button></form>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($users->hasPages())
      {{ $users->links('admin.partials.pagination') }}
    @endif
  @endif
</div>
@endsection
