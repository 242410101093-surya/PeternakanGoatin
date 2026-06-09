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
        // 1. Modify LaporanKeuangans Table:
        Schema::table('laporan_keuangans', function (Blueprint $table) {
            $table->string('jenis_transaksi')->change();
            $table->foreignId('pesanan_id')->nullable()->after('jumlah')->constrained('pesanans')->onDelete('set null');
        });

        // 2. Modify Pesanans Table:
        Schema::table('pesanans', function (Blueprint $table) {
            $table->string('status')->default('Pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_keuangans', function (Blueprint $table) {
            $table->dropForeign(['pesanan_id']);
            $table->dropColumn('pesanan_id');
        });
    }
};
