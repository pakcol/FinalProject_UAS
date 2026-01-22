<?php

namespace App\Http\Controllers;

use App\Models\Tribe;
use App\Models\Building;
use App\Models\Kingdom;
use App\Models\User;
use App\Models\Battle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $stats = [
            'total_users' => User::count(),
            'total_kingdoms' => Kingdom::count(),
            'total_battles' => Battle::count(),
            'active_tribes' => Tribe::where('is_active', true)->count()
        ];

        return view('admin.dashboard', compact('stats'));
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
            'gold_cost' => 'required|integer|min:0',
            'gold_production' => 'required|integer|min:0',
            'troop_production' => 'required|integer|min:0',
            'defense_bonus' => 'required|integer|min:0',
        ]);

        $building->update($validated);

        return redirect()->back()->with('success', 'Building updated successfully!');
    }
}