<?php

namespace App\Jobs;

use App\Models\Member;
use App\Models\Point;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncInvoicesJob extends Job
{
    public function handle()
    {
        $members = Member::whereNotNull('system_pk')->get();

        foreach ($members as $member) {
            $this->syncMemberInvoices($member);
        }
    }

    private function syncMemberInvoices($member)
    {
        $response = Http::withToken(env('ERP_TOKEN'))
            ->post('http://gsuite.graphicstar.com.ph/api/get_invoiced_job_orders', [
                'searchKey' => "",
                'customer'  => $member->system_pk,
                'limit'     => 100,
            ]);

        if (!$response->ok()) {
            Log::error("ERP API failed for " . $member->system_pk);
            return;
        }

        $data = collect($response->json()['data'][0] ?? []);

        foreach ($data as $invoice) {
            $invoiceNo = $invoice['invc_upk'];
            $amount = (float) $invoice['TotalAmountOut_LdgrInvty'];

            // Skip if already added
            if (Point::where('bill_no', $invoiceNo)->exists()) continue;

            // Compute points
            $points = $amount / 1000;

            // Add point record
            Point::create([
                'bill_no'     => $invoiceNo,
                'bill_amount' => $amount,
                'points'      => $points,
                'member_id'   => $member->id,
                'user_id'     => 1, // SYSTEM user (or null)
            ]);
        }
    }
}
