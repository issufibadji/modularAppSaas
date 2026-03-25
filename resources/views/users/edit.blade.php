@extends('layouts.app') <!-- Você pode usar seu layout base aqui -->

@section('content')
    <div class="container">
        <h1>Editar Usuário</h1>
        <div class="panel panel-inverse">
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
                <form class="form" action="{{ route('users.update', $user->uuid) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="name">Nome</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Senha (deixe em branco para manter)</label>
                        <input type="text" name="password" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="email_verified_at">Email Verificado</label>
                        <select name="email_verified_at" class="form-control">
                            <option value="" {{ is_null($user->email_verified_at) ? 'selected' : '' }}>Não Verificado
                            </option>
                            <option value="{{ now() }}" {{ !is_null($user->email_verified_at) ? 'selected' : '' }}>
                                Verificado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="active_2fa">Autenticação em Dois Fatores (2FA)</label>
                        <select name="active_2fa" class="form-control">
                            <option value="1" {{ $user->active_2fa ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ !$user->active_2fa ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="active">Status</label>
                        <select name="active" class="form-control">
                            <option value="1" {{ $user->active ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ !$user->active ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <!-- Adicionar outros campos de acordo com a necessidade -->
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
@endsection
