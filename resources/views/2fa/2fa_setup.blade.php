@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Configuração de Autenticação em Dois Fatores (2FA)</h1>


        <div class="panel panel-inverse col-12">
            <div class="panel-heading">
                <h5 class="panel-title">Usuários</h5>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="panel-body">
                @if ($user->active_2fa && $user->google2fa_secret !== null)
                    <div class="alert alert-success mb-3">O 2FA está atualmente ativado para sua conta.</div>
                    <form action="{{ route('2fa.disable') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Desativar 2FA</button>
                    </form>
                @else
                    <form action="{{ route('2fa.setup.post') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Escaneie este QR Code com seu aplicativo de autenticação:</label>
                            <div>
                                {!! $qrCodeUrl !!} <!-- Renderiza o SVG diretamente -->
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Ou use este código secreto: {{ $secret }}</label>
                            <input type="hidden" name="secret" value="{{ $secret }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="2fa_code">Código de Autenticação</label>
                            <input type="text" name="2fa_code" class="form-control" required>
                            @if ($errors->has('2fa_code'))
                                <span class="text-danger">{{ $errors->first('2fa_code') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Ativar 2FA</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
