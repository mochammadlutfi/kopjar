<?php

namespace App\Models\Pembiayaan;


use Illuminate\Database\Eloquent\Model;
use App\Models\Anggota;
class PmbTunai extends Model
{
    protected $table = 'pmb_tunai';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'anggota_id', 'durasi', 'jumlah', 'bagi_hasil', 'angsuran_pokok', 'angsuran_total', 'admin_fee',  'status'
    ];

    protected $casts = [
        'jumlah' => 'float',
        'bagi_hasil' => 'float',
        'angsuran_pokok' => 'float',
        'angsuran_total' => 'float',
        'admin_fee' => 'float',
    ];

    public function anggota()
    {
        return $this->belongsTo('App\Models\Anggota', 'anggota_id', 'anggota_id');
    }

    public function transaksi()
    {
        return $this->morphOne(PmbTransaksi::class, 'pmb');
    }

    public function line()
    {
        return $this->hasMany(PmbTunaiLine::class, 'pmb_tunai_id');
    }

}
