<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'account_account';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'code',
    ];


    public function group()
    {
        return $this->belongsTo(AccountGroup::class);
    }

}
