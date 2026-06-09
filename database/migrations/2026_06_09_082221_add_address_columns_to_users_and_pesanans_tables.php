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
        Schema::table('users', function (Blueprint $table) {
            $table->text('alamat')->nullable();
            $table->string('tipe_alamat')->nullable(); // 'Rumah' or 'Kantor'
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
        });

        Schema::table('pesanans', function (Blueprint $table) {
            $table->text('alamat')->nullable();
            $table->string('tipe_alamat')->nullable(); // 'Rumah' or 'Kantor'
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'tipe_alamat', 'latitude', 'longitude']);
        });

        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'tipe_alamat', 'latitude', 'longitude']);
        });
    }
};
