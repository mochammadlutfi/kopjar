<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'reg_villages';

    protected $fillable = [
        'name', 'district_id'
    ];

    protected $appends = [
        'daerah'
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'district_id');
    }

    public function getDaerahAttribute($value)
    {
        $daerah = '';
        $daerah .= ucwords(strtolower($this->kecamatan->kota->provinsi->name)).', ';
        $daerah .= ucwords(strtolower($this->kecamatan->kota->name)).', Kec. ';
        $daerah .= ucwords(strtolower($this->kecamatan->name)).', ';
        $daerah .= ucwords(strtolower($this->name)).', ';
        return  $daerah;
    }


}
