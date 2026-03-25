@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Menu</h1>
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h5 class="panel-title">Menu</h5>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <form action="{{ route('menu.update', $menu->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="description">Descrição:</label>
                        <input type="text" class="form-control" id="description" name="description"
                            value="{{ $menu->description }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="icon">Icone:</label>
                        <input type="text" class="form-control" id="icon" name="icon" value="{{ $menu->icon }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="style">Estilo CSS:</label>
                        <input type="text" class="form-control" id="style" name="style" value="{{ $menu->style }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="module">Modulo:</label>
                        <input type="text" class="form-control" id="module" name="module" value="{{ $menu->module }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="menu_above">Menu Pai:</label>
                        <input type="text" class="form-control" id="menu_above" name="menu_above" value="{{ $menu->menu_above }}" >
                    </div>
                    <div class="form-group mb-3">
                        <label for="level">Nível:</label>
                        <input type="number" class="form-control" id="level" name="level" value="{{ $menu->level }}"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="route">Rota:</label>
                        <input type="text" class="form-control" id="route" name="route" value="{{ $menu->route }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="acl">Permissões de Acesso:</label>
                        <input type="text" class="form-control" id="acl" name="acl" value="{{ $menu->acl }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="order">Ordem:</label>
                        <input type="number" class="form-control" id="order" name="order" value="{{ $menu->order }}"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="active">Ativo:</label>
                        <select class="form-control" id="active" name="active" required>
                            <option value="1" {{ $menu->active ? 'selected' : '' }}>Sim</option>
                            <option value="0" {{ !$menu->active ? 'selected' : '' }}>Não</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Atualizar</button>
                    <a href="{{ route('menu.index') }}" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
@endsection
