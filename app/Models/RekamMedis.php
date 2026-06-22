<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    protected $table = 'rekam_medis';

    protected $fillable = [
        'inventaris_id',
        'tanggal',
        'dokter_hewan',
        'diagnosa',
        'tindakan',
        'status',
    ];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function produk()
    {
        return $this->hasOneThrough(
            Produk::class,
            Inventaris::class,
            'id', // Foreign key on inventaris table
            'inventaris_id', // Foreign key on produks table
            'inventaris_id', // Local key on rekam_medis table
            'id' // Local key on inventaris table
        );
    }
}
