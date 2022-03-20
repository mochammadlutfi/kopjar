<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use App\Models\Admin;
use Auth;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
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
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $dataList = Admin::with('roles')
        ->when($request->search, function($query, $search){
            $query->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('email', 'LIKE', '%' . $search . '%');
        })
        ->orderBy('id', 'desc')->paginate(6);
 
        return Inertia::render('Staff/index', [
            'dataList' => $dataList
        ]);
    }

    
    public function profile(Request $request)
    {

        $user = Auth::user('admin');

        return Inertia::render('Staff/profile', [
            'user' => $user->only('name', 'email'),
        ]);
    }


    public function updateProfile(Request $request)
    {
        $user = Auth::user('admin');
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        if($user){
            return redirect()->back()->with('message', 'Data Berhasil Diupdate!');
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $roles = Role::latest()->get();

        return Inertia::render('Staff/Form', [
            'roles' => $roles,
            'editMode' => false,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('base::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('base::edit');
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
        //
    }
}
