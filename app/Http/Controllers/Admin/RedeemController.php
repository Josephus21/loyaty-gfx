<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redeem;

class RedeemController extends Controller
{
    public function index()
    {
        $redemptions = Redeem::with(['member', 'reward'])->latest()->get();
        return view('dashboard.pages.admin.redeem.index', compact('redemptions'));
    }

    public function markRedeemed($id)
    {
        $redeem = Redeem::findOrFail($id);

        $redeem->update([
            'status' => 'redeemed'
        ]);

        return redirect()->back()->with('success', 'Reward marked as redeemed.');
    }
}
