@extends('admin.admin_master')
@section('admin')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i data-feather="activity"></i> Backup Database</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
            <form action="{{ route('backups.store') }}" method="POST" class="d-flex align-items-center gap-3 mb-0">
                @csrf
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="only_db" id="only_db" value="1" checked>
                    <label class="form-check-label" for="only_db">Database only</label>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i data-feather="database"></i> Run Backup Now
                </button>
            </form>

            <div class="text-muted small">
                Scheduled: backup {{ $schedule['backup_time'] }} &middot; cleanup {{ $schedule['cleanup_time'] }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Size</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($files as $file)
                        <tr>
                            <td>{{ $file['filename'] }}</td>
                            <td>{{ $file['size'] }}</td>
                            <td>{{ $file['date']->format('Y-m-d H:i') }} ({{ $file['date']->diffForHumans() }})</td>
                            <td class="text-end">
                                <a href="{{ route('backups.download', $file['filename']) }}" class="btn btn-sm btn-outline-secondary">
                                    <i data-feather="download"></i> Download
                                </a>
                                <form action="{{ route('backups.destroy', $file['filename']) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this backup? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i data-feather="trash-2"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No backups yet. Run one above.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>feather.replace();</script>
@endpush
