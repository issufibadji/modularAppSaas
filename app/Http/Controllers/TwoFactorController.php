<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FAQRCode\Google2FA as Google2FAQrCode;

class TwoFactorController extends Controller
{
    public function showForm()
    {
        return view('2fa.2fa');
    }

    public function verify(Request $request)
    {
        $request->validate([
            '2fa_code' => 'required|digits:6',
        ], [
            '2fa_code.required' => 'O código de autenticação é obrigatório.',
            '2fa_code.digits' => 'O código de autenticação deve ter exatamente 6 dígitos.',
        ]);

        $google2fa = new Google2FA();
        $user = Auth::user();

        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->input('2fa_code'));

        if ($valid) {
            $request->session()->put('2fa_passed', true);
            return redirect()->intended('/')->with('success', 'Autenticação em dois fatores bem-sucedida!');
        }

        return redirect()->route('2fa.form')->withErrors(['2fa_code' => 'Código de autenticação inválido.']);
    }

    // Mostrar o formulário de configuração do 2FA
    public function showSetupForm()
    {
        if (!Auth::user()->can('youself')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }

        $user = Auth::user();
        $google2fa = new Google2FA();

        // Gerar uma chave secreta para o usuário
        $secret = $google2fa->generateSecretKey();
        $qrCodeUrl = (new Google2FAQrCode())->getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('2fa.2fa_setup', compact('secret', 'qrCodeUrl', 'user'));
    }

    // Configurar e salvar o 2FA
    public function setup(Request $request)
    {
        if (!Auth::user()->can('youself')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }

        $user = Auth::user();
        $request->validate([
            '2fa_code' => 'required|digits:6',
            'secret' => 'required'
        ]);

        $google2fa = new Google2FA();

        // Verificar se o código inserido é válido
        $valid = $google2fa->verifyKey($request->input('secret'), $request->input('2fa_code'));

        if ($valid) {
            // Salvar a chave secreta e ativar o 2FA para o usuário
            $user->google2fa_secret = $request->input('secret');
            $user->active_2fa = true;
            $user->save();

            $request->session()->put('2fa_passed', true);

            return redirect()->back()->with('success', 'Autenticação em dois fatores ativada com sucesso!');
        }

        return redirect()->route('2fa.setup')->withErrors(['2fa_code' => 'Código de autenticação inválido.']);
    }

    // Desativar o 2FA
    public function disable()
    {
        $user = Auth::user();
        $user->google2fa_secret = null;
        $user->active_2fa = false;
        $user->save();

        return redirect()->back()->with('success', 'Autenticação em dois fatores desativada com sucesso!');
    }
}
