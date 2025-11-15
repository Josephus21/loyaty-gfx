<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reward;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::all();
        return view('dashboard.pages.admin.rewards.index', compact('rewards'));
    }

    public function create()
    {
        return view('dashboard.pages.admin.rewards.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required',
            'description' => 'required',
            'points_required' => 'required|numeric',
            'image'  => 'required|image'
        ]);

        // auto-create folder if missing
        if (!file_exists(public_path('uploads/rewards'))) {
            mkdir(public_path('uploads/rewards'), 0777, true);
        }

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads/rewards'), $imageName);

        Reward::create([
            'name' => $request->name,
            'description' => $request->description,
            'points_required' => $request->points_required,
            'image' => $imageName,
        ]);

        return redirect('/admin/rewards')->with('success', 'Reward added successfully!');

    }
public function edit($id)
{
    $reward = Reward::findOrFail($id);
    return view('dashboard.pages.admin.rewards.edit', compact('reward'));
}

public function update(Request $request, $id)
{
    $reward = Reward::findOrFail($id);

    $reward->name = $request->name;
    $reward->points_required = $request->points_required;

    if ($request->hasFile('image')) {
        $imageName = time().'_'.$request->image->getClientOriginalName();
        $request->image->move(public_path('uploads/rewards'), $imageName);
        $reward->image = $imageName;
    }

    $reward->save();

    return redirect()->route('admin-rewards-index')
        ->with('success', 'Reward updated successfully');
}


    public function destroy($id)
{
    $reward = Reward::findOrFail($id);

    // delete image from folder
    $imagePath = public_path('uploads/rewards/'.$reward->image);
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    $reward->delete();

    return redirect()->route('admin-rewards-index')
        ->with('success', 'Reward deleted successfully');
}

}
