<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class GenerateMemberQRCodes extends Command
{
    protected $signature = 'members:generate-qrcodes';
    protected $description = 'Generate QR codes for all members';

    public function handle()
    {
        $this->info("Generating QR codes...");

        $members = Member::all();

        $directory = public_path('uploads/qrcodes');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        foreach ($members as $member) {

            $fileName = 'member_qr_' . $member->id . '.png';

            $qrURL = url('/member/q/' . $member->id);

            // v6.x QR creation
            $qr = QrCode::create($qrURL)
                        ->setSize(300)
                        ->setMargin(10);

            $writer = new PngWriter();
            $result = $writer->write($qr);

            $path = $directory . '/' . $fileName;
            $result->saveToFile($path);

            // Save filename to DB
            $member->qr_code = $fileName;
            $member->save();

            $this->info("✔ QR generated for {$member->name}");
        }

        $this->info("All QR codes generated successfully!");
    }
}
