@extends('admin.layouts.admin')

@section('title', 'Logs API')

@section('content')
<div class="page-actions"><h2 class="page-title">Logs API</h2></div>

<div class="card" style="margin-bottom:1rem;">
  <form method="GET" action="/admin/logs" style="display:flex; gap:1rem; align-items:flex-end; flex-wrap:wrap;">
    <div>
      <label class="form-label" style="margin-bottom:0.25rem;">Méthode</label>
      <select name="method" class="form-control" style="width:auto;" onchange="this.form.submit()">
        <option value="">Toutes</option>
        @foreach(['GET','POST','PUT','PATCH','DELETE'] as $m)
          <option value="{{ $m }}" {{ request('method') == $m ? 'selected' : '' }}>{{ $m }}</option>
        @endforeach
      </select>
    </div>
    <div style="flex:1; min-width:200px;">
      <label class="form-label" style="margin-bottom:0.25rem;">Endpoint (contient)</label>
      <input type="text" name="endpoint" class="form-control" value="{{ request('endpoint') }}" placeholder="/api/v1/albums">
    </div>
    <button type="submit" class="btn btn-secondary">Filtrer</button>
    <a href="/admin/logs" class="btn btn-secondary">Reset</a>
  </form>
</div>

<div class="card">
  @if($logs->isEmpty())
    <div class="empty-state"><p>Aucun log.</p></div>
  @else
    <div class="table-wrapper">
      <table>
        <thead><tr><th>Méthode</th><th>Endpoint</th><th>Status</th><th>User</th><th>IP</th><th>Date</th></tr></thead>
        <tbody>
          @foreach($logs as $log)
          <tr>
            <td><span class="log-method log-method--{{ $log->method }}">{{ $log->method }}</span></td>
            <td style="font-size:0.75rem; color:var(--text-muted); max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $log->endpoint }}">{{ $log->endpoint }}</td>
            <td><span class="log-status log-status--{{ $log->status_code >= 200 && $log->status_code < 300 ? '2xx' : ($log->status_code >= 400 && $log->status_code < 500 ? '4xx' : '5xx') }}">{{ $log->status_code }}</span></td>
            <td style="font-size:0.8125rem;">{{ $log->user?->email ?? '—' }}</td>
            <td style="font-size:0.75rem; color:var(--text-muted);">{{ $log->ip }}</td>
            <td style="font-size:0.75rem; color:var(--text-muted); white-space:nowrap;">{{ $log->created_at->format('d/m/Y H:i') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($logs->hasPages())
      <div class="pagination">{{ $logs->links() }}</div>
    @endif
  @endif
</div>
@endsection
