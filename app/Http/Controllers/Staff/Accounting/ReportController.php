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


class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function simpanan(Request $request)
    {
        $this_year = Carbon::now()->format('Y');
        $last_year = Carbon::now()->subYear()->format('Y');

        
        $old_pokok = TransaksiLine::where('akun_id', settings()->get('simpanan_pokok_journal'))->whereYear('created_at', $last_year)
        ->sum('jumlah');
        
        $old_wajib = TransaksiLine::where('akun_id', settings()->get('simpanan_wajib_journal'))->whereYear('created_at', $last_year)
        ->sum('jumlah');
        
        $old_sukarela = TransaksiLine::where('akun_id', settings()->get('simpanan_sukarela_journal'))->whereYear('created_at', $last_year)
        ->sum('jumlah');
        
        $pokok_now = TransaksiLine::where('akun_id', settings()->get('simpanan_pokok_journal'))->whereYear('created_at', $this_year)
        ->sum('jumlah');
        
        $wajib_now = TransaksiLine::where('akun_id', settings()->get('simpanan_wajib_journal'))->whereYear('created_at', $this_year)
        ->sum('jumlah');
        
        $sukarela_now = TransaksiLine::where('akun_id', settings()->get('simpanan_sukarela_journal'))->whereYear('created_at', $this_year)
        ->sum('jumlah');
        
        $data = collect([
            [
                'program' => 'Simpanan Pokok',
                'old' => (float)$old_pokok,
                'now' => (float)$pokok_now,
            ],
            [
                'program' => 'Simpanan Wajib',
                'old' => (float)$old_wajib,
                'now' => (float)$wajib_now,
            ],
            [
                'program' => 'Simpanan Sukarela',
                'old' => (float)$old_sukarela,
                'now' => (float)$sukarela_now,
            ],
        ]);
        
        return Inertia::render('Accounting/Reports/Simpanan',[
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function balance_sheet(Request $request)
    {
        $data = Null;

        return Inertia::render('Accounting/Reports/BalanceSheet',[
            'dataList' => $data
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