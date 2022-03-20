<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Controller;
use App\Helpers\GeneralHelp;
use Illuminate\Support\Facades\Validator;
use DB;
use Storage;
use Carbon\Carbon;
use App\Helpers\SimpananHelp;


use App\Models\Anggota;
use App\Models\AnggotaAlamat;

use App\Models\Simpanan\SimkopTransaksi;
use App\Models\Simpanan\SimlaTransaksi;
use App\Models\Simpanan\Wallet;

use App\Models\User;
use App\Models\TransaksiLine;

use App\Models\Pembiayaan\PmbTunai;
class AnggotaAuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $data = User::with([
            'anggota' => function($query) use ($keyword) {
                $query->select('anggota_id','nama', 'no_ktp', 'no_hp');
                $query->where('nama', 'like', '%' . $keyword . '%');
            },
        ])
        ->orderBy('id', 'ASC')->paginate(10);

        return Inertia::render('Anggota/Auth', [
            'dataList' => $data
        ]);
    }


        /**
     * create the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {

        return Inertia::render('Anggota/form',[
            'kd_anggota' => GeneralHelp::generate_kd_anggota(),
            'transaksi_ref' => GeneralHelp::generate_transaksi_ref('simkop'),
        ]);
    }


    /**
     * Menyimpan Data Anggota Baru
     * 
     * @return Renderable
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), $rules, $pesan);
        if ($v->fails()) {
            return response()->json([
                'fail' => true,
                'errors' => $v->errors()
            ]);
        }else{
            DB::beginTransaction();

            try{

                $anggota_id = $request->anggota_id;
                
                $anggota = new Anggota();
                $anggota->anggota_id =  $request->anggota_id;
                $anggota->no_ktp = $request->no_ktp;
                $anggota->nama = $request->nama;
                $anggota->jk = $request->jk;
                $anggota->tgl_lahir = Carbon::parse($request->tgl_lahir)->format('Y-m-d');
                $anggota->tmp_lahir = $request->tmp_lahir;
                $anggota->email = $request->email;
                $anggota->no_hp = $request->no_hp;
                $anggota->no_telp = $request->no_telp;
                $anggota->pekerjaan = $request->pekerjaan;
                $anggota->pendidikan = $request->pendidikan;
                $anggota->status_pernikahan = $request->perkawinan;
                $anggota->nama_ibu = $request->nama_ibu;

                if($request->hasfile('ktp')){
                    $anggota->ktp = $ktp;
                }

                if($request->hasfile('foto')){
                    $anggota->foto = $foto;
                }

                $anggota->cabang_id = 1;
                $anggota->status = 1;
                $anggota->tgl_gabung = Carbon::today()->format('Y-m-d');
                $anggota->created_at = Carbon::today()->format('Y-m-d H:i:s');
                $anggota->save();

                $alamat = array(
                    [
                        'anggota_id' => $request->anggota_id,
                        'alamat' => $request->alamat,
                        'domisili' => !empty($request->alamat2) ? 0 : 1,
                        'wilayah_id' => $request->wilayah_id,
                        'pos' => $request->kode_pos,
                    ]
                );
                if(!empty($request->alamat2)){
                    array_push($alamat,
                    [
                        'anggota_id' => $request->anggota_id,
                        'alamat' => $request->alamat2,
                        'domisili' => 1,
                        'wilayah_id' => $request->wilayah_id2,
                        'pos' => $request->kode_pos2,
                    ]);
                }
                // dd($alamat);
                // $anggota->alamat()->createMany($alamat);
                AnggotaAlamat::insert($alamat);

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'errors' => $e,
                    'pesan' => 'Error Menyimpan Data Anggota',
                ]);
            }


            try{
                $program = array(
                    array(
                        'keterangan' => 'Administrasi',
                        'nominal' => 25000,
                        'journal_id' => 12,
                    ),
                    array(
                        'keterangan' => 'Simpanan Pokok',
                        'nominal' => 200000,
                        'journal_id' => 3,
                    ),
                    array(
                        'keterangan' => 'Simpanan Wajib',
                        'nominal' => 100000,
                        'journal_id' => 4,
                    ),
                    array(
                        'keterangan' => 'Simpanan Sosial',
                        'nominal' => 5000,
                        'journal_id' => 9,
                    )
                );

                $total = 330000;

                if(!empty($request->simla)){
                    $simla = array(
                        'keterangan' => 'Simpanan Sukarela',
                        'nominal' => $request->simla,
                        'akun' => 14,
                    );
                    $total += (int)$request->simla;

                    array_push($program, $simla);
                }

                $no_transaksi = $request->transaksi_ref;

                $transaksi = new Transaksi();
                $transaksi->nomor = $no_transaksi;
                $transaksi->anggota_id = $anggota_id;
                $transaksi->service = 'pendaftaran';
                $transaksi->sub_service = null;
                $transaksi->jenis = 'pendaftaran';
                $transaksi->total = $total;
                $transaksi->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                // $transaksi->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->teller_id  = auth()->guard('admin')->user()->id;
                $transaksi->save();

                
                $pay_wajib = new SimkopTransaksi();
                $pay_wajib->anggota_id  = $request->anggota_id;
                $pay_wajib->periode = Carbon::parse($request->tgl)->format('Y-m-d');
                $pay_wajib->jumlah = 100000;
                $pay_wajib->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->simkop()->save($pay_wajib);
                
                $wallet = new Wallet();
                $wallet->anggota_id = $anggota_id;
                $wallet->wajib = 100000;
                $wallet->pokok = 200000;
                $wallet->sosial = 5000;
                // $wallet->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');

                if(!empty($request->simla))
                {
                    $simla = new SimlaTransaksi();
                    $simla->anggota_id = $request->anggota_id;
                    $simla->type = 'deposit';
                    $simla->jumlah = (int)$request->jumlah;
                    $simla->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                    $transaksi->simla()->save($simla);

                    $wallet->sukarela = $request->simla;
                }
                
                $wallet->save();

                foreach($program as $p)
                {
                    $line = new TransaksiLine();
                    $line->jumlah = $p['nominal'];
                    $line->keterangan = $p['keterangan'];
                    $line->jenis = 'pemasukan';
                    $line->akun_id = $p['journal_id'];
                    $line->user_id = auth()->guard('admin')->user()->id;
                    $line->tgl = Carbon::parse($request->tgl)->format('Y-m-d');
                    $transaksi->line()->save($line);
                }
                
            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Error Menyimpan Data Transaksi',
                    'log' => $e,
                ]);
            }

            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
    }



    public function show($id, $state = '', Request $request)
    {
        $keyword = $request->search;
        if($state == '' || $state == 'simpanan' || $state == 'pembiayaan'){
            $anggota = Anggota::where('anggota_id', $id)->first();

            if($state == ''){
                
                $dataList = Transaksi::with(['payment' => function($q){
                    $q->select(['method', 'transaksi_id', 'code', 'admin_fee', 'bank_id', 'status', 'jumlah']);
                }])
                ->where('transaksi.anggota_id', $anggota->anggota_id)
                ->orderBy('transaksi.tgl', 'DESC')
                ->paginate(10);

                $result = [
                    'anggota' => $anggota,
                    'state' => $state,
                    'dataList' => $dataList
                ];
            }elseif($state == 'simpanan'){

                $dataList = Transaksi::with(['payment' => function($q){
                    $q->select(['method', 'transaksi_id', 'code', 'admin_fee', 'bank_id', 'status', 'jumlah']);
                }])
                ->where(function($q){
                    $q->where('transaksi.service', 'pendaftaran')
                    ->orWhere('transaksi.service', 'simpanan');
                })
                ->where('transaksi.anggota_id', $anggota->anggota_id)
                ->orderBy('transaksi.tgl', 'DESC')
                ->paginate(10);

                $wallet = Wallet::where('anggota_id', $id)->first();
                $overview = collect([
                    [
                        'program' => 'Simpanan Pokok',
                        'saldo' => (int)$wallet->pokok,
                        'akun' => 3,
                    ],
                    [
                        'program' => 'Simpanan Wajib',
                        'saldo' => SimpananHelp::getSaldoSimkop($anggota->anggota_id),
                        'akun' => 4,
                    ],
                    [
                        'program' => 'Simpanan Sosial',
                        'saldo' => (int)$wallet->sosial,
                        'akun' => 9,
                    ],
                    [
                        'program' => 'Simpanan Sukarela',
                        'saldo' => SimpananHelp::getSaldoSimla($anggota->anggota_id),
                        'akun' => 14,
                    ],
                ]);

                $result = [
                    'anggota' => $anggota,
                    'state' => $state,
                    'dataList' => $dataList,
                    'overview' => $overview
                ];
            }else{
                $dataList = PmbTunai::with([
                    'anggota' => function($query) use ($keyword) {
                        $query->select('anggota_id','nama');
                    },
                ])
                ->where('anggota_id', $anggota->anggota_id)
                ->orderBy('created_at', 'DESC')
                ->paginate(20);
                $result = [
                    'anggota' => $anggota,
                    'state' => $state,
                    'dataList' => $dataList,
                ];
            }
            return Inertia::render('Anggota/Show',$result);
        }
        
        return abort(404);

    }


    public function data(Request $request)
    {
        $keyword = $request->q;
        $data = Anggota::with(["alamat" => function($q){
            $q->where('anggota_alamat.domisili', '=', 1);
        }])
        ->where(function($q) use ($keyword){
            $q->where('anggota_id', 'like', '%' . $keyword . '%')
              ->orWhere('nama', 'like', '%' . $keyword . '%');
        })
        ->orderBy('anggota_id', 'DESC')->get();

        
        return response()->json($data);
    }

}
