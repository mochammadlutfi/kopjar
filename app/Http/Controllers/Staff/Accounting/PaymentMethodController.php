<?php

namespace App\Http\Controllers\Staff\Accounting;

use App\Models\Accounting\PaymentMethod;
use App\Models\Accounting\Bank;


use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Storage;

class PaymentMethodController extends Controller
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
        $data = PaymentMethod::with(['bank'])
        ->when($request->search, function($query, $search){
            $query->where('name', 'LIKE', '%' . $search . '%');
        })
        ->orderBy('id', 'desc')->paginate(10);
       

        $bank = Bank::orderBy('name', 'ASC')->get();

        return Inertia::render('Accounting/Config/PaymentMethod', [
            'dataList' => fn () => $data,
            'bankList' => fn () => $bank
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // dd($request->all());
        if($request->type == 'offline'){
            $rules = [
                'name' => 'required',
            ];

            $pesan = [
                'name.required' => 'Nama Metode Pembayaran Wajib Diisi!',
            ];
        }else{
            $rules = [
                'bank_id' => 'required',
            ];

            $pesan = [
                'bank_id.required' => 'Bank Wajib Diisi!',
            ];
        }
            

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $data = new PaymentMethod();
                    $data->name = $request->name;
                    $data->bank_id = $request->bank_id;
                    $data->type = $request->type;
                    $data->status = $request->status;
                    $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('accounting.payment_method.index');
        }
    }

        /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function update(Request $request)
    {
        if($request->type == 'online'){
            $rules = [
                'name' => 'required',
            ];

            $pesan = [
                'name.required' => 'Nama Metode Pembayaran Wajib Diisi!',
            ];
        }else{
            $rules = [
                'bank_id' => 'required',
            ];

            $pesan = [
                'bank_id.required' => 'Bank Wajib Diisi!',
            ];
        }

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $data = PaymentMethod::find($request->id);
                    $data->name = $request->name;
                    $data->bank_id = $request->bank_id;
                    $data->type = $request->type;
                    $data->status = $request->status;
                    $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('accounting.payment_method.index');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $data = PaymentMethod::find($id);
        $hapus_db = PaymentMethod::destroy($data->id);
        if($hapus_db)
        {
            return redirect()->route('accounting.payment_method.index');
        }
    }
    

    public function data(Request $request)
    {
        $keyword = $request->q;
        $data = PaymentMethod::where(function($q) use ($keyword){
            $q->where('name', 'like', '%' . $keyword . '%');
        })
        ->orderBy('name', 'DESC')->get();
        
        return response()->json($data);
    }
}