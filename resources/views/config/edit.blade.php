@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Configuração</h1>
        
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h5 class="panel-title">Configurações</h5>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="panel-body">

                <form method="POST" action="{{ route('config.update', $config->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="key">Chave:</label>
                        <input type="text" name="key" id="key" class="form-control" value="{{ $config->key }}"
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="value">Valor:</label>
                        <textarea name="value" id="value" class="form-control">{{ $config->value }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Descrição:</label>
                        <textarea name="description" id="description" class="form-control">{{ $config->description }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <input type="file" name="archive" id="archive" class="form-control-file">
                    </div>

                    <div class="form-group mb-3">
                        <input type="checkbox" class="form-check-input" {{ $config->required ? 'checked' : '' }}
                            name="require">
                        <label for="require">Obrigatório:</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
@endsection
