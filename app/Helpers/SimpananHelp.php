<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Simpanan\SimlaTransaksi;
use App\Models\Simpanan\SimkopTransaksi;
use App\Models\Transaksi;
use DB;
class SimpananHelp
{

    public static function getSaldoSimla($anggota_id)
    {
        $simla = SimlaTransaksi::
        join('transaksi as a', 'a.id', '=', 'simla_transaksi.transaksi_id')
        ->where('a.status', '1')
        ->where('a.anggota_id', $anggota_id)
        ->get();

        $credit = $simla->sum('credit');
        $debit = $simla->sum('debit');

        return (int)$debit - $credit;
    }


    public static function getSaldoSimkop($anggota_id)
    {
        $saldo = SimkopTransaksi::
        join('transaksi as a', 'a.id', '=', 'simkop_transaksi.transaksi_id')
        ->where('a.status', '1')
        ->where('a.anggota_id', $anggota_id)
        ->sum('simkop_transaksi.jumlah');

        return (int)$saldo;
    }

    public static function getSaldoPokok($anggota_id)
    {
        $saldo = Transaksi::join('transaksi_line as a', 'a.transaksi_id', '=', 'transaksi.id')
        ->where('transaksi.status', 1)
        ->where('transaksi.anggota_id', $anggota_id)
        ->where('a.akun_id', 3)
        ->sum('a.jumlah');

        return (int)$saldo;
    }
    

    public static function getTransactionPending($type)
    {
        if($type == 'simkop'){
            $total = SimkopTransaksi::select('a.anggota_id', 'simkop_transaksi.periode', 'a.nama', 'b.id', 'b.total', 'd.nama as teller', 'b.status', 'b.nomor', 'b.tgl')
            ->leftJoin('anggota as a', 'a.anggota_id', '=', 'simkop_transaksi.anggota_id')
            ->leftJoin('transaksi as b', 'b.id', '=', 'simkop_transaksi.transaksi_id')
            ->leftJoin('admins as c', 'c.id', '=', 'b.teller_id')
            ->leftJoin('anggota as d', 'd.anggota_id', '=', 'c.anggota_id')
            ->where('b.status', 0)->count();
    
        }elseif($type == 'simla deposit'){
            $total = SimlaTransaksi::select('a.anggota_id', 'a.nama', 'simla_transaksi.jumlah', 'd.nama as teller', 'simla_transaksi.type', 'b.status', 'b.nomor', 'b.tgl', 'b.id')
            ->leftJoin('anggota as a', 'a.anggota_id', '=', 'simla_transaksi.anggota_id')
            ->leftJoin('transaksi as b', 'b.id', '=', 'simla_transaksi.transaksi_id')
            ->leftJoin('admins as c', 'c.id', '=', 'b.teller_id')
            ->leftJoin('anggota as d', 'd.anggota_id', '=', 'c.anggota_id')
            ->where('simla_transaksi.type', 'isi saldo')
            ->where('b.status', 0)->count();
        }elseif($type == 'simla withdraw'){
            $total = SimlaTransaksi::select('a.anggota_id', 'a.nama', 'simla_transaksi.jumlah', 'd.nama as teller', 'simla_transaksi.type', 'b.status', 'b.nomor', 'b.tgl', 'b.id')
            ->leftJoin('anggota as a', 'a.anggota_id', '=', 'simla_transaksi.anggota_id')
            ->leftJoin('transaksi as b', 'b.id', '=', 'simla_transaksi.transaksi_id')
            ->leftJoin('admins as c', 'c.id', '=', 'b.teller_id')
            ->leftJoin('anggota as d', 'd.anggota_id', '=', 'c.anggota_id')
            ->where('simla_transaksi.type', 'penarikan')
            ->where('b.status', 0)->count();
        }

        return (int)$total;
    }

    public static function getAmountWajib($gol)
    {
        $wajib = 0;
        if($gol == '1'){
            $wajib = (int)settings()->get('simpanan_wajib_amount_1');
        }else if($gol == '2'){
            $wajib = (int)settings()->get('simpanan_wajib_amount_2');
        }else if($gol == '3'){
            $wajib = (int)settings()->get('simpanan_wajib_amount_3');
        }else if($gol == '4'){
            $wajib = (int)settings()->get('simpanan_wajib_amount_4');
        }else if($gol == '5'){
            $wajib = (int)settings()->get('simpanan_wajib_amount_5');
        }else if($gol == '6'){
            $wajib = (int)settings()->get('simpanan_wajib_amount_6');
        }else if($gol == '7'){
            $wajib = (int)settings()->get('simpanan_wajib_amount_7');
        }else{
            $wajib = (int)settings()->get('simpanan_wajib_amount_8');
        }

        return $wajib;
    }

}
