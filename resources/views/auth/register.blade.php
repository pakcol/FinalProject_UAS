<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Browser Strategy Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/game.css') }}" rel="stylesheet">
</head>
<body class="auth-body">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card auth-card">
                    <div class="card-header text-center">
                        <h3 class="mb-0">Create Your Kingdom</h3>
                        <p class="mb-0">Begin your strategic conquest</p>
                    </div>
                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger alert-game">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            <!-- Personal Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Kingdom Information -->
                            <div class="mb-3">
                                <label for="kingdom_name" class="form-label">Kingdom Name</label>
                                <input type="text" class="form-control" id="kingdom_name" name="kingdom_name" value="{{ old('kingdom_name') }}" required>
                                <div class="form-text">Choose a unique name for your kingdom</div>
                            </div>

                            <!-- Tribe Selection -->
                            <div class="mb-4">
                                <label for="tribe_id" class="form-label">Choose Your Tribe</label>
                                <select class="form-select" id="tribe_id" name="tribe_id" required>
                                    <option value="">Select a tribe...</option>
                                    @foreach($tribes as $tribe)
                                        <option value="{{ $tribe->id }}" {{ old('tribe_id') == $tribe->id ? 'selected' : '' }}>
                                            {{ $tribe->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tribe Descriptions -->
                            <div class="tribe-descriptions mb-4">
                                @foreach($tribes as $tribe)
                                    <div class="tribe-info" id="tribe-info-{{ $tribe->id }}" style="display: none;">
                                        <div class="game-card">
                                            <div class="card-header">
                                                <h6 class="mb-0">{{ $tribe->name }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="mb-2">{{ $tribe->description }}</p>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <small><strong>Attack:</strong></small>
                                                        <div class="small">
                                                            Melee: {{ $tribe->melee_attack }} | 
                                                            Range: {{ $tribe->range_attack }} | 
                                                            Magic: {{ $tribe->magic_attack }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small><strong>Defense:</strong></small>
                                                        <div class="small">
                                                            Melee: {{ $tribe->melee_defense }} | 
                                                            Range: {{ $tribe->range_defense }} | 
                                                            Magic: {{ $tribe->magic_defense }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <small><strong>Troop Production:</strong> {{ $tribe->troop_production_rate }}/min</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-game btn-lg">
                                    Create Kingdom
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <p>Already have a kingdom? <a href="{{ route('login.form') }}" class="text-decoration-none">Login Here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show tribe description when selected
        document.getElementById('tribe_id').addEventListener('change', function() {
            // Hide all tribe info
            document.querySelectorAll('.tribe-info').forEach(function(el) {
                el.style.display = 'none';
            });
            
            // Show selected tribe info
            const selectedTribe = this.value;
            if (selectedTribe) {
                document.getElementById('tribe-info-' + selectedTribe).style.display = 'block';
            }
        });

        // Show tribe info if already selected (on page reload)
        const selectedTribe = document.getElementById('tribe_id').value;
        if (selectedTribe) {
            document.getElementById('tribe-info-' + selectedTribe).style.display = 'block';
        }
    </script>
</body>
</html>