<?php

namespace App\Http\Controllers;

use App\Models\AppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AppConfigController extends Controller
{
    // Método para listar todas as configurações
    public function index()
    {
        if (!Auth::user()->can('configs-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        $configs = AppConfig::all();
        return view('config.index', compact('configs'));
    }

    public function create()
    {
        if (!Auth::user()->can('configs-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        return view('config.create');
    }

    public function edit(Request $request, AppConfig $config)
    {
        if (!Auth::user()->can('configs-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        return view('config.edit',  compact('config'));
    }

    // Método para criar uma nova configuração
    public function store(Request $request)
    {
        if (!Auth::user()->can('configs-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        $request->validate([
            'key' => 'required|string|max:255|unique:app_configs',
            'value' => 'nullable|string',
            'description' => 'nullable|string',
            'archive' => 'nullable|file|mimes:jpeg,png,mp4,pdf,ico|max:10240', // Defina os tipos de arquivos e o tamanho máximo aqui
        ]);

        // Se houver um arquivo no pedido
        if ($request->hasFile('archive')) {
            $path = $request->file('archive')->store('config_files', 'public');
            $request->merge(['path_archive' => $path]);
            // Extraia a extensão do arquivo
            $request->merge(['extension' => $request->file('archive')->extension()]);
        }

        if($request->require){
            $request->merge(['required' => true]);
        }

        $config = AppConfig::create($request->all());
        return redirect()->route('config.index')->with('success', 'Configuração criada com sucesso!');
    }

    // Método para exibir uma configuração específica
    public function show(Request $request, AppConfig $config)
    {
        if (!Auth::user()->can('configs-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        return view('config.show', compact('config'));
    }

    // Método para atualizar uma configuração existente
    public function update(Request $request, AppConfig $config)
    {
        if (!Auth::user()->can('configs-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        $request->validate([
            'key' => 'required|string|max:255|unique:app_configs,key,' . $config->id,
            'value' => 'nullable|string',
            'description' => 'nullable|string',
            'archive' => 'nullable|file|mimes:jpeg,png,mp4,pdf|max:10240',
        ]);

        // Se houver um novo arquivo no pedido
        if ($request->hasFile('archive')) {
            // Exclua o arquivo antigo
            if ($config->path_archive) {
                Storage::disk('public')->delete($config->path_archive);
            }

            // Armazene o novo arquivo
            $path = $request->file('archive')->store('config_files', 'public');
            $request->merge(['path_archive' => $path]);
            // Extraia a extensão do arquivo
            $request->merge(['extension' => $request->file('archive')->extension()]);
        }

        if($request->require){
            $request->merge(['required' => true]);
        }else{
            $request->merge(['required' => false]);
        }

        $config->update($request->all());

        return redirect()->route('config.index')->with('success', 'Configuração atualizada com sucesso!');
    }

    // Método para excluir uma configuração
    public function destroy(AppConfig $config)
    {
        if (!Auth::user()->can('configs-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   
        
        // Verifique se a configuração tem um arquivo associado
        if ($config->path_archive) {
            // Obtenha o caminho relativo do arquivo a partir da configuração
            $path = $config->path_archive;

            // Use a facade Storage para excluir o arquivo do disco
            Storage::disk('public')->delete($path);
        }

        $config->delete();
        return redirect()->route('config.index')->with('success', 'Configuração excluída com sucesso!');
    }
}
