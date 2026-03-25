@extends('layouts.app') <!-- Você pode usar seu layout base aqui -->

@section('content')
    <div class="container">
        <h1>Editar Permissão</h1>
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h5 class="panel-title">Permissão</h5>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <form action="{{ route('permissions.update', $permission) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ $permission->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="module" class="form-label">Modulo</label>
                        <input type="text" name="module" id="module" class="form-control"
                            value="{{ $permission->module }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Voltar</a>
                </form>
            </div>
        </div>
    </div>
@endsection
