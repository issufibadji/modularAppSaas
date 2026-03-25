<?php

namespace App\Http\Controllers;

use App\Models\MenuSideBar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MenuSideBarController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('menu-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        $menus = MenuSideBar::orderBy('order')->get();
        return view('menu.index', compact('menus'));
    }

    public function create()
    {
        if (!Auth::user()->can('menu-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        return view('menu.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('menu-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        $request->validate([
            'description' => 'required|string|max:255',
            'level' => 'required|integer',
            'order' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        MenuSideBar::create($request->all());

        return redirect()->route('menu.index')->with('success', 'Menu criado com sucesso!');
    }

    public function edit(MenuSideBar $menu)
    {
        if (!Auth::user()->can('menu-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        return view('menu.edit', compact('menu'));
    }

    public function update(Request $request, MenuSideBar $menu)
    {
        if (!Auth::user()->can('menu-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        $request->validate([
            'description' => 'required|string|max:255',
            'level' => 'required|integer',
            'order' => 'required|integer',
            'active' => 'required|boolean',
        ]);

        $menu->update($request->all());

        return redirect()->route('menu.index')->with('success', 'Menu atualizado com sucesso!');
    }

    public function destroy(MenuSideBar $menu)
    {
        if (!Auth::user()->can('menu-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   
        
        $menu->delete();

        return redirect()->route('menu.index')->with('success', 'Menu excluído com sucesso!');
    }
}
