<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SliderController extends Controller
{
    /**
     * Display a listing of sliders
     */
    public function index()
    {
        $sliders = Slider::orderBy('order', 'asc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new slider
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created slider
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|url',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $data = $request->except('image');
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('admin-assets/sliders');
                
                // Create directory if it doesn't exist
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $image->move($destinationPath, $filename);
                $data['image'] = 'admin-assets/sliders/' . $filename;
            }
            
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['order'] = $request->order ?? 0;
            
            Slider::create($data);
            
            return redirect()->route('admin.sliders.index')
                ->with('success', 'Slider created successfully!');
                
        } catch (\Exception $e) {
            Log::error('Slider creation error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create slider: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the slider
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified slider
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|url',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $data = $request->except('image');
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($slider->image && file_exists(public_path($slider->image))) {
                    unlink(public_path($slider->image));
                }
                
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('admin-assets/sliders');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $image->move($destinationPath, $filename);
                $data['image'] = 'admin-assets/sliders/' . $filename;
            }
            
            $data['is_active'] = $request->has('is_active') ? 1 : 0;
            $data['order'] = $request->order ?? $slider->order;
            
            $slider->update($data);
            
            return redirect()->route('admin.sliders.index')
                ->with('success', 'Slider updated successfully!');
                
        } catch (\Exception $e) {
            Log::error('Slider update error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to update slider: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified slider
     */
    public function destroy(Slider $slider)
    {
        try {
            // Delete image file
            if ($slider->image && file_exists(public_path($slider->image))) {
                unlink(public_path($slider->image));
            }
            
            $slider->delete();
            
            return redirect()->route('admin.sliders.index')
                ->with('success', 'Slider deleted successfully!');
                
        } catch (\Exception $e) {
            Log::error('Slider deletion error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete slider: ' . $e->getMessage());
        }
    }

    /**
     * Toggle slider active status
     */
    public function toggleStatus(Slider $slider)
    {
        try {
            $slider->is_active = !$slider->is_active;
            $slider->save();
            
            return response()->json([
                'success' => true,
                'is_active' => $slider->is_active,
                'message' => 'Slider status updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
