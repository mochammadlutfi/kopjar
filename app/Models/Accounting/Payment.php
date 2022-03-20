<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'account_payment';
    protected $primaryKey = 'id';

    protected $fillable = [
        'transaksi_id', 'bank_id', 'program', 'jumlah', 'code', 'status', 'method', 'tgl_bayar'
    ];


    public function transaksi()
    {
        return $this->belongsTo(\Transaksi::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function payment_method(){
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
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
