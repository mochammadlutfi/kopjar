<?php

namespace App\Http\Controllers\Staff\Accounting;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;

use Carbon\Carbon;
use App\Helpers\GeneralHelp;
use App\Models\Accounting\Account;
use App\Models\Accounting\Cash;
use App\Models\Accounting\CashLine;
use App\Models\Accounting\Payment;
use DB;
use Illuminate\Support\Facades\Validator;
class CashController extends Controller
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

        $data = Cash::with(['payment'=> function($q){
            $q->with('payment_method:id,name');
        }])->latest()->paginate(10);
        
        return Inertia::render('Accounting/Cash/Index',[
            'dataList' => $data
        ]);
    }

    
    public function create(Request $request)
    {
        
        $journal = Account::select('id', DB::raw("CONCAT(code ,' - ',name) as name"))->orderBy('created_at', 'Desc')->get();

        $ref = GeneralHelp::generate_transaksi_kas_ref();

        return Inertia::render('Accounting/Cash/Form',[
            'nomor' => $ref,
            'editMode' => false,
            'journal' => $journal,
        ]);
    }

    
    public function store(Request $request)
    {
        $rules = [
            'type' => 'required',
            'tgl' => 'required',
            'payment_method_id' => 'required',
        ];

        $pesan = [
            'type.required' => 'ID Anggota Wajib Diisi!',
            'payment_method_id.required' => 'Metode Pembayaran Wajib Diisi!',
            'tgl.required' => 'Tanggal Transaksi Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{

            DB::beginTransaction();
            try{

                $transaksi = new Cash();
                $transaksi->nomor = $request->nomor;
                $transaksi->ref = $request->ref;
                $transaksi->type = $request->type;
                $transaksi->note = $request->note;
                $transaksi->total = $request->total;
                $transaksi->payment_method_id = $request->payment_method_id;
                $transaksi->staff_id = auth()->guard('admin')->user()->id;
                $transaksi->tgl = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $transaksi->save();

                foreach($request->lines as $l){
                    $line = new CashLine();
                    $line->journal_id = $l["journal_id"];
                    $line->jumlah = (int) $l["jumlah"];
                    $line->created_at = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                    $transaksi->line()->save($line);
                }

                $payment = new Payment();
                $payment->payment_method_id = $request->payment_method_id;
                $payment->tgl_bayar = Carbon::parse($request->tgl)->format('Y-m-d H:i:s');
                $payment->jumlah = (int)$request->total;
                $payment->type = $request->type;
                $payment->status = $request->payment_method_id == 1 ? 'paid' : 'unpaid';
                $transaksi->payment()->save($payment);

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'pesan' => 'Terjadi Error Pada Penyimpanan Data',
                    'error' => $e,
                ]);
            }

            DB::commit();
            return redirect()->route('accounting.cash.show', $transaksi->id);
        }
    }

    
    public function show($id, Request $request)
    {
        $data = Cash::with(['line' => function($q){
            $q->with('journal:id,name');
        }, 'payment'=> function($q){
            $q->with('payment_method:id,name');
        }])->where('id', $id)->first();

        return Inertia::render('Accounting/Cash/Show',[
            'data' => $data,
        ]);
    }

}
