<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seller_reviews', function (Blueprint $table) {
            $table->id();

            // siapa yang direview (penjual)
            $table->foreignId('seller_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // siapa yang memberi review (pembeli)
            $table->foreignId('buyer_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // validasi dari transaksi
            $table->foreignId('transaction_id')
                  ->constrained('transactions')
                  ->cascadeOnDelete();

            // rating & komentar
            $table->unsignedTinyInteger('rating'); // 1–5
            $table->text('comment')->nullable();

            $table->timestamps();

            // ⛔ cegah double review per transaksi
            $table->unique('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_reviews');
    }
};
