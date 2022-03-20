<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'reg_districts';

    protected $fillable = [
        'name', 'district_id', 'alamat', 'postal_code'
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'regency_id', 'id');
    }
}
