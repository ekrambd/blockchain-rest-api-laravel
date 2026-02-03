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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->string('wallet_name');
            $table->enum('existing_wallet_address', ['yes', 'no'])->nullable();
            $table->enum('import_by', ['private_key', 'mnemonic'])->nullable();
            $table->text('private_key');
            $table->text('mnemonic')->nullable();
            $table->enum('status', ['Active', 'Inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
