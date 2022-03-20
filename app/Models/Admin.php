<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminEmailVerificationNotification;
use App\Notifications\AdminResetPasswordNotification as Notification;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'anggota_id', 'username', 'password',
    ];
    
    protected $appends = [
        'jabatan'
    ];  


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id', 'anggota_id');
    }

    public function getNameAttribute($value)
    {
        if((!isset($this->anggota_id) || $this->anggota_id === null) === !isset($this->anggota_id)){
            return $value;
        }else{
            return $this->anggota->nama;
        }
    }

    public function getJabatanAttribute($value)
    {
        $jabatan = '';
        foreach($this->getRoleNames() as $v){
            $jabatan = ucwords(str_replace('-', ' ', $v));
        }
        return $jabatan;
    }

    /**
     * Custom password reset notification.
     * 
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new Notification($token));
    }

    /**
     * Send email verification notice.
     * 
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new AdminEmailVerificationNotification);
    }
}
