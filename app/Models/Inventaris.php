<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    protected $fillable = [
        'jenis',
        'ras',
        'gender',
        'umur',
        'berat',
        'rekam_medis_general',
        'status_stok',
    ];

    public function produk()
    {
        return $this->hasOne(Produk::class, 'inventaris_id');
    }

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'inventaris_id');
    }

    public function pertumbuhan()
    {
        return $this->hasMany(PertumbuhanTernak::class, 'inventaris_id');
    }

    public static function lowStockCount()
    {
        return self::where('status_stok', 'Terbooking')->count();
    }
}
