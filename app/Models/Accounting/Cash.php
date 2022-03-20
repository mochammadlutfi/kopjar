<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    protected $table = 'account_cash';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ref', 'tgl', 'staff_id', 'note', 'type',
    ];

    
    protected $casts = [
        'total' => 'float',
    ];


    public function line()
    {
        return $this->hasMany(CashLine::class);
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'paymenttable');
    }

    public function getMethodAttribute($value)
    {
        if($value == 'simla'){
            return 'Simpanan Sukarela';
        }else{
            return $value;
        }
    }




}
