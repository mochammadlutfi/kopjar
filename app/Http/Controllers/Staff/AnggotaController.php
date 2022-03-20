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

use App\Models\Transaksi;
use App\Models\TransaksiLine;
use App\Models\Accounting\Payment;

use App\Models\Pembiayaan\PmbTunai;
class AnggotaController extends Controller
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
        $keyword = $request->search;
        $dataList = Anggota::with(["alamat" => function($q){
            $q->where('anggota_alamat.domisili', '=', 1);
        }])
        ->where(function($q) use ($keyword){
            $q->where('anggota_id', 'like', '%' . $keyword . '%')
              ->orWhere('nama', 'like', '%' . $keyword . '%');
        })
        ->orderBy('anggota_id', 'DESC')->paginate(20);

        return Inertia::render('Anggota/index', [
            'dataList' => $dataList
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
        // $v = Validator::make($request->all(), $rules, $pesan);
        // if ($v->fails()) {
        //     return response()->json([
        //         'fail' => true,
        //         'errors' => $v->errors()
        //     ]);
        // }else{
            // dd($request->all());
            DB::beginTransaction();

            try{

                $anggota_id = GeneralHelp::generate_kd_anggota((int)$request->tipe);
                
                $anggota = new Anggota();
                $anggota->anggota_id =  $anggota_id;
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

                $anggota->nip = $request->nip;
                $anggota->tipe = (int)$request->tipe;
                $anggota->golongan = (int)$request->golongan;

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

                if(!empty($request->alamat)){
                    $alamat = array(
                        [
                            'anggota_id' => $anggota_id,
                            'alamat' => $request->alamat,
                            'domisili' => !empty($request->alamat2) ? 0 : 1,
                            'wilayah_id' => $request->wilayah_id,
                            'pos' => $request->kode_pos,
                        ]
                    );
                    if(!empty($request->alamat2)){
                        array_push($alamat,
                        [
                            'anggota_id' => $anggota_id,
                            'alamat' => $request->alamat2,
                            'domisili' => 1,
                            'wilayah_id' => $request->wilayah_id2,
                            'pos' => $request->kode_pos2,
                        ]);
                    }
                    AnggotaAlamat::insert($alamat);
                }

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
                        'keterangan' => 'Simpanan Pokok',
                        'nominal' => $request->pokok,
                        'journal_id' => settings()->get('simpanan_pokok_journal'),
                    ),
                    array(
                        'keterangan' => 'Simpanan Wajib',
                        'nominal' => $request->wajib,
                        'journal_id' => settings()->get('simpanan_wajib_journal'),
                    ),
                );

                $total = $request->total;

                if(!empty($request->sukarela)){
                    $simla = array(
                        'keterangan' => 'Simpanan Sukarela',
                        'nominal' => $request->sukarela,
                        'akun' => settings()->get('simpanan_sukarela_journal'),
                    );
                    array_push(
                        $program, $simla);
                }

                $no_transaksi = $request->transaksi_ref;

                $transaksi = new Transaksi();
                $transaksi->nomor = $no_transaksi;
                $transaksi->anggota_id = $anggota_id;
                $transaksi->service = 'pendaftaran';
                $transaksi->sub_service = null;
                $transaksi->jenis = 'pendaftaran';
                $transaksi->total = $request->total;
                $transaksi->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->teller_id  = auth()->guard('admin')->user()->id;
                $transaksi->save();

                
                $pay_wajib = new SimkopTransaksi();
                $pay_wajib->anggota_id  = $anggota_id;
                $pay_wajib->periode = Carbon::parse($request->tgl)->format('Y-m-d');
                $pay_wajib->jumlah = $request->wajib;
                $pay_wajib->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->simkop()->save($pay_wajib);
                
                // $wallet = new Wallet();
                // $wallet->anggota_id = $anggota_id;
                // $wallet->wajib = 100000;
                // $wallet->pokok = 200000;
                // $wallet->sosial = 5000;
                // $wallet->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');

                if(!empty($request->sukarela))
                {
                    $simla = new SimlaTransaksi();
                    $simla->anggota_id = $anggota_id;
                    $simla->type = 'deposit';
                    $simla->jumlah = (int)$request->sukarela;
                    $simla->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                    $transaksi->simla()->save($simla);
                }

                foreach($program as $p)
                {
                    $line = new TransaksiLine();
                    $line->jumlah = $p['nominal'];
                    $line->nama = $p['keterangan'];
                    $line->jenis = 'pemasukan';
                    $line->akun_id = $p['journal_id'];
                    $line->user_id = auth()->guard('admin')->user()->id;
                    $line->tgl = Carbon::parse($request->tgl)->format('Y-m-d');
                    $transaksi->line()->save($line);
                }

                $payment = new Payment();
                $payment->payment_method_id = 1;
                $payment->type = 'inbound';
                $payment->jumlah = $request->total;
                $payment->tgl_bayar = Carbon::now();

                $transaksi->payment()->save($payment);
                
            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Error Menyimpan Data Transaksi',
                    'log' => $e,
                ]);
            }

            DB::commit();
            // return response()->json([
            //     'fail' => false,
            // ]);
            return redirect()->route('anggota.show', $anggota_id);
        // }
    }



    public function show($id, $state = '', Request $request)
    {
        $keyword = $request->search;
        if($state == '' || $state == 'simpanan' || $state == 'pembiayaan'){
            $anggota = Anggota::with(['alamat'])->where('anggota_id', $id)->first();

            if($state == ''){
                
                $dataList = Transaksi::with(['payment' => function($q){
                    $q->select(['payment_method_id', 'status', 'jumlah']);
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
                    $q->select(['payment_method_id', 'status', 'jumlah']);
                }])
                ->where(function($q){
                    $q->where('transaksi.service', 'pendaftaran')
                    ->orWhere('transaksi.service', 'simpanan');
                })
                ->where('transaksi.anggota_id', $anggota->anggota_id)
                ->orderBy('transaksi.tgl', 'DESC')
                ->paginate(10);

                // $wallet = Wallet::where('anggota_id', $id)->first();
                $overview = collect([
                    // [
                    //     'program' => 'Simpanan Pokok',
                    //     'saldo' => (int)$wallet->pokok,
                    //     'akun' => 3,
                    // ],
                    [
                        'program' => 'Simpanan Wajib',
                        'saldo' => SimpananHelp::getSaldoSimkop($anggota->anggota_id),
                        'akun' => settings()->get('simpanan_wajib_journal'),
                    ],
                    [
                        'program' => 'Simpanan Pokok',
                        'saldo' => SimpananHelp::getSaldoPokok($anggota->anggota_id),
                        'akun' => settings()->get('simpanan_pokok_journal'),
                    ],
                    [
                        'program' => 'Simpanan Sukarela',
                        'saldo' => SimpananHelp::getSaldoSimla($anggota->anggota_id),
                        'akun' => settings()->get('simpanan_sukarela_journal'),
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

    
    public function edit($id, Request $request)
    {
        if($id){
            $data = Anggota::with(['alamat'])->where('anggota_id', $id)->first();

            return Inertia::render('Anggota/Edit',[
                'data' => $data
            ]);
        }
        
        return abort(404);

    }

    
    public function update(Request $request)
    {
        // dd($request->all());
            DB::beginTransaction();
            try{
                
                $anggota = Anggota::where('anggota_id', $request->anggota_id)->first();
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

                $anggota->nip = $request->nip;
                $anggota->tipe = (int)$request->tipe;
                $anggota->golongan = (int)$request->golongan;

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


                $alamat = AnggotaAlamat::where('anggota_id', $anggota->anggota_id)->get();
                if(!empty($request->alamat)){
                    $alamat = AnggotaAlamat::updateOrCreate(
                        [
                            'anggota_id' => $anggota->anggota_id,
                            'alamat' => $request->alamat,
                            'domisili' => !empty($request->alamat2) ? 0 : 1,
                            'wilayah_id' => $request->wilayah_id,
                            'pos' => $request->kode_pos,
                        ],
                        [
                            'anggota_id' => $anggota->anggota_id,
                            'alamat' => $request->alamat,
                            'domisili' => !empty($request->alamat2) ? 0 : 1,
                            'wilayah_id' => $request->wilayah_id,
                            'pos' => $request->kode_pos,
                        ]
                    );


                    if(!empty($request->alamat2)){
                        $alamat2 = AnggotaAlamat::updateOrCreate([
                            'anggota_id' => $anggota->anggota_id,
                            'alamat' => $request->alamat2,
                            'domisili' => 1,
                            'wilayah_id' => $request->wilayah_id2,
                            'pos' => $request->kode_pos2,
                        ],[
                            
                            'anggota_id' => $anggota->anggota_id,
                            'alamat' => $request->alamat2,
                            'domisili' => 1,
                            'wilayah_id' => $request->wilayah_id2,
                            'pos' => $request->kode_pos2,
                        ]
                        );
                    }
                }

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'errors' => $e,
                    'pesan' => 'Error Menyimpan Data Anggota',
                ]);
            }

            DB::commit();
            return redirect()->route('anggota.show', $anggota->anggota_id);
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


    public function import(){
        $filepath = public_path('anggota.csv');
        // Reading file
        $file = fopen($filepath, "r");
        $importData_arr = array(); // Read through the file and store the contents as an array
        $i = 0;
        //Read the contents of the uploaded file 
        while (($filedata = fgetcsv($file, 1000, ";")) !== FALSE) {
            $num = count($filedata);
            if ($i == 0) {
            $i++;
            continue;
            }
            for ($c = 0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata[$c];
            }
            $i++;
        }
        fclose($file);
        // dd($importData_arr);
        $j = 0;
        $no = 1;
        foreach ($importData_arr as $importData) {
            $nip = $importData[0];
            $nama = $importData[1];
            $golongan = $importData[2];
            $jk = $importData[3];
            $j++;
            DB::beginTransaction();
            try{

                $anggota_id = GeneralHelp::generate_kd_anggota(1);
                
                $anggota = new Anggota();
                $anggota->anggota_id =  $anggota_id;
                $anggota->nama = $nama;
                $anggota->jk = $jk;

                $anggota->nip = $nip;
                $anggota->tipe = 1;
                $anggota->golongan = (int)$golongan;
                $anggota->cabang_id = 1;
                $anggota->status = 1;
                $anggota->tgl_gabung = '2022-02-25';
                $anggota->created_at = Carbon::parse('2022-02-25')->format('Y-m-d H:i:s');
                $anggota->save();

                $wajib_amount = $this->getGolongan($golongan);
                
                $program = array(
                    array(
                        'keterangan' => 'Simpanan Pokok',
                        'nominal' => 150000,
                        'journal_id' => settings()->get('simpanan_pokok_journal'),
                    ),
                    array(
                        'keterangan' => 'Simpanan Wajib',
                        'nominal' => $wajib_amount,
                        'journal_id' => settings()->get('simpanan_wajib_journal'),
                    ),
                );

                $total = 150000 + $wajib_amount;

                $no_transaksi = 'SK/220225/'. sprintf("%05s", $no);
                $transaksi = new Transaksi();
                $transaksi->nomor = $no_transaksi;
                $transaksi->anggota_id = $anggota_id;
                $transaksi->service = 'pendaftaran';
                $transaksi->sub_service = null;
                $transaksi->jenis = 'pendaftaran';
                $transaksi->total = $total;
                $transaksi->tgl = Carbon::parse('2022-02-25')->format('Y-m-d H:i:s');
                $transaksi->teller_id  = auth()->guard('admin')->user()->id;
                $transaksi->save();

                foreach($program as $p)
                {
                    $line = new TransaksiLine();
                    $line->jumlah = $p['nominal'];
                    $line->nama = $p['keterangan'];
                    $line->jenis = 'pemasukan';
                    $line->akun_id = $p['journal_id'];
                    $line->user_id = auth()->guard('admin')->user()->id;
                    $line->tgl = '2022-02-25';
                    $transaksi->line()->save($line);
                }

                $pay_wajib = new SimkopTransaksi();
                $pay_wajib->anggota_id  = $anggota_id;
                $pay_wajib->periode = Carbon::parse('2022-02-25')->format('Y-m-d');
                $pay_wajib->jumlah = $wajib_amount;
                $pay_wajib->created_at = Carbon::parse('2022-02-25')->format('Y-m-d H:i:s');
                $transaksi->simkop()->save($pay_wajib);

                $payment = new Payment();
                $payment->payment_method_id = 1;
                $payment->type = 'inbound';
                $payment->status = 'paid';
                $payment->jumlah = $total;
                $payment->tgl_bayar = Carbon::parse('2022-02-25')->format('Y-m-d H:i:s');

                $transaksi->payment()->save($payment);
                $no++;


            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }
            DB::commit();
        }
        echo 'done';
    }

    private function getGolongan($gol){
        $wajib = 0;
        if($gol == 1){
            $wajib = 20000;
        }else if($gol == 2){
            $wajib = 25000;
        }else if($gol == 3){
            $wajib = 50000;
        }else if($gol == 4){
            $wajib = 100000;
        }else if($gol == 5){
            $wajib = 150000;
        }else if($gol == 6){
            $wajib = 200000;
        }else if($gol == 7){
            $wajib = 250000;
        }else{
            $wajib = 300000;
        }

        return $wajib;

    }

}
