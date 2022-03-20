<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'account_payment_method';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'name', 'type', 'bank_id',
    ];


    public function payment()
    {
        return $this->hasOne(\Payment::class, 'method_id',);
    }
    
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

}
