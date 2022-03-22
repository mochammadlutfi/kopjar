<?php

namespace App\Http\Controllers\Staff\Simpanan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Helpers\GeneralHelp;
use App\Helpers\SimpananHelp;



use App\Models\Transaksi;
use App\Models\Accounting\Payment;
use App\Models\TransaksiLine;

use App\Models\Simpanan\SimkopTransaksi;
use App\Models\Simpanan\SimlaTransaksi;
use App\Models\Simpanan\Wallet;

class SukarelaWithdrawController extends Controller
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
        $keyword  = $request->search;
        $from = isset($request->from) ? Carbon::parse($request->from) : Carbon::now()->startOfMonth();
        $to = isset($request->to) ? Carbon::parse($request->to) : Carbon::now();
        $status = $request->status;

        $eloq = SimlaTransaksi::select('a.anggota_id', 'a.nama', 'simla_transaksi.credit', 'b.total', 'd.nama as teller', 'simla_transaksi.type', 'b.nomor', 'b.tgl', 'b.id', 'b.status')
            ->leftJoin('anggota as a', 'a.anggota_id', '=', 'simla_transaksi.anggota_id')
            ->leftJoin('transaksi as b', 'b.id', '=', 'simla_transaksi.transaksi_id')
            ->leftJoin('admins as c', 'c.id', '=', 'b.teller_id')
            ->leftJoin('anggota as d', 'd.anggota_id', '=', 'c.anggota_id')
            ->where(function ($query) use ($keyword) {
                return $query->where('a.anggota_id', 'like', '%' . $keyword . '%')
                ->orWhere('a.nama', 'like', '%' . $keyword . '%')
                ->orWhere('b.jenis', 'like', '%' . $keyword . '%')
                ->orWhere('b.nomor', 'like', '%' . $keyword . '%')
                ->orWhere('d.nama', 'like', '%' . $keyword . '%');
            })
            ->where('simla_transaksi.type', 'withdrawn')
            ->whereBetween('b.tgl', [$from, $to])
            ->when(isset($status), function ($q) use ($status){
                return $q->where('b.status', $status);
            });
            // dd($eloq->get());

            $data = $eloq->orderBy('tgl', 'DESC')
            ->paginate(20);

            $all = clone $eloq;
            $pending = clone $eloq;
            $done = clone $eloq;
    
            $statistic = [
                'all' => $all->count(),
                'pending' => $pending->where('b.status', 0)->count(),
                'done' =>  $done->where('b.status', 1)->count(),
            ];

        return Inertia::render('Simpanan/Sukarela/IndexWithdraw',[
            'dataList' => $data,
            'statistic' => $statistic
        ]);
    }


        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        return Inertia::render('Simpanan/Sukarela/FormWithdraw',[
            'transaksi_ref' => GeneralHelp::generate_transaksi_ref('simla'),
        ]);
    }

        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $rules = [
            'anggota_id' => 'required',
            'amount' => 'required',
            'tgl' => 'required',
        ];

        $pesan = [
            'anggota_id.required' => 'ID Anggota Wajib Diisi!',
            'kas_id.required' => 'Kas Wajib Diisi!',
            'amount.required' => 'Jumlah Simpanan Wajib Diisi!',
            'tgl.required' => 'Tanggal Transaksi Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{

            $current_saldo = SimpananHelp::getSaldoSimla($request->anggota_id);

            if($request->amount >= $current_saldo){
                $errors['amount'] = array('Jumlah Tidak Boleh Lebih Dari Rp '. GeneralHelp::currency($current_saldo));

                return response()->json([
                    'fail' => true,
                    'errors' => $errors
                ]);
            }

            $item = collect([
                array(
                    'keterangan' => 'Simpanan Sukarela',
                    'jumlah' => $request->jumlah,
                    'akun' => 14,
                ),
            ]);

            DB::beginTransaction();
            try{

                $transaksi = new Transaksi();
                $transaksi->nomor = $request->kd_transaksi;
                $transaksi->anggota_id = $request->anggota_id;
                $transaksi->service = 'simpanan';
                $transaksi->sub_service = 'sukarela';
                $transaksi->jenis = 'penarikan sukarela';
                $transaksi->teller_id   = auth()->guard('admin')->user()->id;
                $transaksi->keterangan = $request->keterangan;
                $transaksi->total = $request->amount ;
                $transaksi->tgl = Carbon::parse($request->tgl)->format('Y-m-d');
                $transaksi->status = 1;
                $transaksi->save();
 

                $line = new TransaksiLine();
                $line->jumlah = $request->amount;
                $line->nama = 'Simpanan Sukarela';
                $line->akun_id = 14;
                $line->user_id = auth()->guard('admin')->user()->id;
                $line->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->line()->save($line);
                
                $payment = new Payment();
                $payment->payment_method_id = 1;
                $payment->tgl_bayar = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $payment->jumlah = (int)$request->amount;
                $payment->type = 'outbound';
                $payment->status = 'paid';
                $transaksi->payment()->save($payment);

                $simla = new SimlaTransaksi();
                $simla->anggota_id = $request->anggota_id;
                $simla->type = 'withdrawn';
                $simla->credit = $request->amount;
                $simla->debit = 0;
                $transaksi->simla()->save($simla);

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Terjadi Error Pada Penyimpanan Data',
                    'error' => $e,
                ]);
            }

            DB::commit();
            return response()->json([
                'fail' => false,
                'invoice' => $transaksi->id,
            ]);
        }
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Request $request)
    {
        return Inertia::render('Simpanan/Sukarela/Show');
    }

}
