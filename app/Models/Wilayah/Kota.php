<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    protected $table = 'reg_regencies';

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'province_id', 'id');
    }
}
