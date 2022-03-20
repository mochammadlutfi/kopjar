<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'no_transaksi', 'anggota_id', 'teller_id', 'jenis', 'item', 'total'
    ];

    protected $appends = [
        'darike', 'jenis_transaksi'
    ];

    
    protected $casts = [
        'total' => 'float',
    ];

    public function anggota(){
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function teller()
    {
        return $this->belongsTo(Admin::class, 'teller_id', 'id');
    }

    public function simkop()
    {
        return $this->hasMany(Simpanan\SimkopTransaksi::class);
    }

    public function simla()
    {
        return $this->hasOne(Simpanan\SimlaTransaksi::class);
    }

    public function payment()
    {
        return $this->morphOne(Accounting\Payment::class, 'paymenttable');
    }

    public function line()
    {
        return $this->hasMany(TransaksiLine::class);
    }

    public function getJenisTransaksiAttribute($value)
    {
        if($this->jenis == 'setoran wajib'){
            return 'Simpanan Wajib';
        }elseif($this->jenis == 'penarikan sukarela'){
            return 'Penarikan Simpanan Sukarela';
        }elseif($this->jenis == 'pendaftaran'){
            return 'Pendaftaran';
        }else if($this->jenis == 'setoran sukarela'){
            return 'Isi Saldo Simpanan Sukarela';
        }else if($this->jenis == 'transfer sukarela'){
            return 'Transfer';
        }else if($this->jenis == 'pembelian'){
            if($this->sub_service == 'pulsa'){
                return 'Pembelian Pulsa';
            }elseif($this->sub_service == 'data'){
                return 'Pembelian Data';
            }
        }else{
            return '';
        }
    }

    public function getDarikeAttribute($value)
    {
        if($this->jenis == 'setoran wajib' || $this->jenis == 'setoran sukarela'){
            return 'Koperasi BUMABA';
        }elseif($this->jenis == 'penarikan sukarela'){
            return 'Koperasi BUMABA';
        }elseif($this->jenis == 'pendaftaran'){
            return 'Koperasi BUMABA';
        }else{
            return '';
        }
    }


}
