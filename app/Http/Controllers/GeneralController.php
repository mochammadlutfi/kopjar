<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wilayah\Kelurahan;
class GeneralController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getWilayah(Request $request)
    {
        if($request->has('q')){
            $cari = $request->q;
            $fetchData = Kelurahan::where('name','LIKE',  '%' . $cari .'%')->get();
            $data = array();
            // dd($fetchData);
            foreach($fetchData as $row) {
                $data[] = array(
                    "id" =>$row->id,
                    "text" => ucwords(strtolower($row->kecamatan->kota->provinsi->name)).', '. ucwords(strtolower($row->kecamatan->kota->name)).', Kec. '.ucwords(strtolower($row->kecamatan->name)).', '.ucwords(strtolower($row->name)),
                );
            }
            return response()->json($data);
        }
    }
}
