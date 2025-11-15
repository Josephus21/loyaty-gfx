<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('redemptions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('member_id');
        $table->unsignedBigInteger('reward_id');
        $table->string('code'); // QR code key
        $table->enum('status', ['pending', 'redeemed'])->default('pending');
        $table->timestamps();

        $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        $table->foreign('reward_id')->references('id')->on('rewards')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redemptions');
    }
};
