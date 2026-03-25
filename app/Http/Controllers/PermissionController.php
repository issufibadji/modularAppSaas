<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission as BasePermission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->can('permissions-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        $permissions = BasePermission::all();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        if (!Auth::user()->can('permissions-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        return view('permissions.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('permissions-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        BasePermission::create($request->all());

        return redirect()->route('permissions.index')->with('success', 'Permissão criada com sucesso!');
    }

    public function show(BasePermission $permission)
    {
        if (!Auth::user()->can('permissions-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        return view('permissions.show', compact('permission'));
    }

    public function edit(BasePermission $permission)
    {
        if (!Auth::user()->can('permissions-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, BasePermission $permission)
    {
        if (!Auth::user()->can('permissions-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update($request->all());

        return redirect()->route('permissions.index')->with('success', 'Permissão atualizada com sucesso!');
    }

    public function destroy(BasePermission $permission)
    {
        if (!Auth::user()->can('permissions-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   
        
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permissão excluída com sucesso!');
    }
}
