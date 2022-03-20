<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class CashLine extends Model
{
    protected $table = 'account_cash_line';
    protected $primaryKey = 'id';

    protected $fillable = [
        'cash_id', 'journal_id', 'jumlah',
    ];

    
    protected $casts = [
        'jumlah' => 'float',
    ];


    public function cash()
    {
        return $this->belongsTo(Cash::class, 'cash_id');
    }

    public function journal()
    {
        return $this->belongsTo(Account::class, 'journal_id');
    }

}
