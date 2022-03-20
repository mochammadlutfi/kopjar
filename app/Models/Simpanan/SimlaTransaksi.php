<?php

namespace App\Models\Simpanan;

use Illuminate\Database\Eloquent\Model;

class SimlaTransaksi extends Model
{
    protected $table = 'simla_transaksi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id','no_transaksi', 'anggota_id', 'tujuan', 'type', 'amount'
    ];

    
    protected $casts = [
        'amount' => 'float',
    ];

    public function transaksi()
    {
        return $this->belongsTo(\Transaksi::class, 'no_transaksi', 'no_transaksi');
    }

    public function anggota()
    {
        return $this->belongsTo(\Anggota::class, 'anggota_id', 'anggota_id');
    }
}
