<?php

namespace App\Http\Controllers;

use App\Models\Tribe;
use App\Models\Building;
use App\Models\Kingdom;
use App\Models\User;
use App\Models\Battle;
use App\Models\KingdomBuilding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Add admin check middleware here in production
    }

    public function tribes()
    {
        $tribes = Tribe::all();
        return view('admin.tribes', compact('tribes'));
    }

    public function dashboard()
    {
        // Basic Stats
        $stats = [
            'total_users' => User::count(),
            'total_kingdoms' => Kingdom::whereNotNull('user_id')->count(), // Exclude AI bots
            'total_buildings' => Building::count(),
            'active_buildings' => Building::where('is_active', 1)->count(),
            'total_battles' => Battle::where('type', '!=', 'training')->count(),
            'training_battles' => Battle::where('type', 'training')->count(),
        ];

        // Tribe Distribution
        $tribeDistributionData = Kingdom::whereNotNull('user_id')
            ->select('tribe_id', DB::raw('count(*) as total'))
            ->groupBy('tribe_id')
            ->with('tribe')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->tribe->name,
                    'count' => $item->total
                ];
            });
        
        // Convert to collection
        $tribeDistribution = collect($tribeDistributionData);

        // User Registration Trend (Last 7 days)
        $registrationTrendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = User::whereDate('created_at', $date->format('Y-m-d'))->count();
            $registrationTrendData[] = [
                'date' => $date->format('M d'),
                'count' => $count
            ];
        }
        
        // Convert to collection
        $registrationTrend = collect($registrationTrendData);

        // Recent Activities
        $recentBattles = Battle::with(['attacker', 'defender'])
            ->where('type', '!=', 'training')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::with('kingdom')
            ->latest()
            ->take(5)
            ->get();

        $recentBuildings = KingdomBuilding::with(['kingdom', 'building'])
            ->latest()
            ->take(5)
            ->get();

        // Top Players by Power
        $topPlayers = Kingdom::with(['user', 'tribe'])
            ->whereNotNull('user_id')
            ->orderBy('total_attack_power', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'tribeDistribution',
            'registrationTrend',
            'recentBattles',
            'recentUsers',
            'recentBuildings',
            'topPlayers'
        ));
    }

    public function tribeSettings()
    {
        $tribes = Tribe::all();
        return view('admin.tribe-settings', compact('tribes'));
    }

    public function updateTribe(Request $request, $id)
    {
        $request->validate([
            'description'     => 'required|string',
            'melee_attack'    => 'required|integer|min:0',
            'range_attack'    => 'required|integer|min:0',
            'magic_attack'    => 'required|integer|min:0',
            'melee_defense'   => 'required|integer|min:0',
            'range_defense'   => 'required|integer|min:0',
            'magic_defense'   => 'required|integer|min:0',
        ]);

        $tribe = Tribe::findOrFail($id);

        $tribe->update($request->all());

        return back()->with('success', 'Tribe updated successfully.');
    }

    public function buildingSettings()
    {
        $buildings = Building::all();
        return view('admin.building-settings', compact('buildings'));
    }

    public function updateBuilding(Request $request, $id)
    {
        $building = Building::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:main,barracks,mine,walls,other',
            'description' => 'required|string',
            'gold_cost' => 'required|integer|min:0',
            'level' => 'required|integer|min:1',
            'gold_production' => 'nullable|integer|min:0',
            'troop_production' => 'nullable|integer|min:0',
            'defense_bonus' => 'nullable|integer|min:0',
        ]);

        // Handle checkbox (is_active)
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // Update building
        $building->update($validated);

        return redirect()->route('admin.buildings.index')
            ->with('success', 'Building "' . $building->name . '" updated successfully!');
    }
}
