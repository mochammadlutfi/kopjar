<?php

namespace App\Http\Controllers\Staff\Accounting;

use App\Models\TransaksiLine;
use App\Models\Transaksi;
use App\Models\Anggota;


use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Storage;


use App\Helpers\Collection;
use Carbon\Carbon;


class PotongGajiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request)
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
                    'list' => $transaksi
                ];
            }
        }

        $data = (new Collection($items))->paginate(10);


        return Inertia::render('Accounting/PotongGaji/Index',[
            'dataList' => $data
        ]);
    }


    
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function show($anggota_id, Request $request)
    {
        $today = Carbon::now()->format('m');
        $anggota = Anggota::where('anggota_id', $anggota_id)->first();
        
        $transaksi = Transaksi::whereHas(
            'payment', function($q){
                return $q->where('payment_method_id', 2);
            },
        )
        ->where('anggota_id', $anggota_id)
        ->whereMonth('tgl', $today)->get();
        

        return Inertia::render('Accounting/PotongGaji/Show',[
            'anggota' => $anggota,
            'transaksi' => $transaksi
        ]);
    }

    
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function exportExcel($anggota_id, Request $request)
    {
        $today = Carbon::now()->format('m');
        $anggota = Anggota::where('anggota_id', $anggota_id)->first();
        
        $transaksi = Transaksi::whereHas(
            'payment', function($q){
                return $q->where('payment_method_id', 2);
            },
        )
        ->where('anggota_id', $anggota_id)
        ->whereMonth('tgl', $today)->get();
        

        return Inertia::render('Accounting/PotongGaji/Show',[
            'anggota' => $anggota,
            'transaksi' => $transaksi
        ]);
    }


        /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $pesan = [
            'name.required' => 'Nama Brand Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $data = ProductBrand::find($request->id);
                    $data->name = $request->name;
                    $data->state = $request->state;
                    if($data->image != $request->image){
                        if(Storage::disk('public')->exists($data->image))
                        {
                            Storage::disk('public')->delete($data->image);
                        }
                        if($request->hasFile('image')){
                            $data->image = $this->uploadFiles($request->file('image'), Str::slug($request->name, '-'));
                        }else{
                            $data->image = null;
                        }
                    }
                    $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('admin.product.brand.index');
        }
    }


    private function uploadFiles($file, $name){
        $file_name = $name. '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->putFileAs(
            'uploads/product/brands',
            $file,
            $file_name
        );
        
        return 'uploads/product/brands/'.$file_name;
    }


    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $data = ProductBrand::find($id);
        $cek = Storage::disk('public')->exists($data->thumbnail);
        if($cek)
        {
            Storage::disk('public')->delete($data->thumbnail);
        }
        $hapus_db = ProductBrand::destroy($data->id);
        if($hapus_db)
        {
            return redirect()->route('admin.product.brand.index');
        }
    }
}