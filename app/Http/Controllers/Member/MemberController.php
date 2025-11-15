<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Reward;
use App\Models\Gallery;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Point;
use App\Models\Redeem;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;



class MemberController extends Controller
{
    // ================= STAFF SIDE =================

    public function memberIndex()
    {
        $members = Member::with('user')
            ->where('user_id', Auth::id())
            ->get();

        return view('dashboard.pages.staff.member.index', compact('members'));
    }

    public function memberCreate()
    {
        return view('dashboard.pages.staff.member.add');
    }

    public function memberStore(Request $request)
    {
        $request->validate([
            'form_no' => 'required',
            'card_no' => 'required|unique:members,card_no',
            'date' => 'required|date',
            'name' => 'required|string',
            'gender' => 'required',
            'email' => 'required|email|unique:members,email',
            'address' => 'required|string',
            'dob' => 'required|date',
            'phone' => 'required|unique:members,phone'
        ]);

        try {
            Member::create([
                'form_no' => $request->form_no,
                'card_no' => $request->card_no,
                'date' => $request->date,
                'name' => $request->name,
                'gender' => $request->gender,
                'email' => $request->email,
                'address' => $request->address,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('members-index')->with('success', 'Member created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create member: ' . $e->getMessage());
        }
    }

    public function memberEdit($id)
    {
        $member = Member::findOrFail($id);
        return view('dashboard.pages.staff.member.edit', compact('member'));
    }

    public function memberUpdate(Request $request, $id)
    {
        $request->validate([
            'form_no' => 'required',
            'card_no' => 'required|unique:members,card_no,' . $id,
            'date' => 'required|date',
            'name' => 'required|string',
            'gender' => 'required',
            'email' => 'required|email|unique:members,email,' . $id,
            'address' => 'required|string',
            'dob' => 'required|date',
            'phone' => 'required|unique:members,phone,' . $id
        ]);

        try {
            $member = Member::findOrFail($id);
            $member->update($request->all());

            return redirect()->route('members-index')->with('success', 'Member updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update member: ' . $e->getMessage());
        }
    }


    // ================= ADMIN SIDE =================

    public function adminMemberIndex()
    {
        $members = Member::with('user')->get();
        return view('dashboard.pages.admin.member.index', compact('members'));
    }

    public function adminMemberCreate()
    {
        return view('dashboard.pages.admin.member.add');
    }

  public function adminMemberStore(Request $request)
{
    $request->validate([
        'form_no'   => 'required',
        'card_no'   => 'required|unique:members,card_no',
        'date'      => 'required|date',
        'name'      => 'required|string',
        'gender'    => 'required',
        'email'     => 'required|email|unique:members,email|unique:users,email',
        'address'   => 'required|string',
        'dob'       => 'required|date',
        'phone'     => 'required|unique:members,phone',
        'system_pk' => 'required|string',
        'password'  => 'required|min:6|confirmed'
    ]);

    try {
        // Create USER
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => \Hash::make($request->password),
            'branch'   => 'N/A',
            'role'     => 'member',
        ]);

        // Create MEMBER
        $member = Member::create([
            'form_no'   => $request->form_no,
            'card_no'   => $request->card_no,
            'date'      => $request->date,
            'name'      => $request->name,
            'gender'    => $request->gender,
            'email'     => $request->email,
            'address'   => $request->address,
            'dob'       => $request->dob,
            'phone'     => $request->phone,
            'system_pk' => $request->system_pk,
            'user_id'   => $user->id,
        ]);

        // ⭐ Generate QR code for this member
        $qrURL    = url('/member/q/' . $member->id);
        $fileName = 'member_qr_' . $member->id . '.png';
        $qrPath   = public_path('uploads/qrcodes');

        if (!file_exists($qrPath)) {
            mkdir($qrPath, 0777, true);
        }

        $qr = (new Builder(
            writer: new PngWriter(),
            data: $qrURL,
            size: 300,
            margin: 10
        ))->build();

        $qr->saveToFile($qrPath . '/' . $fileName);

        $member->qr_code = $fileName;
        $member->save();

        return redirect()->route('admin-members-index')
            ->with('success', 'Member created successfully with QR Code.');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to create member: ' . $e->getMessage());
    }
}


    // ⭐ QR CODE SCAN INFO
    public function qrInfo($id)
    {
        $member = Member::findOrFail($id);

        return response()->json([
            'member' => $member->name,
            'available_points' => $member->points()->sum('points')
        ]);
    }


    // ================= MEMBER DASHBOARD =================

   public function memberDashboard()
{
    $user = Auth::user();
    $member = Member::where('user_id', $user->id)->first();

    $totalPoints = $member ? $member->points()->sum('points') : 0;

    $pointHistory = $member
        ? $member->points()->orderBy('created_at', 'desc')->paginate(5)
        : collect();

    $rewards = Reward::all();

    // ⭐ Fetch gallery images
    $galleryImages = Gallery::all();

    return view('dashboard.pages.member.dashboard', compact(
        'user', 'member', 'totalPoints', 'rewards',
        'pointHistory', 'galleryImages'   // include here
    ));
}

    public function memberRewards()
{
    $user = Auth::user();
    $member = Member::where('user_id', $user->id)->first();

    $totalPoints = $member ? $member->points()->sum('points') : 0;
    $rewards = Reward::all();

    // 🔥 All rewards the member has redeemed (pending OR redeemed)
    $redeemedStatuses = Redeem::where('member_id', $member->id)
                              ->pluck('status', 'reward_id')
                              ->toArray();

    return view('dashboard.pages.member.rewards', compact(
        'rewards',
        'totalPoints',
        'redeemedStatuses'
    ));
}



 public function redeemReward($id)
{
    $user = Auth::user();
    $member = Member::where('user_id', $user->id)->first();
    $reward = Reward::findOrFail($id);

    $totalPoints = $member->points()->sum('points');

    if ($totalPoints < $reward->points_required) {
        return back()->with('error', 'Not enough points.');
    }

    // Deduct points
    Point::create([
        'bill_no'     => 'REDEEM-' . $reward->id,
        'bill_amount' => 0,
        'points'      => -$reward->points_required,
        'member_id'   => $member->id,
        'user_id'     => $user->id
    ]);

    // Generate redeem code
    $code = uniqid('REWARD-');

    // Save redeem action
    $redeem = Redeem::create([
        'member_id' => $member->id,
        'reward_id' => $reward->id,
        'code'      => $code,
        'status'    => 'pending'
    ]);


    // ⭐ Generate QR code using Endroid
  $qr = (new Builder(
    writer: new PngWriter(),
    data: $code,
    size: 200,
    margin: 10
))->build();

$qrBase64 = $qr->getDataUri();

    return view('dashboard.pages.member.redeem', [
        'reward' => $reward,
        'qrcode' => $qrBase64,
        'code'   => $code,
        'redeem' => $redeem
    ]);
}

public function adminMemberEdit($id)
{
    $member = Member::findOrFail($id);
    return view('dashboard.pages.admin.member.edit', compact('member'));
}

public function adminMemberUpdate(Request $request, $id)
{
    $request->validate([
        'form_no' => 'required',
        'card_no' => 'required|unique:members,card_no,' . $id,
        'date' => 'required|date',
        'name' => 'required|string',
        'gender' => 'required',
        'email' => 'required|email|unique:members,email,' . $id,
        'address' => 'required|string',
        'dob' => 'required|date',
        'phone' => 'required|unique:members,phone,' . $id,
        'system_pk' => 'required|string'
    ]);

    $member = Member::findOrFail($id);
    $member->update($request->all());

    return redirect()->route('admin-members-index')
        ->with('success', 'Member updated successfully.');
}



    public function adminMemberDestroy($id)
    {
        Member::findOrFail($id)->delete();
        return redirect()->route('admin-members-index')
            ->with('success', 'Member deleted successfully.');
    }
}
