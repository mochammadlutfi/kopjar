<?php

namespace App\Models\Simpanan;


use Illuminate\Database\Eloquent\Model;

class SimkopTransaksi extends Model
{
    protected $table = 'simkop_transaksi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id','no_transaksi', 'anggota_id', 'debit', 'credit', 'next_payment'
    ];

    
    protected $casts = [
        'debit' => 'float',
        'credit' => 'float',
    ];

    public function transaksi()
    {
        return $this->belongsTo(\Transaksi::class, 'no_transaksi');
    }

    public function anggota()
    {
        return $this->belongsTo(\Anggota::class, 'anggota_id', 'anggota_id');
    }
}
