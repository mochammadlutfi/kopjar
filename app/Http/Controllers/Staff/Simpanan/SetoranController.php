<?php

namespace App\Http\Controllers\Staff\Simpanan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Helpers\GeneralHelp;



use App\Models\Transaksi;
use App\Models\TransaksiBayar;
use App\Models\TransaksiLine;

use App\Models\Simpanan\SimkopTransaksi;
use App\Models\Simpanan\SimlaTransaksi;
use App\Models\Simpanan\Wallet;

class SetoranController extends Controller
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
    public function wajib(Request $request)
    {
        
        return Inertia::render('Simpanan/FormWajib',[
            'transaksi_ref' => GeneralHelp::generate_transaksi_ref('simkop'),
        ]);
    }


        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function wajib_store(Request $request)
    {

        // dd($request->all());

        $rules = [
            'anggota_id' => 'required',
            'tgl' => 'required',
            'periode' => 'required',
        ];

        $pesan = [
            'anggota_id.required' => 'Anggota Koperasi Wajib Diisi!',
            'kas_id.required' => 'Kas Koperasi Wajib Diisi!',
            'periode.required' => 'Periode Pembayaran Wajib Diisi!',
            'tgl.required' => 'Tanggal Setoran Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            $pay_check = SimkopTransaksi::where('anggota_id', $request->anggota_id)
            ->whereMonth('periode', Carbon::createFromFormat('d F Y', '1 '.$request->periode)->format('m'))
            ->whereYear('periode', Carbon::createFromFormat('d F Y', '1 '.$request->periode)->format('Y'))
            ->first();

            if($pay_check)
            {
                return response()->json([
                    'fail' => true,
                    'errors' => $validator->errors()
                ]);
            }

            $item = collect([
                array(
                    'keterangan' => 'Simpanan Wajib',
                    'jumlah' => 100000,
                    'akun' => 4,
                ),
            ]);
            
            if(!empty($request->jml_sosial)){
                $item = $item->push([
                    'keterangan' => 'Simpanan Sosial',
                    'jumlah' => (int)$request->jml_sosial,
                    'akun' => 9,
                ]);
            }

            DB::beginTransaction();
            Carbon::today()->format('H:i:s');
            try{
                $transaksi = new Transaksi();
                $transaksi->nomor = $request->kd_transaksi;
                $transaksi->anggota_id = $request->anggota_id;
                $transaksi->service = 'simpanan';
                $transaksi->sub_service = 'wajib';
                $transaksi->jenis = 'setoran wajib';
                $transaksi->total = 100000 + (int)$request->jml_sosial;
                $transaksi->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->teller_id  = auth()->guard('admin')->user()->id;
                $transaksi->save();
                
                $pay_wajib = new SimkopTransaksi();
                $pay_wajib->anggota_id  = $request->anggota_id;
                $pay_wajib->periode = Carbon::createFromFormat('d F Y', '1 '. $request->periode)->format('Y-m-d');
                $pay_wajib->jumlah = 100000;
                $pay_wajib->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');

                $transaksi->simkop()->save($pay_wajib);

                $payment = new TransaksiBayar();
                $payment->method = 'Tunai';
                $payment->jumlah = !empty($request->jml_sosial) ? 150000 : 100000;
                $payment->status = 'confirm';
                $transaksi->pembayaran()->save($payment);

                $wallet = Wallet::where('anggota_id', $request->anggota_id)->first();
                $wallet->increment('wajib', 100000);

                foreach($item as $i){
                    $line = new TransaksiLine();
                    $line->akun_id = $i['akun'];
                    $line->jumlah = $i['jumlah'];
                    $line->keterangan = $i['keterangan'];
                    $line->user_id = auth()->guard('admin')->user()->id;
                    $transaksi->line()->save($line);
                }

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
    public function sukarela(Request $request)
    {

        return Inertia::render('Simpanan/FormSukarela');
    }


        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sukarela_store(Request $request)
    {
        return Inertia::render('Dashboard');
    }

    public function wajib_paid($id){
        $data = SimkopTransaksi::where('anggota_id', $id)
        ->whereYear('periode', '>', '2020')->pluck('periode');
        return response()->json([
            'fail' => false,
            'date' => $data,
        ]);
    }


}
