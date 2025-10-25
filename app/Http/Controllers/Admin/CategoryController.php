<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Display all categories
    public function index()
    {
        $category = Category::get();
        $categories = Category::latest()->paginate(10);

    $categoriesForJs = $categories->map(function($c, $i) {
        return [
            'id' => $c->id,
            'name' => $c->name,
            'image' => $c->image,
            'status' => $c->status,
            'index' => $i + 1,
        ];
    });

    return view('admin.categories.index', compact('categories','category', 'categoriesForJs'));
        
    }

    // Show create form
    public function create()
    {
        return view('admin.categories.create');
    }

    // Show edit form
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Store new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('admin-assets/categories'), $imageName);
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'image' => $imageName,
            'status' => $request->status == '1' ? true : false,
            'featured' => $request->featured == '1' ? true : false,
            'nav' => $request->nav == '1' ? true : false,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully.',
                'category' => $category
            ]);
        }

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    // Update existing category
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = $category->image;

        if ($request->hasFile('image')) {
            // Delete old image
            $oldImagePath = public_path('admin-assets/categories/' . $category->image);
            if ($category->image && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('admin-assets/categories'), $imageName);
        }

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'image' => $imageName,
            'status' => $request->status == '1' ? true : false,
            'featured' => $request->featured == '1' ? true : false,
            'nav' => $request->nav == '1' ? true : false,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
                'category' => $category
            ]);
        }

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    // Show category details
    public function show(Category $category)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        }
        
        return view('admin.categories.show', compact('category'));
    }

    // Delete category
    public function destroy(Category $category)
    {
        $imagePath = public_path('admin-assets/categories/' . $category->image);
        if ($category->image && file_exists($imagePath)) {
            unlink($imagePath);
        }

        $category->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.'
            ]);
        }
        
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}