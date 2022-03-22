<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use App\Models\Transaksi;
use App\Models\Anggota;
use Carbon\Carbon;
class PiutangExport implements FromArray, WithHeadings, WithColumnFormatting
{

    use Exportable;

    // protected $year;
    
    // public function __construct(int $year)
    // {
    //     $this->year = $year;
    // }

    public function headings(): array
    {
        return [
            'ID Anggota',
            'Nama Lengkap',
            'NIP',
            'Golongan',
            'Jumlah',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        $today = Carbon::now()->format('m');

        $anggota = Anggota::where('tipe', 1)->latest()->get();
        $items = [];
        foreach($anggota as $ang){
            $transaksi = Transaksi::whereHas(
                'payment', function($q){
                    return $q->where('payment_method_id', 2);
                },
            )
            ->where('anggota_id', $ang->anggota_id)
            ->whereMonth('tgl', $today)->get();
            
            if($transaksi->count() > 0){
                $items[] = [
                    'anggota_id' => $ang->anggota_id,
                    'nama' => $ang->nama,
                    'nip' => $ang->nip,
                    'golongan' => $ang->golongan,
                    'jumlah' => $transaksi->sum('total'),
                ];
            }
        }

        return $items;
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
