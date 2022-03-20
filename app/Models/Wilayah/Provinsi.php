<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'reg_provinces';

    public function kota()
    {
        return $this->hasMany(Kota::class, 'id', 'regency_id');
    }

}
