<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $images = Gallery::latest()->get();
        return view('dashboard.pages.admin.gallery.index', compact('images'));
    }

    public function create()
    {
        return view('dashboard.pages.admin.gallery.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'image'       => 'required|image|mimes:jpg,png,jpeg|max:4096',
        'title'       => 'nullable|string|max:255',
        'description' => 'nullable|string'
    ]);

    $file     = $request->file('image');
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->move(public_path('uploads/gallery'), $filename);

    Gallery::create([
        'image'       => $filename,
        'title'       => $request->title,
        'description' => $request->description
    ]);

    return redirect()->route('admin.gallery.index')
        ->with('success', 'Image uploaded successfully.');
}

    public function destroy($id)
    {
        $image = Gallery::findOrFail($id);

        $path = public_path('uploads/gallery/' . $image->image);
        if (file_exists($path)) unlink($path);

        $image->delete();

        return back()->with('success', 'Image deleted.');
    }
}
