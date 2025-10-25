<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slogan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SloganController extends Controller
{
    /**
     * Display a listing of slogans
     */
    public function index()
    {
        $slogans = Slogan::orderBy('order', 'asc')->get();
        return view('admin.slogans.index', compact('slogans'));
    }

    /**
     * Show the form for creating a new slogan
     */
    public function create()
    {
        return view('admin.slogans.create');
    }

    /**
     * Store a newly created slogan
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $data = $request->only(['text', 'order', 'is_active']);
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['order'] = $request->order ?? 0;
            
            Slogan::create($data);
            
            // Clear cache
            Cache::flush();
            \Artisan::call('view:clear');
            
            return redirect()->route('admin.slogans.index')
                ->with('success', 'Slogan created successfully!');
                
        } catch (\Exception $e) {
            Log::error('Slogan creation error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create slogan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the slogan
     */
    public function edit(Slogan $slogan)
    {
        return view('admin.slogans.edit', compact('slogan'));
    }

    /**
     * Update the specified slogan
     */
    public function update(Request $request, Slogan $slogan)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $data = $request->only(['text', 'order', 'is_active']);
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['order'] = $request->order ?? $slogan->order;
            
            $slogan->update($data);
            
            // Clear cache
            Cache::flush();
            \Artisan::call('view:clear');
            
            return redirect()->route('admin.slogans.index')
                ->with('success', 'Slogan updated successfully!');
                
        } catch (\Exception $e) {
            Log::error('Slogan update error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to update slogan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified slogan
     */
    public function destroy(Slogan $slogan)
    {
        try {
            $slogan->delete();
            
            // Clear cache
            Cache::flush();
            \Artisan::call('view:clear');
            
            return redirect()->route('admin.slogans.index')
                ->with('success', 'Slogan deleted successfully!');
                
        } catch (\Exception $e) {
            Log::error('Slogan deletion error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete slogan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle slogan active status
     */
    public function toggleStatus(Slogan $slogan)
    {
        try {
            $slogan->is_active = !$slogan->is_active;
            $slogan->save();
            
            // Clear cache
            Cache::flush();
            
            return response()->json([
                'success' => true,
                'is_active' => $slogan->is_active,
                'message' => 'Slogan status updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
