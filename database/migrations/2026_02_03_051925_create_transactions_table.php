<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('wallet_id');
            $table->integer('contract_id');
            $table->string('sender_address');
            $table->string('receiver_address');
            $table->string('transaction_hash')->unique();
            $table->date('date');
            $table->string('time');
            $table->string('month');
            $table->string('year');
            $table->string('timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
