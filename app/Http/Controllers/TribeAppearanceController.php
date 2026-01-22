<?php

namespace App\Http\Controllers;

use App\Models\TribeAppearancePart;
use App\Models\Tribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TribeAppearanceController extends Controller
{
    public function index(Request $request)
    {
        $query = TribeAppearancePart::with('tribe');

        // Filter by tribe
        if ($request->filled('tribe_id')) {
            $query->where('tribe_id', $request->tribe_id);
        }

        // Filter by part type
        if ($request->filled('part_type')) {
            $query->where('part_type', $request->part_type);
        }

        $parts = $query->orderBy('tribe_id')->orderBy('part_type')->orderBy('display_order')->paginate(20);
        $tribes = Tribe::all();
        $partTypes = ['head', 'body', 'legs', 'arms'];

        return view('admin.appearance.index', compact('parts', 'tribes', 'partTypes'));
    }

    public function create()
    {
        $tribes = Tribe::all();
        $partTypes = ['head', 'body', 'legs', 'arms'];
        
        return view('admin.appearance.create', compact('tribes', 'partTypes'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tribe_id' => 'required|exists:tribes,id',
                'part_type' => 'required|in:head,body,legs,arms',
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'display_order' => 'nullable|integer|min:0',
                'description' => 'nullable|string',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('appearance_parts', $filename, 'public');
                $validated['image_url'] = $path;
            }

            // If this is set as default, unset other defaults for same tribe and type
            if ($request->has('is_default') && $request->is_default) {
                TribeAppearancePart::where('tribe_id', $validated['tribe_id'])
                    ->where('part_type', $validated['part_type'])
                    ->update(['is_default' => false]);
            }

            $validated['is_default'] = $request->has('is_default') ? 1 : 0;
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            $validated['display_order'] = $validated['display_order'] ?? 0;

            TribeAppearancePart::create($validated);

            Log::info('Appearance Part Created', ['name' => $validated['name']]);

            return redirect()->route('admin.appearance.index')
                ->with('success', 'Appearance part "' . $validated['name'] . '" created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Failed to create appearance part: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to create appearance part: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(TribeAppearancePart $appearance)
    {
        $tribes = Tribe::all();
        $partTypes = ['head', 'body', 'legs', 'arms'];
        
        return view('admin.appearance.edit', compact('appearance', 'tribes', 'partTypes'));
    }

    public function update(Request $request, TribeAppearancePart $appearance)
    {
        try {
            $validated = $request->validate([
                'tribe_id' => 'required|exists:tribes,id',
                'part_type' => 'required|in:head,body,legs,arms',
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'display_order' => 'nullable|integer|min:0',
                'description' => 'nullable|string',
            ]);

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($appearance->image_url && Storage::disk('public')->exists($appearance->image_url)) {
                    Storage::disk('public')->delete($appearance->image_url);
                }

                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('appearance_parts', $filename, 'public');
                $validated['image_url'] = $path;
            }

            // If this is set as default, unset other defaults
            if ($request->has('is_default') && $request->is_default) {
                TribeAppearancePart::where('tribe_id', $validated['tribe_id'])
                    ->where('part_type', $validated['part_type'])
                    ->where('id', '!=', $appearance->id)
                    ->update(['is_default' => false]);
            }

            $validated['is_default'] = $request->has('is_default') ? 1 : 0;
            $validated['is_active'] = $request->has('is_active') ? 1 : 0;
            $validated['display_order'] = $validated['display_order'] ?? $appearance->display_order;

            $appearance->update($validated);

            Log::info('Appearance Part Updated', ['id' => $appearance->id]);

            return redirect()->route('admin.appearance.index')
                ->with('success', 'Appearance part "' . $appearance->name . '" updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Failed to update appearance part: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to update appearance part: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(TribeAppearancePart $appearance)
    {
        try {
            $name = $appearance->name;

            // Delete image file
            if ($appearance->image_url && Storage::disk('public')->exists($appearance->image_url)) {
                Storage::disk('public')->delete($appearance->image_url);
            }

            $appearance->delete();

            Log::info('Appearance Part Deleted', ['name' => $name]);

            return redirect()->route('admin.appearance.index')
                ->with('success', 'Appearance part "' . $name . '" deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to delete appearance part: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete appearance part: ' . $e->getMessage());
        }
    }

    public function toggleActive(TribeAppearancePart $appearance)
    {
        try {
            $appearance->update(['is_active' => !$appearance->is_active]);

            $status = $appearance->is_active ? 'activated' : 'deactivated';

            Log::info('Appearance Part Status Toggled', [
                'id' => $appearance->id,
                'status' => $status
            ]);

            return redirect()->back()
                ->with('success', 'Appearance part "' . $appearance->name . '" ' . $status . ' successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to toggle appearance part status.');
        }
    }

    public function setDefault(TribeAppearancePart $appearance)
    {
        try {
            // Unset other defaults for same tribe and type
            TribeAppearancePart::where('tribe_id', $appearance->tribe_id)
                ->where('part_type', $appearance->part_type)
                ->where('id', '!=', $appearance->id)
                ->update(['is_default' => false]);

            $appearance->update(['is_default' => true]);

            Log::info('Default Appearance Part Set', [
                'id' => $appearance->id,
                'tribe' => $appearance->tribe->name,
                'part_type' => $appearance->part_type
            ]);

            return redirect()->back()
                ->with('success', 'Default appearance part set successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to set default appearance part.');
        }
    }
}
