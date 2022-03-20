<?php

namespace App\Http\Controllers\Staff\Pembiayaan;

use App\Models\Pembiayaan\PmbTunai;
use App\Models\Pembiayaan\PmbTunaiDetail;
use App\Models\Pembiayaan\PmbTunaiBayar;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Helpers\GeneralHelp;
use Carbon\Carbon;
class PmbTunaiController extends Controller
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
        // $status  = isset($request->status) ? $request->status : "pending";
        $dataList = PmbTunai::with([
            'anggota' => function($query) use ($keyword) {
                $query->select('anggota_id','nama');
            },
        ])
        ->whereHas('anggota', function ($query) use ($keyword) {
            return $query->where('anggota_id', 'like', '%' . $keyword . '%')
            ->orWhere('nama', 'like', '%' . $keyword . '%');
        })
        ->orderBy('created_at', 'DESC')
        ->paginate(20);

        return Inertia::render('Pembiayaan/Tunai/Index', [
            'dataList' => $dataList
        ]);
    }


        /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function create()
    {
        return Inertia::render('Pembiayaan/Tunai/FormPengajuan');
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

                $data = new PmbTunai();
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
            'line' => function($q) {
                $q->with(['transaksi']);
            },
        ])->where('id', $id)->first();
        
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
    public function destroy($id)
    {
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
            if($request->status == 1){
                $data->staff_id = auth()->guard('admin')->user()->id;
            }
            $data->save();

            if($request->status == 1){
                $line = [];
                for($i=1; $i <= $data->durasi; $i++){
                    $line[] = [
                        'angsuran_ke' => $i,
                        'amount' => $data->angsuran_total,
                        'tgl_tempo' => Carbon::now()->addMonth($i),
                    ];
                }
                $data->line()->createMany($line);
            }else if($request->status == 2){
                $data->transaksi()->create([
                    'type' => 'outbond',
                    'method' => 'Tunai',
                    'amount' => $data->jumlah - $data->admin_fee,
                    'tgl_bayar' => Carbon::now(),
                    'staff_id' => auth()->guard('admin')->user()->id,
                ]);
            }


        }catch(\QueryException $e){
            DB::rollback();
            return redirect()->back();
        }

        DB::commit();
        return redirect()->route('pembiayaan.tunai.show', $data->id);
    }
}
