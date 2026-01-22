@extends('admin.layouts.app')

@section('title', 'Tribe Appearance Parts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-user-tie"></i> Tribe Appearance Parts</h2>
    <a href="{{ route('admin.appearance.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Part
    </a>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.appearance.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="tribe_id" class="form-label">Filter by Tribe</label>
                <select name="tribe_id" id="tribe_id" class="form-control">
                    <option value="">All Tribes</option>
                    @foreach($tribes as $tribe)
                        <option value="{{ $tribe->id }}" {{ request('tribe_id') == $tribe->id ? 'selected' : '' }}>
                            {{ $tribe->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="part_type" class="form-label">Filter by Part Type</label>
                <select name="part_type" id="part_type" class="form-control">
                    <option value="">All Types</option>
                    @foreach($partTypes as $type)
                        <option value="{{ $type }}" {{ request('part_type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.appearance.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- Appearance Parts Table -->
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Appearance Parts ({{ $parts->total() }})</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Preview</th>
                        <th>Name</th>
                        <th>Tribe</th>
                        <th>Part Type</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Default</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parts as $part)
                    <tr>
                        <td>{{ $part->id }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $part->image_url) }}" 
                                 alt="{{ $part->name }}" 
                                 class="img-thumbnail" 
                                 style="width: 60px; height: 60px; object-fit: cover;"
                                 data-toggle="tooltip" 
                                 title="{{ $part->name }}">
                        </td>
                        <td>
                            <strong>{{ $part->name }}</strong>
                            @if($part->description)
                                <br><small class="text-muted">{{ Str::limit($part->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $part->tribe->name }}</span>
                        </td>
                        <td>
                            <span class="badge badge-secondary">{{ ucfirst($part->part_type) }}</span>
                        </td>
                        <td>{{ $part->display_order }}</td>
                        <td>
                            @if($part->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($part->is_default)
                                <span class="badge badge-warning"><i class="fas fa-star"></i> Default</span>
                            @else
                                <form action="{{ route('admin.appearance.set-default', $part) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Set as Default">
                                        <i class="far fa-star"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.appearance.edit', $part) }}" 
                                   class="btn btn-sm btn-info" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.appearance.toggle', $part) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-sm btn-{{ $part->is_active ? 'warning' : 'success' }}" 
                                            title="{{ $part->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $part->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.appearance.destroy', $part) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this appearance part?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No appearance parts found. Add your first one!</p>
                            <a href="{{ route('admin.appearance.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Appearance Part
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($parts->hasPages())
    <div class="card-footer">
        {{ $parts->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Initialize tooltips
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush
