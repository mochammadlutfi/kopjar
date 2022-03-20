<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    protected $table = 'account_account_group';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'code', 'parent_id'
    ];

    public function child(){
        return $this->hasMany(AccountGroup::class, 'parent_id');

    }

    public function parent(){
        return $this->belongsTo(AccountGroup::class, 'parent_id');
    }

}
