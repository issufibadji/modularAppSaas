<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('user-roles-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

        $users = User::all();
        $roles = Role::all();
        return view('user_roles.index', compact('users', 'roles'));
    }

    public function assignRole(Request $request, User $user)
    {
        if (!Auth::user()->can('user-roles-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        // Atribuir a role ao usuário
        $role = Role::findOrFail($request->role_id);
        $user->assignRole($role->name);

        return redirect()->route('user_roles.index')->with('success', 'Role atribuída ao usuário com sucesso!');
    }

    public function revokeRole(Request $request, User $user)
    {
        if (!Auth::user()->can('user-roles-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  
        
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        // Obtenha a role a ser revogada
        $role = Role::findOrFail($request->role_id);

        // Remova a role do usuário
        $user->removeRole($role->name);

        return redirect()->route('user_roles.index')->with('success', 'Role revogada do usuário com sucesso!');
    }
}
