<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    public function index()
    {
        if (!Auth::user()->can('user-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

        $users = User::all(); // Obtém todos os usuários, você pode adicionar paginação ou filtros aqui se necessário
        return view('users.index', compact('users')); // Retorna a view de listagem de usuários
    }

    public function create()
    {
        if (!Auth::user()->can('user-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

        return view('users.create');
    }

    public function show(User $user)
    { 
        if (Auth::user()->can('script::yourself user')) {
            return view('users.show', compact('user'));
        } else {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }
    }

    public function edit(User $user)
    {   
        if (!Auth::user()->can('user-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

        return view('users.edit', compact('user'));
    }

    public function destroy(User $user)
    {
        // Lógica para deletar o usuário
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('user-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
        ]);

        $user->givePermissionTo('youself'); // ou 'admin', conforme necessário

        return redirect()->route('users.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function update(Request $request, User $user)
    {
        if (!Auth::user()->can('user-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'active' => 'required|boolean',
            'active_2fa' => 'required|boolean', // Adicionando validação para active_2fa
        ]);

         // Atualizar os campos diretamente
        $data = $request->only(['name', 'email', 'active', 'active_2fa']);

        // Verificar se a senha foi preenchida e hashear antes de atualizar
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Lidar com o campo de verificação de email
        $data['email_verified_at'] = $request->email_verified_at ? now() : null;

        // Atualizar usando o método update()
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function toggleActivation(User $user)
    {
        if (!Auth::user()->can('user-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  
        
        $user->active = !$user->active;

        if(!$user->active)
            $user->google2fa_secret = null;

        $user->save();

        return redirect()->route('users.index')->with('success', 'Status do usuário atualizado!');
    }
}
