<?php

namespace App\Http\Controllers\Staff\Accounting;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Storage;
use App\Models\Accounting\Bank;

class BankController extends Controller
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
        $data = Bank::orderBy('created_at', 'Desc')->paginate(10);

        return Inertia::render('Accounting/Config/Bank',[
            'dataList' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'account_name' => 'required',
            'account_no' => 'required',
        ];

        $pesan = [
            'name.required' => 'Nama Bank Wajib Diisi!',
            'code.required' => 'Kode Bank Wajib Diisi!',
            'account_name.required' => 'Nama Rekening Wajib Diisi!',
            'account_no.required' => 'No Rekening Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $data = new Bank();
                    $data->name = $request->name;
                    $data->code = $request->code;
                    $data->account_name = $request->account_name;
                    $data->account_no = $request->account_no;
                    $data->status = $request->status;
                    $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('accounting.config.bank.index');
        }
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
            'code' => 'required',
            'account_name' => 'required',
            'account_no' => 'required',
        ];

        $pesan = [
            'name.required' => 'Nama Bank Wajib Diisi!',
            'code.required' => 'Kode Bank Wajib Diisi!',
            'account_name.required' => 'Nama Rekening Wajib Diisi!',
            'account_no.required' => 'No Rekening Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $data = Bank::find($request->id);
                    $data->name = $request->name;
                    $data->code = $request->code;
                    $data->account_name = $request->account_name;
                    $data->account_no = $request->account_no;
                    $data->status = $request->status;
                    if($data->logo != $request->logo){
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
            return redirect()->route('accounting.config.bank.index');
        }
    }

    private function uploadFiles($file, $name){
        $file_name = $name. '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->putFileAs(
            'uploads/bank',
            $file,
            $file_name
        );
        
        return 'uploads/bank/'.$file_name;
    }


    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $data = Bank::find($id);
        $cek = Storage::disk('public')->exists($data->logo);
        if($cek)
        {
            Storage::disk('public')->delete($data->logo);
        }
        $hapus_db = Bank::destroy($data->id);
        if($hapus_db)
        {
            return redirect()->route('accounting.config.bank.index');
        }
    }
}
