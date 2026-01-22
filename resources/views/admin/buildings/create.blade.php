@extends('admin.layouts.app')

@section('title', 'Create Building')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.buildings.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Buildings
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-plus-circle"></i> Create New Building</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.buildings.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Building Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">Building Type *</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="main" {{ old('type') == 'main' ? 'selected' : '' }}>Main Building</option>
                        <option value="barracks" {{ old('type') == 'barracks' ? 'selected' : '' }}>Barracks</option>
                        <option value="mine" {{ old('type') == 'mine' ? 'selected' : '' }}>Gold Mine</option>
                        <option value="walls" {{ old('type') == 'walls' ? 'selected' : '' }}>Defense Walls</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="gold_cost" class="form-label">Gold Cost *</label>
                    <input type="number" class="form-control @error('gold_cost') is-invalid @enderror" 
                           id="gold_cost" name="gold_cost" value="{{ old('gold_cost', 0) }}" min="0" required>
                    @error('gold_cost')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="level" class="form-label">Level *</label>
                    <input type="number" class="form-control @error('level') is-invalid @enderror" 
                           id="level" name="level" value="{{ old('level', 1) }}" min="1" required>
                    @error('level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="gold_production" class="form-label">Gold Production</label>
                    <input type="number" class="form-control @error('gold_production') is-invalid @enderror" 
                           id="gold_production" name="gold_production" value="{{ old('gold_production', 0) }}" min="0">
                    @error('gold_production')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="troop_production" class="form-label">Troop Production</label>
                    <input type="number" class="form-control @error('troop_production') is-invalid @enderror" 
                           id="troop_production" name="troop_production" value="{{ old('troop_production', 0) }}" min="0">
                    @error('troop_production')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="defense_bonus" class="form-label">Defense Bonus</label>
                    <input type="number" class="form-control @error('defense_bonus') is-invalid @enderror" 
                           id="defense_bonus" name="defense_bonus" value="{{ old('defense_bonus', 0) }}" min="0">
                    @error('defense_bonus')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active (Available for players to build)
                        </label>
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.buildings.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Building
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
