<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Member;
use App\Models\Point;

class SyncMemberPoints extends Command
{
    protected $signature = 'sync:memberpoints';
    protected $description = 'Sync member points from ERP API based on system_pk';

    public function handle()
    {
        $members = Member::whereNotNull('system_pk')->get();

        foreach ($members as $member) {

            $this->info("Fetching invoices for member: {$member->name}");

            $response = Http::withToken(env('ERP_TOKEN'))
                ->timeout(120)
                ->post('http://gsuite.graphicstar.com.ph/api/get_invoiced_job_orders', [
                    "searchKey" => "",
                    "searchKey" => $member->system_pk,
                    "filterdate" => [
                        "filter" => "period from",
                        "date1" => ["hide" => false, "date" => "Jan 01, 2025"],
                        "date2" => ["hide" => false, "date" => now()->format("M d, Y")],
                    ],
                    "office" => env('ERP_OFFICE_PK'),
                    "limit" => 500,
                    "offset" => 0
                ]);

            if (!$response->successful()) {
                $this->error("Failed fetching invoices for {$member->name}");
                continue;
            }

            $invoices = $response->json()['data'][0] ?? [];

            foreach ($invoices as $inv) {

                if (($inv['Name_Cust'] ?? '') !== $member->system_pk) {
                    continue;
                }

                $amount = floatval($inv['TotalAmountOut_LdgrInvty'] ?? 0);
                $invNo  = $inv['invc_upk'] ?? null;

                if (!$invNo || $amount <= 0) continue;

                $points = round($amount / 1000, 2);

                // ⭐ UPDATE OR CREATE
                Point::updateOrCreate(
                    ['bill_no' => $invNo],
                    [
                        'member_id' => $member->id,
                        'bill_amount' => $amount,
                        'points' => $points,
                        'user_id' => 1
                    ]
                );

                $this->info("✔ Invoice {$invNo} | Amount: ₱{$amount} | Points: {$points}");
            }
        }

        $this->info("Member points sync completed!");
    }
}
