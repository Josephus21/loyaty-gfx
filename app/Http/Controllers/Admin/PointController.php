<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Point;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{

    public function adminPointIndex()
    {
        // Retrieve the sum of points for each member grouped by member_id
        $points = Point::select('member_id', DB::raw('ROUND(SUM(points), 5) as total_points'))
            ->groupBy('member_id')
            ->get();

        // Return the view with the points data
        return view('dashboard.pages.admin.point.index', compact('points'));
    }
    public function adminPointCreate(Request $request,)
    {
        if ($request->ajax()) {
            $searchQuery = $request->query('search');

            $member = Member::where('card_no', $searchQuery)
                ->orWhere('phone', $searchQuery)
                ->orWhere('email', $searchQuery)
                ->first();

            if ($member) {
                $points = Point::where('member_id', $member->id)->get();
                $total_points = 0;
                foreach($points as $point){
                    
                    $total_points = $total_points + $point->points;
                }
                $branch = $member->user ? $member->user->branch : null;


                return response()->json([
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'gender' => $member->gender,
                    'address' => $member->address,
                    'phone' => $member->phone,
                    'card_no' => $member->card_no,
                    'dob' => $member->dob,
                    'date' => $member->date,
                    'form_no' => $member->form_no,
                    'total_points' => $total_points,
                    'branch' => $branch,

                ]);
            } else {
                return response()->json(['error' => 'Member not found.'], 404);
            }
        }

        return view('dashboard.pages.admin.point.add');
    }


    public function adminPointStore(Request $request)
{
    $request->validate([
        'bill_no' => 'required|string',
        'bill_amount' => 'required|numeric',
        'points' => 'required|numeric',
        'member_id' => 'required|exists:members,id',
    ]);

    try {
        Point::create([
            'member_id' => $request->member_id,
            'bill_no' => $request->bill_no,
            'bill_amount' => $request->bill_amount,
            'points' => $request->points,
            'user_id' => Auth::id(), // ⭐ ADMIN WHO ADDED THE POINTS
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Point saved successfully!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error saving point: ' . $e->getMessage()
        ], 500);
    }
}




    public function adminPointEdit($id)
    {
        $point = Point::findOrFail($id);

        return view('dashboard.pages.admin.point.edit', compact('point'));
    }


    public function adminPointUpdate(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'member_id' => 'required|exists:members,id',
            'bill_no' => 'required|string',
            'bill_amount' => 'required|numeric',
            'points' => 'required|numeric',
        ]);

        $point = Point::findOrFail($id);

        $point->update([
            'user_id' => $request->user_id,
            'member_id' => $request->member_id,
            'bill_no' => $request->bill_no,
            'bill_amount' => $request->bill_amount,
            'points' => $request->points,
        ]);

        return redirect()->route('admin.point.index')->with('success', 'Point record updated successfully');
    }


    public function adminPointDelete($id)
    {
        $point = Point::findOrFail($id);

        $point->delete();

        return redirect()->route('admin.point.index')->with('success', 'Point record deleted successfully');
    }


    public function staffPointIndex()
    {
        // Retrieve the sum of points for each member grouped by member_id
        $points = Point::select('member_id', DB::raw('ROUND(SUM(points), 5) as total_points'))
            ->groupBy('member_id')
            ->get();

        // Return the view with the points data
        return view('dashboard.pages.admin.point.index', compact('points'));
    }

    public function staffPointCreate(Request $request,)
    {
        if ($request->ajax()) {
            $searchQuery = $request->query('search');

            $member = Member::where('card_no', $searchQuery)
                ->orWhere('phone', $searchQuery)
                ->orWhere('email', $searchQuery)
                ->first();

            if ($member) {
                $points = Point::where('member_id', $member->id)->get();
                $total_points = 0;
                foreach($points as $point){
                    
                    $total_points = $total_points + $point->points;
                }

                $branch = $member->user ? $member->user->branch : null;

                return response()->json([
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'gender' => $member->gender,
                    'address' => $member->address,
                    'phone' => $member->phone,
                    'card_no' => $member->card_no,
                    'dob' => $member->dob,
                    'date' => $member->date,
                    'form_no' => $member->form_no,
                    'total_points' => $total_points,
                    'branch' => $branch,
                ]);
            } else {
                return response()->json(['error' => 'Member not found.'], 404);
            }
        }

        return view('dashboard.pages.staff.point.add');
    }


    public function staffPointStore(Request $request)
    {
        $request->validate([
            'bill_no' => 'required|string',
            'bill_amount' => 'required|numeric',
            'points' => 'required|numeric',
            'member_id' => 'required|exists:members,id',
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            Point::create([
                'bill_no' => $request->bill_no,
                'bill_amount' => $request->bill_amount,
                'points' => $request->points,
                'member_id' => $request->member_id,
                'user_id' => $request->user_id,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Point saved successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error saving point: ' . $e->getMessage()]);
        }
    }


    public function staffPointEdit($id)
    {
        $point = Point::findOrFail($id);

        return view('dashboard.pages.admin.point.edit', compact('point'));
    }


    public function staffPointUpdate(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'member_id' => 'required|exists:members,id',
            'bill_no' => 'required|string',
            'bill_amount' => 'required|numeric',
            'points' => 'required|numeric',
        ]);

        $point = Point::findOrFail($id);

        $point->update([
            'user_id' => $request->user_id,
            'member_id' => $request->member_id,
            'bill_no' => $request->bill_no,
            'bill_amount' => $request->bill_amount,
            'points' => $request->points,
        ]);

        return redirect()->route('admin.point.index')->with('success', 'Point record updated successfully');
    }


    public function staffPointDelete($id)
    {
        $point = Point::findOrFail($id);

        $point->delete();

        return redirect()->route('admin.point.index')->with('success', 'Point record deleted successfully');
    }
}
