<?php

namespace App\Http\Controllers\Staff\Accounting;

use App\Models\Accounting\PaymentMethod;


use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Storage;

class PaymentController extends Controller
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
        $data = ProductBrand::withCount(['product'])
        ->when($request->search, function($query, $search){
            $query->where('name', 'LIKE', '%' . $search . '%');
        })
        ->orderBy('id', 'desc')->paginate(6);
       

        return Inertia::render('Backend/Brand/index', [
            'dataList' => fn () => $data
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
                    $data = new ProductBrand();
                    $data->name = $request->name;
                    $data->state = $request->state;
                    if(!empty($request->file('image'))){
                        $data->image = $this->uploadFiles($request->file('image'), Str::slug($request->name, '-'));
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