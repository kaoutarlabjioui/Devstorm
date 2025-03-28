<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function index(){
        return response()->json(Role::all());
    }

    public function store(Request $request){


        Gate::allows('isAdmin');
        Role::create([
            'role_name' => $request['role_name'],
        ]);

        return response()->json(['success'=>'Role added successfully.']);
    }

    public function show($id){
        return response()->json([Role::find($id)]);
    }
}
