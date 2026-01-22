@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<h2 class="mb-4"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>

<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-building"></i> Total Buildings</h5>
                <h2>{{ \App\Models\Building::count() }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-check-circle"></i> Active Buildings</h5>
                <h2>{{ \App\Models\Building::where('is_active', 1)->count() }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-users"></i> Total Users</h5>
                <h2>{{ \App\Models\User::count() }}</h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-flag"></i> Total Kingdoms</h5>
                <h2>{{ \App\Models\Kingdom::count() }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Quick Actions</h5>
    </div>
    <div class="card-body">
        <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary me-2">
            <i class="fas fa-plus"></i> Add New Building
        </a>
        <a href="{{ route('admin.buildings.index') }}" class="btn btn-secondary">
            <i class="fas fa-list"></i> Manage Buildings
        </a>
    </div>
</div>
@endsection
