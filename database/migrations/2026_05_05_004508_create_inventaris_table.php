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
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('jenis'); // e.g. Kambing, Sapi
            $table->string('ras')->nullable(); // e.g. Etawa, Boer
            $table->enum('gender', ['Jantan', 'Betina']);
            $table->integer('umur'); // in months
            $table->decimal('berat', 8, 2); // in kg
            $table->text('rekam_medis_general')->nullable();
            $table->enum('status_stok', ['Tersedia', 'Terbooking', 'Terjual', 'Dalam Perawatan'])->default('Tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
