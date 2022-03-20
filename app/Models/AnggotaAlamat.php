<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaAlamat extends Model
{
    protected $table = 'anggota_alamat';
    protected $primaryKey = 'alamat_id';

    protected $fillable = [
        'anggota_id', 'domisili', 'wilayah_id', 'pos', 'alamat'
    ];

    protected $appends = [
        'daerah', 'alamat_lengkap'
    ];

    public function anggota(){
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah\Kelurahan::class, 'wilayah_id');
    }

    public function getDaerahAttribute($value)
    {
        $daerah = '';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->kota->provinsi->name)).', ';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->kota->name)).', Kec. ';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->name)).', ';
        $daerah .= ucwords(strtolower($this->wilayah->name));
        return  $daerah;
    }

    public function getAlamatLengkapAttribute($value)
    {
        $daerah = '';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->kota->provinsi->name)).', ';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->kota->name)).', Kec. ';
        $daerah .= ucwords(strtolower($this->wilayah->kecamatan->name)).', ';
        $daerah .= $this->pos.', ';
        $daerah .= ucwords(strtolower($this->wilayah->name)).', ';
        $daerah .= $this->alamat;
        return  $daerah;
    }

}
