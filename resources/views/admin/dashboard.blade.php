@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<h2 class="mb-4"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kingdoms</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_kingdoms'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-flag fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Battles</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_battles'] }}</div>
                        <div class="text-xs text-muted mt-1">{{ $stats['training_battles'] }} training</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-sword fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Buildings</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_buildings'] }}</div>
                        <div class="text-xs text-muted mt-1">{{ $stats['active_buildings'] }} active</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- User Registration Trend -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">User Registration Trend (Last 7 Days)</h6>
            </div>
            <div class="card-body">
                <canvas id="registrationChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Tribe Distribution -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tribe Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="tribeChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Activities & Top Players Row -->
<div class="row">
    <!-- Recent Activities -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="activityTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="battles-tab" data-toggle="tab" href="#battles" role="tab">Battles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab">New Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="buildings-tab" data-toggle="tab" href="#buildings" role="tab">Buildings</a>
                    </li>
                </ul>
                <div class="tab-content" id="activityTabContent">
                    <!-- Battles Tab -->
                    <div class="tab-pane fade show active" id="battles" role="tabpanel">
                        <div class="table-responsive mt-3">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Attacker</th>
                                        <th>Defender</th>
                                        <th>Winner</th>
                                        <th>Gold</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBattles as $battle)
                                    <tr>
                                        <td>{{ $battle->attacker->name }}</td>
                                        <td>{{ $battle->defender->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $battle->winner_id == $battle->attacker_id ? 'success' : 'danger' }}">
                                                {{ $battle->winner_id == $battle->attacker_id ? $battle->attacker->name : $battle->defender->name }}
                                            </span>
                                        </td>
                                        <td>💰 {{ number_format($battle->gold_stolen) }}</td>
                                        <td><small>{{ $battle->created_at->diffForHumans() }}</small></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Users Tab -->
                    <div class="tab-pane fade" id="users" role="tabpanel">
                        <div class="list-group list-group-flush mt-3">
                            @foreach($recentUsers as $user)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $user->username }}</h6>
                                    <small>{{ $user->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">
                                    <span class="badge badge-info">{{ $user->kingdom ? $user->kingdom->name : 'No Kingdom' }}</span>
                                    @if($user->kingdom)
                                    <span class="badge badge-secondary">{{ $user->kingdom->tribe->name }}</span>
                                    @endif
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Buildings Tab -->
                    <div class="tab-pane fade" id="buildings" role="tabpanel">
                        <div class="list-group list-group-flush mt-3">
                            @foreach($recentBuildings as $kb)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $kb->kingdom->name }}</h6>
                                    <small>{{ $kb->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">
                                    Built <strong>{{ $kb->building->name }}</strong> (Level {{ $kb->level }})
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Players -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Top Players</h6>
            </div>
            <div class="card-body">
                @foreach($topPlayers as $index => $player)
                <div class="d-flex align-items-center mb-3 pb-3 {{ $loop->last ? '' : 'border-bottom' }}">
                    <div class="mr-3">
                        @if($index == 0)
                        <div class="badge badge-warning" style="font-size: 1.5rem; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">🥇</div>
                        @elseif($index == 1)
                        <div class="badge badge-secondary" style="font-size: 1.5rem; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">🥈</div>
                        @elseif($index == 2)
                        <div class="badge badge-danger" style="font-size: 1.5rem; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">🥉</div>
                        @else
                        <div class="badge badge-dark" style="font-size: 1rem; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">{{ $index + 1 }}</div>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $player->name }}</h6>
                        <small class="text-muted">{{ $player->user->username }} • {{ $player->tribe->name }}</small>
                    </div>
                    <div class="text-right">
                        <div class="font-weight-bold">{{ number_format($player->total_attack_power) }}</div>
                        <small class="text-muted">Power</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card shadow mb-4">
    <div class="card-header py-3 bg-dark text-white">
        <h6 class="m-0 font-weight-bold">Quick Actions</h6>
    </div>
    <div class="card-body">
        <a href="{{ route('admin.buildings.create') }}" class="btn btn-primary mr-2">
            <i class="fas fa-plus"></i> Add New Building
        </a>
        <a href="{{ route('admin.buildings.index') }}" class="btn btn-secondary mr-2">
            <i class="fas fa-list"></i> Manage Buildings
        </a>
        <a href="{{ route('admin.tribes') }}" class="btn btn-info mr-2">
            <i class="fas fa-users"></i> Manage Tribes
        </a>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
// User Registration Trend Chart
const registrationCtx = document.getElementById('registrationChart').getContext('2d');
const registrationChart = new Chart(registrationCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($registrationTrend->pluck('date')) !!},
        datasets: [{
            label: 'New Users',
            data: {!! json_encode($registrationTrend->pluck('count')) !!},
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Tribe Distribution Chart
const tribeCtx = document.getElementById('tribeChart').getContext('2d');
const tribeChart = new Chart(tribeCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($tribeDistribution->pluck('name')) !!},
        datasets: [{
            data: {!! json_encode($tribeDistribution->pluck('count')) !!},
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush

<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}

.border-left-success {
    border-left: 4px solid #1cc88a !important;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

.text-xs {
    font-size: .7rem;
}

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}
</style>
