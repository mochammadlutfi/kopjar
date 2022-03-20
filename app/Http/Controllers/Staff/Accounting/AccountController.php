<?php

namespace App\Http\Controllers\Staff\Accounting;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Accounting\Account;
use App\Models\Accounting\AccountGroup;

class AccountController extends Controller
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
        $data = Account::with('group')->orderBy('created_at', 'Desc')->paginate(10);
        $group = AccountGroup::with('child')->where('parent_id', NULL)->get();
        return Inertia::render('Accounting/Account/Index',[
            'dataList' => $data,
            'group' => $group
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
        ];

        $pesan = [
            'name.required' => 'Nama Akun Wajib Diisi!',
            'code.required' => 'Kode Akun Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $data = new Account();
                    $data->name = $request->name;
                    $data->code = $request->code;
                    $data->status = $request->status;
                    $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('accounting.config.account.index');
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
                    $data = Account::find($request->id);
                    $data->name = $request->name;
                    $data->code = $request->code;
                    $data->status = $request->status;
                    $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('accounting.config.account.index');
        }
    }

    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $hapus_db = Account::destroy($data->id);
        if($hapus_db)
        {
            return redirect()->route('accounting.config.account.index');
        }
    }
}
