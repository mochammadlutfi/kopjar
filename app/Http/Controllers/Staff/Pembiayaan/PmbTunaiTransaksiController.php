<?php

namespace App\Http\Controllers\Staff\Pembiayaan;

use App\Models\Pembiayaan\PmbTunai;
use App\Models\Pembiayaan\PmbTunaiDetail;
use App\Models\Pembiayaan\PmbTransaksi;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Helpers\GeneralHelp;

class PmbTunaiTransaksiController extends Controller
{

    
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $keyword = $request->search;
        $dataList = PmbTransaksi::orderBy('created_at', 'DESC')
        ->paginate(20);

        return Inertia::render('Pembiayaan/Tunai/IndexPembayaran', [
            'dataList' => $dataList
        ]);
    }


        /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function create()
    {
        return Inertia::render('Pembiayaan/Tunai/FormPembayaran');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $rules = [
            'anggota_id' => 'required',
            'amount' => 'required',
            'tenor' => 'required',
        ];

        $pesan = [
            'anggota_id.required' => 'Anggota Koperasi Wajib Diisi!',
            'amount.required' => 'Jumlah Pengajuan Wajib Diisi!',
            'tenor.required' => 'Durasi Pembiayaan Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            DB::beginTransaction();
            try{

                $data = new PmbTunaiTransaksi();
                $data->ref = GeneralHelp::generate_kd_pembiyaan('tunai');
                $data->anggota_id = $request->anggota_id;
                $data->durasi = $request->tenor;
                $data->jumlah = $request->amount;
                $data->jumlah_bunga = $request->bagi_hasil;
                $data->angsuran_pokok = $request->angsuran_pokok;
                $data->angsuran_bunga = $request->angsuran_total;
                $data->status = 0;
                $data->biaya_admin = $request->admin_fee;
                $data->save();

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
            ]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {

        $data = PmbTunai::with([
            'anggota' => function($q) {
                $q->select('anggota_id', 'nama', 'no_hp');
            },
        ])
        
        ->where('id', $id)->first();
        
        return Inertia::render('Pembiayaan/Tunai/Show',[
            'data' => $data
        ]);
        
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('pembiayaan::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function riwayat(Request $request)
    {
        if ($request->ajax()) {
            $keyword  = $request->keyword;
            $tgl_mulai = Date::parse($request->tgl_mulai);
            $tgl_akhir = Date::parse($request->tgl_akhir);

            $data = PmbTunaiBayar::
            join('pmb_tunai_detail', 'pmb_tunai_detail.id', '=', 'pmb_tunai_bayar.pmb_tunai_detail_id')
            ->join('pmb_tunai', 'pmb_tunai.id', '=', 'pmb_tunai_detail.pmb_tunai_id')
            ->join('anggota', 'pmb_tunai.anggota_id', '=', 'anggota.anggota_id')
            ->join('admins', 'pmb_tunai_bayar.teller', '=', 'admins.id')
            ->join('anggota as tellers', 'admins.anggota_id', '=', 'tellers.anggota_id')
            ->select('pmb_tunai_bayar.*', 'pmb_tunai_detail.pmb_tunai_id', 'pmb_tunai_detail.angsuran_ke', 'pmb_tunai.no_pembiayaan', 'pmb_tunai.anggota_id', 'anggota.nama as anggota_nama', 'admins.anggota_id', 'tellers.nama as teller_nama')
            // ->whereHas('transaksi_kas', function ($query) use ($keyword) {
            //     return $query->where('akun_id', 14);
            // })
            // ->whereHas('anggota', function ($query) use ($keyword) {
            //     return $query->where('anggota_id', 'like', '%' . $keyword . '%')
            //     ->orWhere('nama', 'like', '%' . $keyword . '%');
            // })
            ->whereBetween('tgl_bayar', [$tgl_mulai, $tgl_akhir])
            ->orderBy('tgl_bayar', 'DESC')
            ->paginate(20);

            return response()->json($data);
        }
        
        return view('pembiayaan::tunai.riwayat');
    }



    /**
     * Update the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function updateState(Request $request)
    {
        DB::beginTransaction();
        try{

            $data = PmbTunai::where('id', $request->id)->first();
            $data->status = $request->status;
            $data->save();

            if($request->status == 1){


                // $data->line()->create();
            }


        }catch(\QueryException $e){
            DB::rollback();
            return redirect()->back();
        }

        DB::commit();
        return redirect()->route('pembiayaan.tunai.show', $data->id);
    }
}
