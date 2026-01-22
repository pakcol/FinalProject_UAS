@extends('admin.layouts.app')

@section('title', 'Manage Tribes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users"></i> Manage Tribes</h2>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@foreach ($tribes as $tribe)
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-flag"></i> {{ $tribe->name }}</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.tribes.update', $tribe->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="description_{{ $tribe->id }}" class="form-label">Description</label>
                <textarea name="description" id="description_{{ $tribe->id }}" 
                          class="form-control @error('description') is-invalid @enderror" 
                          rows="3" required>{{ old('description', $tribe->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="melee_attack_{{ $tribe->id }}" class="form-label">
                        <i class="fas fa-fist-raised text-danger"></i> Melee Attack
                    </label>
                    <input type="number" name="melee_attack" id="melee_attack_{{ $tribe->id }}" 
                           class="form-control @error('melee_attack') is-invalid @enderror" 
                           value="{{ old('melee_attack', $tribe->melee_attack) }}" min="0" required>
                    @error('melee_attack')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="range_attack_{{ $tribe->id }}" class="form-label">
                        <i class="fas fa-bullseye text-warning"></i> Range Attack
                    </label>
                    <input type="number" name="range_attack" id="range_attack_{{ $tribe->id }}" 
                           class="form-control @error('range_attack') is-invalid @enderror" 
                           value="{{ old('range_attack', $tribe->range_attack) }}" min="0" required>
                    @error('range_attack')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="magic_attack_{{ $tribe->id }}" class="form-label">
                        <i class="fas fa-magic text-primary"></i> Magic Attack
                    </label>
                    <input type="number" name="magic_attack" id="magic_attack_{{ $tribe->id }}" 
                           class="form-control @error('magic_attack') is-invalid @enderror" 
                           value="{{ old('magic_attack', $tribe->magic_attack) }}" min="0" required>
                    @error('magic_attack')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="melee_defense_{{ $tribe->id }}" class="form-label">
                        <i class="fas fa-shield-alt text-success"></i> Melee Defense
                    </label>
                    <input type="number" name="melee_defense" id="melee_defense_{{ $tribe->id }}" 
                           class="form-control @error('melee_defense') is-invalid @enderror" 
                           value="{{ old('melee_defense', $tribe->melee_defense) }}" min="0" required>
                    @error('melee_defense')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="range_defense_{{ $tribe->id }}" class="form-label">
                        <i class="fas fa-shield-alt text-info"></i> Range Defense
                    </label>
                    <input type="number" name="range_defense" id="range_defense_{{ $tribe->id }}" 
                           class="form-control @error('range_defense') is-invalid @enderror" 
                           value="{{ old('range_defense', $tribe->range_defense) }}" min="0" required>
                    @error('range_defense')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="magic_defense_{{ $tribe->id }}" class="form-label">
                        <i class="fas fa-shield-alt text-warning"></i> Magic Defense
                    </label>
                    <input type="number" name="magic_defense" id="magic_defense_{{ $tribe->id }}" 
                           class="form-control @error('magic_defense') is-invalid @enderror" 
                           value="{{ old('magic_defense', $tribe->magic_defense) }}" min="0" required>
                    @error('magic_defense')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection
