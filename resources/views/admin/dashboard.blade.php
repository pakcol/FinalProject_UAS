@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<div class="dashboard-container">

    <div class="dashboard-header">
        <h2>Admin Dashboard</h2>
    </div>

    <div class="dashboard-card">
        <h4>Tribe Settings</h4>
        <p>Edit base attack & defense setiap tribe.</p>
        <a href="{{ route('admin.tribes') }}" class="btn btn-primary">
            Manage Tribes
        </a>
    </div>


    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: 20px;">
        @csrf
        <button class="btn btn-danger">
            Logout
        </button>
    </form>

</div>
@endsection
