<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use DB;


use Carbon\Carbon;
use App\Helpers\GeneralHelp;
use App\Models\Transaksi;
use App\Models\TransaksiLine;
use App\Models\Accounting\Payment;
use App\Models\Anggota;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $today = Carbon::now()->format('m');

        $wajib = TransaksiLine::whereHas('transaksi', function($q) use ($today){
            return $q->whereMonth('tgl', $today);
        })
        ->where('akun_id', settings()->get('simpanan_wajib_journal'))
        ->get()->sum('jumlah');

        $sukarela = TransaksiLine::whereHas('transaksi', function($q) use ($today){
            return $q->whereMonth('tgl', $today);
        })
        ->where('akun_id', settings()->get('simpanan_sukarela_journal'))
        ->get()->sum('jumlah');

        $piutang = Payment::where('payment_method_id', 2)->where('status', 'unpaid')
        ->whereMonth('created_at', $today)->get()->sum('jumlah');

        $anggota = Anggota::whereMonth('tgl_gabung', $today)->get()->count();

        $trans = Transaksi::latest()->limit(9)->get();
        

        $overview = collect([
            [
                'title' => 'Anggota Baru',
                'data' => $anggota,
            ],
            [
                'title' => 'Simpanan Wajib',
                'data' => $wajib,
            ],
            [
                'title' => 'Simpanan Sukarela',
                'data' => $sukarela,
            ],
            [
                'title' => 'Piutang',
                'data' => $piutang,
            ],
        ]);

        
        return Inertia::render('Dashboard', [
            'overview' => $overview,
            'simpanan' => $this->chartSimpanan(),
            'keanggotaan' => $this->keanggotaan(),
            'transaksi' => $trans
        ]);
    }

    private function chartSimpanan(){
        $today = Carbon::now();
        $start = Carbon::now()->subDays(7);
        
        $i = 0;
        $label = array();
        $wajib = array();
        $sukarela = array();
        while ($i <= $start->diffInDays($today)) {

            $dayOfWeek = Carbon::now()->locale('id')->subDays(7)->addDays($i);
            $label[] = $dayOfWeek->translatedFormat('l, d M Y');
            $wajibEloq = Transaksi::join('transaksi_line as a', 'a.transaksi_id', '=', 'transaksi.id')
            ->where('akun_id',  (int)settings()->get('simpanan_wajib_journal'))
            ->whereDate('transaksi.tgl', Carbon::now()->locale('id')->subDays(7)->addDays($i));

            $sukarelaEloq = Transaksi::join('transaksi_line as a', 'a.transaksi_id', '=', 'transaksi.id')
            ->where('akun_id',  (int)settings()->get('simpanan_sukarela_journal'))
            ->whereDate('transaksi.tgl', Carbon::now()->locale('id')->subDays(7)->addDays($i));

            $wajib[] = $wajibEloq->count();
            $sukarela[] = $sukarelaEloq->count();
            $i++;
        }
        $response = Collect([
            "label" => $label,
            "wajib" => $wajib,
            "sukarela" => $sukarela,
        ]);

        return $response;
    }

    private function keanggotaan(){
        $anggota = Anggota::select('golongan', DB::raw('count(*) as jumlah'))
        ->groupBy('golongan')
        ->get();

        $total = Anggota::latest()->get()->count();

        $data = collect([
            'data' => $anggota,
            'total' => $total
        ]);

        return $data;
    }
}
