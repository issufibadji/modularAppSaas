@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Meu Perfil</h1>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h5 class="panel-title">Perfil</h5>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="panel-body">

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name">Nome</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password">Nova Senha (opcional)</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="password_confirmation">Confirme a Nova Senha</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
                </form>

                <hr>

                <h3>Configurações de Segurança</h3>
                @if ($user->active_2fa)
                    <p>O 2FA está atualmente ativado para sua conta.</p>
                    <form action="{{ route('2fa.disable') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Desativar 2FA</button>
                    </form>
                @else
                    <p>O 2FA não está ativado para sua conta.</p>
                    <a href="{{ route('2fa.setup') }}" class="btn btn-secondary">Configurar 2FA</a>
                @endif

            </div>
        </div>
    </div>
@endsection
