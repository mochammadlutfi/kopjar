<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Simpanan\SimkopTransaksi;
use Jenssegers\Date\Date;

use App\Helpers\SimpananHelp;
class Anggota extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'anggota_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'anggota_id', 'nama', 'email', 'no_ktp', 'jk', 'tmp_lahir', 'tgl_lahir', 'no_hp', 'no_telp', 'status_pernikahan', 'pendidikan', 'pekerjaan', 'nama_ibu', 'foto', 'ktp'
    ];

    protected $appends = [
        'status_badge',
    ];

    public function alamat()
    {
        return $this->hasMany(AnggotaAlamat::class, 'anggota_id', 'anggota_id');
    }

    
    public function getAlamatFullAttribute()
    {
        $alamat = '';
        if($this->alamat){
            $data = $this->alamat->where('domisili', 1)->first();
            $alamat .= $data->alamat.', ';
            $alamat .= ucwords(strtolower($data->wilayah->name)).', ';
            $alamat .= $data->pos.', ';
            $alamat .= 'Kec. '.ucwords(strtolower($data->wilayah->kecamatan->name)).', ';
            $alamat .= ucwords(strtolower($data->wilayah->kecamatan->kota->name)).', ';
            $alamat .= ucwords(strtolower($data->wilayah->kecamatan->kota->provinsi->name));
        }
        return $alamat;
    }

    public function getAvatarAttribute($value)
    {
        if($value == null){
            return 'media/placeholder/avatar.jpg';
        }

        return $value;
    }

    public function getStatusBadgeAttribute($value)
    {
        if ($this->status === 1) {
            return '<span class="badge badge-success">Aktif</span>';
        } else {
            return '<span class="badge badge-danger">Keluar</span>';
        }
    }


    public function getTunggakanSimkopAttribute($value)
    {
        $dari = Carbon::parse($this->tgl_gabung)->startOfMonth();
        $now = Carbon::now()->endOfMonth();
        $diff_in_months = $dari->diffInMonths($now);

        $nominal = 0;
        $jumlah = 0;
        $list = array();
        for($i = 0; $i <= $diff_in_months; $i++)
        {
            $bulan = SimkopTransaksi::where('anggota_id', $this->anggota_id)
            ->whereMonth('periode', $dari->format('m'))
            ->whereYear('periode', $dari->format('Y'))
            ->first();
            if(!$bulan)
            {
                $nominal += SimpananHelp::getAmountWajib($this->golongan);
                $jumlah += 1;
                $list[$dari->format('Y')][] = $dari->format('F Y');
            }
            $dari->addMonth(1);
        }
        
        $data = array(
            'jumlah' => $jumlah,
            'nominal' => $nominal,
            'list' => $list,
        );
        return $data;
    }

    public function last_simkop()
    {
        return $this->hasOne(Simpanan\SimkopTransaksi::class, 'anggota_id')->orderBy('periode', 'DESC')->latest();
    }

    
}
