<?php

namespace App\Http\Controllers;
use App\Models\gallery;
use Exception;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = gallery::latest()->paginate(10);
        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            $gallery = new gallery();
            $gallery->title = $request->title;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('galleries', $filename, 'public');
                // $image->move(public_path('galleries'), $filename);
                $gallery->image_path = 'galleries/' . $filename;
            }

            $gallery->save();

            return redirect()->route('Admin.gallery.index')->with('success', 'Gallery created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function edit($id)
    {
        $gallery = gallery::findOrFail($id);
        return view('admin.gallery.edit', compact('gallery'));
    }
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);

            $gallery = gallery::findOrFail($id);
            $gallery->title = $request->title;

            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($gallery->image_path) {
                    if (file_exists($gallery->image_path)) {
                        unlink($gallery->image_path);
                    }
                }
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('galleries', $filename, 'public');
                // $image->move(public_path('galleries'), $filename);
                $gallery->image_path = 'galleries/' . $filename;
            }

            $gallery->save();

            return redirect()->route('Admin.gallery.index')->with('success', 'Gallery updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function delete($id)
    {
        try {
            $gallery = gallery::findOrFail($id);
            // Delete the image file if it exists
            if ($gallery->image_path) {
                if (file_exists($gallery->image_path)) {
                    unlink($gallery->image_path);
                }
            }
            $gallery->delete();
            return response()->json(['success' => true, 'message' => 'Gallery deleted successfully.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    public function show(){
        $galleries = gallery::all()->sortDesc();
        return view('User.gallery.show', compact('galleries'));
    }
}
