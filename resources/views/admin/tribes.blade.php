@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('title', 'Manage Tribes')

@section('content')
<div class="dashboard-container">

    <h2>Manage Tribes</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @foreach ($tribes as $tribe)
    <div class="dashboard-card mb-4">

        <h4>{{ $tribe->name }}</h4>

        <form method="POST" action="{{ route('admin.tribes.update', $tribe->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-2">
                <label>Description</label>
                <textarea name="description" class="form-control">{{ $tribe->description }}</textarea>
            </div>

            <div class="row">
                <div class="col">
                    <label>Melee Attack</label>
                    <input type="number" name="melee_attack" class="form-control" value="{{ $tribe->melee_attack }}">
                </div>
                <div class="col">
                    <label>Range Attack</label>
                    <input type="number" name="range_attack" class="form-control" value="{{ $tribe->range_attack }}">
                </div>
                <div class="col">
                    <label>Magic Attack</label>
                    <input type="number" name="magic_attack" class="form-control" value="{{ $tribe->magic_attack }}">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col">
                    <label>Melee Defense</label>
                    <input type="number" name="melee_defense" class="form-control" value="{{ $tribe->melee_defense }}">
                </div>
                <div class="col">
                    <label>Range Defense</label>
                    <input type="number" name="range_defense" class="form-control" value="{{ $tribe->range_defense }}">
                </div>
                <div class="col">
                    <label>Magic Defense</label>
                    <input type="number" name="magic_defense" class="form-control" value="{{ $tribe->magic_defense }}">
                </div>
            </div>

            <button class="btn btn-primary mt-3">
                Save Changes
            </button>
        </form>

    </div>
    @endforeach

</div>
@endsection
