@extends('layouts.app')

@section('title', 'My Kingdom - Strategy Game')

@section('content')
<div class="container">
    <div class="game-card">
        <div class="card-header">
            <h5 class="mb-0">Kingdom Overview - {{ $kingdom->name }}</h5>
        </div>
        <div class="card-body">
            <!-- Kingdom Basic Info -->
            <div class="row mb-4">
                <div class="col-md-4 text-center">
                    <div class="kingdom-avatar" style="font-size: 4rem;">
                        @switch($kingdom->tribe->name)
                            @case('Marksman') 🎯 @break
                            @case('Tank') 🛡️ @break
                            @case('Mage') 🔮 @break
                            @case('Warrior') ⚔️ @break
                            @default 🏰
                        @endswitch
                    </div>
                    <h4>{{ $kingdom->name }}</h4>
                    <div class="tribe-badge tribe-{{ strtolower($kingdom->tribe->name) }} mb-2">
                        {{ $kingdom->tribe->name }} Tribe
                    </div>
                    <p class="text-muted">Founded: {{ $kingdom->created_at->format('M j, Y') }}</p>
                </div>
                
                <div class="col-md-8">
                    <div class="stats-grid">
                        <div class="stat-card gold">
                            <div class="stat-number">{{ number_format($kingdom->gold) }}</div>
                            <div class="stat-label">Gold</div>
                        </div>
                        <div class="stat-card troops">
                            <div class="stat-number">{{ number_format($kingdom->total_troops) }}</div>
                            <div class="stat-label">Troops</div>
                        </div>
                        <div class="stat-card danger">
                            <div class="stat-number">{{ number_format($kingdom->total_attack_power) }}</div>
                            <div class="stat-label">Attack Power</div>
                        </div>
                        <div class="stat-card success">
                            <div class="stat-number">{{ number_format($kingdom->total_defense_power) }}</div>
                            <div class="stat-label">Defense Power</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tribe Attributes -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="game-card">
                        <div class="card-header">
                            <h6 class="mb-0">Tribe Attributes - {{ $kingdom->tribe->name }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Attack Attributes</h6>
                                    <div class="mb-2">
                                        <label>Melee Attack: {{ $kingdom->tribe->melee_attack }}</label>
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" style="width: {{ $kingdom->tribe->melee_attack / 10 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label>Range Attack: {{ $kingdom->tribe->range_attack }}</label>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" style="width: {{ $kingdom->tribe->range_attack / 10 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label>Magic Attack: {{ $kingdom->tribe->magic_attack }}</label>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" style="width: {{ $kingdom->tribe->magic_attack / 10 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Defense Attributes</h6>
                                    <div class="mb-2">
                                        <label>Melee Defense: {{ $kingdom->tribe->melee_defense }}</label>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: {{ $kingdom->tribe->melee_defense / 10 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label>Range Defense: {{ $kingdom->tribe->range_defense }}</label>
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" style="width: {{ $kingdom->tribe->range_defense / 10 }}%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label>Magic Defense: {{ $kingdom->tribe->magic_defense }}</label>
                                        <div class="progress">
                                            <div class="progress-bar bg-purple" style="width: {{ $kingdom->tribe->magic_defense / 10 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <strong>Tribe Description:</strong>
                                <p class="mb-0">{{ $kingdom->tribe->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Production Rates -->
            <div class="row">
                <div class="col-md-6">
                    <div class="game-card">
                        <div class="card-header">
                            <h6 class="mb-0">Production Rates</h6>
                        </div>
                        <div class="card-body">
                            <div class="production-item mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span>💰 Gold Production</span>
                                    <strong>{{ 5 + ($kingdom->mines_count * 10) }}/min</strong>
                                </div>
                                <div class="small text-muted">
                                    Base: 5/min + Mines: {{ $kingdom->mines_count }} × 10/min
                                </div>
                            </div>
                            
                            <div class="production-item mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span>⚔️ Troop Production</span>
                                    <strong>{{ $kingdom->tribe->troop_production_rate + ($kingdom->barracks_count * 5) }}/min</strong>
                                </div>
                                <div class="small text-muted">
                                    Base: {{ $kingdom->tribe->troop_production_rate }}/min + Barracks: {{ $kingdom->barracks_count }} × 5/min
                                </div>
                            </div>
                            
                            <div class="production-item">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span>🛡️ Defense Bonus</span>
                                    <strong>+{{ $kingdom->walls_count * 10 }}</strong>
                                </div>
                                <div class="small text-muted">
                                    Walls: {{ $kingdom->walls_count }} × 10 defense
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="game-card">
                        <div class="card-header">
                            <h6 class="mb-0">Building Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="building-summary-item d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                                <div>
                                    <span class="fw-bold">🏰 Main Building</span>
                                </div>
                                <span class="badge bg-primary">Level {{ $kingdom->main_building_level }}</span>
                            </div>
                            
                            <div class="building-summary-item d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                                <div>
                                    <span class="fw-bold">⚔️ Barracks</span>
                                    <div class="small text-muted">+5 troops/min each</div>
                                </div>
                                <span class="badge bg-success">{{ $kingdom->barracks_count }} built</span>
                            </div>
                            
                            <div class="building-summary-item d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                                <div>
                                    <span class="fw-bold">⛏️ Gold Mines</span>
                                    <div class="small text-muted">+10 gold/min each</div>
                                </div>
                                <span class="badge bg-warning">{{ $kingdom->mines_count }} built</span>
                            </div>
                            
                            <div class="building-summary-item d-flex justify-content-between align-items-center p-2 border rounded">
                                <div>
                                    <span class="fw-bold">🛡️ Defense Walls</span>
                                    <div class="small text-muted">+10 defense each</div>
                                </div>
                                <span class="badge bg-info">{{ $kingdom->walls_count }} built</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="game-card">
                        <div class="card-header">
                            <h6 class="mb-0">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('kingdom.buildings') }}" class="btn btn-game w-100">
                                        🏗️ Manage Buildings
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('game.battle') }}" class="btn btn-game danger w-100">
                                        ⚔️ Launch Attack
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('game.rankings') }}" class="btn btn-game success w-100">
                                        📊 View Rankings
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="{{ route('game.troops') }}" class="btn btn-game info w-100">
                                        🎖️ Manage Troops
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-purple {
    background-color: #6f42c1 !important;
}
</style>
@endsection