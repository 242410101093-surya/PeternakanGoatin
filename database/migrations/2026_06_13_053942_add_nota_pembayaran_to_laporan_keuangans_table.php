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
        Schema::table('laporan_keuangans', function (Blueprint $table) {
            $table->string('nota_pembayaran')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_keuangans', function (Blueprint $table) {
            $table->dropColumn('nota_pembayaran');
        });
    }
};
