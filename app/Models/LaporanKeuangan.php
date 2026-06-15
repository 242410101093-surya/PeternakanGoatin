<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
    protected $fillable = [
        'tanggal',
        'jenis_transaksi',
        'jumlah',
        'keterangan',
        'pesanan_id',
        'nota_pembayaran',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
