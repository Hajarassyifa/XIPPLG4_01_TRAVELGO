<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // Sementara pakai ini dulu (tanpa foreign key)
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('destinasi_id');
            // Hapus atau koment dulu yang ini:
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->foreignId('destinasi_id')->constrained('destinasi')->onDelete('cascade');
            
            $table->string('booking_code')->unique();
            $table->date('tanggal_berangkat');
            $table->integer('jumlah_tiket');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', ['pending', 'confirmed', 'paid', 'cancelled', 'completed'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'pending_verification', 'paid', 'failed'])->default('unpaid');
            $table->string('payment_method')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('special_requests')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};