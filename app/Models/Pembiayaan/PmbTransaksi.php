<?php

namespace App\Models\Pembiayaan;


use Illuminate\Database\Eloquent\Model;

class PmbTransaksi extends Model
{
    protected $table = 'pmb_transaksi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'transaksi_id', 'pmb_type', 'pmb_id', 'amount', 'tgl_bayar', 'method', 'type', 'staff_id'
    ];

    public function transaksi()
    {
        return $this->belongsTo(\Transaksi::class, 'transaksi_id');
    }

    // public function pmb_tunai()
    // {
    //     return $this->belongsTo(PmbTunai::class, 'pmb_tunai_id');
    // }

    public function pmb()
    {
        return $this->morphTo();
    }

}
