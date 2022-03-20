<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Anggota;
use App\Models\Transaksi;
use App\Models\Pembiayaan\PmbTunai;
use DB;
class GeneralHelp
{

    public static function generate_kd_anggota($tipe)
    {
        $q = Anggota::select(DB::raw('MAX(RIGHT(anggota_id,4)) AS kd_max'));

        if($tipe == 1){
            $kd_tipe = 1;
        }else{
            $kd_tipe = 2;
        }
        $no = 1;
        date_default_timezone_set('Asia/Jakarta');

        if($q->count() > 0){
            foreach($q->get() as $k){
                return $kd_tipe . date('ym').sprintf("%04s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return $kd_tipe . date('ym').sprintf("%04s", $no);
        }
    }


    public static function UserMenuList()
    {
        $result = Collect([
            [
                'name' => 'Dashboard',
                'to' => 'dashboard',
                'icon' => 'si si-speedometer'
            ]
        ]);
    }


    public static function generate_transaksi_ref($type)
    {

        if($type == 'simkop'){
            $sub_service = 'wajib';
            $service = 'simpanan';
            $code = 'SK/';
        }else if($type == 'simla'){
            $sub_service = 'sukarela';
            $service = 'simpanan';
            $code = 'SL/';
        }

        $today = Carbon::today();
        
        $q = Transaksi::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'))->whereDate('tgl', $today)->where('sub_service', $sub_service);
        $kd_cabang = 1;
        $no = 1;

        date_default_timezone_set('Asia/Jakarta');
        if($q->count() > 0){
            foreach($q->get() as $k){
                return $code. Carbon::now()->format('ymd') .'/'. sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return $code. Carbon::now()->format('ymd').'/'. sprintf("%05s", 1);
        }
    }


    public static function generate_transaksi_kas_ref()
    {

        $code = 'CT/';

        $today = Carbon::today();
        
        $q =  DB::table('account_cash')->select(DB::raw('MAX(RIGHT(ref,5)) AS kd_max'))->whereDate('tgl', $today);
        $kd_cabang = 1;
        $no = 1;

        date_default_timezone_set('Asia/Jakarta');
        if($q->count() > 0){
            foreach($q->get() as $k){
                return $code. Carbon::now()->format('ymd') .'/'. sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return $code. Carbon::now()->format('ymd').'/'. sprintf("%05s", 1);
        }
    }


    public static function currency($value){
        return number_format($value,0,',','.');
    }

    
    /**
     * Calculates in percent, the change between 2 numbers.
     * e.g from 1000 to 500 = 50%
     * 
     * @param oldNumber The initial value
     * @param newNumber The value that changed
     * @return percentage
     */
    public static function percentage($old_val, $new_val){
        $decreaseValue = $old_val - $new_val;

        return ($decreaseValue / $old_val) * 100;
    }
}
