<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('roles-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {   
        if (!Auth::user()->can('roles-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('roles-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create($request->all());

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role criada com sucesso');
    }

    public function edit(Role $role)
    {
        if (!Auth::user()->can('roles-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        if (!Auth::user()->can('roles-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role atualizada com sucesso');
    }

    public function destroy(Role $role)
    {
        if (!Auth::user()->can('roles-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  
        
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deletado com sucesso');
    }
    
}
