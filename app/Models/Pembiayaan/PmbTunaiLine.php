<?php

namespace App\Models\Pembiayaan;


use Illuminate\Database\Eloquent\Model;

class PmbTunaiLine extends Model
{
    protected $table = 'pmb_tunai_line';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'pmb_tunai_id', 'angsuran_ke', 'amount', 'tgl_tempo', 'status',
    ];

    protected $cast = [
        'amount' => 'float',
    ];

    // public function transaksi()d
    // {
    //     return $this->belongsTo(\Transaksi::class, 'transaksi_id');
    // }

    public function pmb_tunai()
    {
        return $this->belongsTo(PmbTunai::class, 'pmb_tunai_id');
    }

    public function transaksi()
    {
        return $this->morphOne(PmbTransaksi::class, 'pmb');
    }

}
