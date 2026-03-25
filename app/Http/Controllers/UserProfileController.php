<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserProfileController extends Controller
{
     // Mostrar o perfil do usuário
     public function show()
     {
        if (!Auth::user()->can('youself')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  

         $user = Auth::user();
         return view('profile.show', compact('user'));
     }
 
     // Atualizar as informações do usuário
     public function update(Request $request)
     {
        if (!Auth::user()->can('youself')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  
        
         $user = Auth::user();
         
         $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
             'password' => 'nullable|string|min:8|confirmed',
         ]);
 
         $user->name = $request->name;
         $user->email = $request->email;
         if ($request->filled('password')) {
             $user->password = Hash::make($request->password);
         }
 
         $user->save();
 
         return redirect()->route('profile.show')->with('success', 'Perfil atualizado com sucesso!');
     }
}
