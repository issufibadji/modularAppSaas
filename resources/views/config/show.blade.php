@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detalhes da Configuração</h1>

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
                <p><strong>ID:</strong> {{ $config->id }}</p>
                <p><strong>Chave:</strong> {{ $config->key }}</p>
                <p><strong>Valor:</strong> {{ $config->value }}</p>
                <p><strong>Descrição:</strong> {{ $config->description }}</p>
                <p><strong>Obrigatório:</strong> {{ $config->required ? 'SIM' : 'NÃO' }}</p>

                <div class="card-text">
                    <strong>Mídia:</strong><br>
                    @if ($config->path_archive)
                        @if (in_array($config->extension, ['jpg', 'jpeg', 'png', 'gif']))
                            <!-- Exibir imagem -->
                            <img src="{{ asset('storage/' . $config->path_archive) }}" alt="Imagem da Configuração"
                                style="max-width: 300px;">
                        @elseif(in_array($config->extension, ['mp4', 'mov']))
                            <!-- Exibir vídeo -->
                            <video width="300" controls>
                                <source src="{{ asset('storage/' . $config->path_archive) }}"
                                    type="video/{{ $config->extension }}">
                                Seu navegador não suporta vídeos.
                            </video>
                        @else
                            <!-- Link para baixar Arquivo -->
                            <a href="{{ asset('storage/' . $config->path_archive) }}" download>Baixar Arquivo</a>
                        @endif
                    @endif
                </div>

                <a href="{{ route('config.index') }}" class="btn btn-primary">Voltar</a>
            </div>
        </div>
    </div>
@endsection
