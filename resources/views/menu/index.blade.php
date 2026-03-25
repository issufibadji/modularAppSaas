@extends('layouts.app')

@section('content')
    <link href="{{ asset('/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('plugins/datatables.net/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}" type="text/javascript">
    </script>

    <link href="{{ asset('/plugins/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css') }}"
        rel="stylesheet" />
    <script src="{{ asset('plugins/datatables.net-fixedcolumns-bs5/js/fixedColumns.bootstrap5.min.js') }}"
        type="text/javascript"></script>

    <link href="{{ asset('/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"
        type="text/javascript"></script>

    <div class="container">
        <h1>Menus</h1>
        <a href="{{ route('menu.create') }}" class="btn btn-primary mb-3">Novo Menu</a>

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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Nível</th>
                            <th>Modulo</th>
                            <th>Rota</th>
                            <th>Ordem</th>
                            <th>Ativo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $menu)
                            <tr>
                                <td>{{ $menu->description }}</td>
                                <td>{{ $menu->level }}</td>
                                <td>{{ $menu->module }}</td>
                                <td>{{ $menu->route }}</td>
                                <td>{{ $menu->order }}</td>
                                <td>{{ $menu->active ? 'Sim' : 'Não' }}</td>
                                <td>
                                    <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('menu.destroy', $menu->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $('.table').DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    "url": "{{ asset('lang/pt-BR.json') }}"
                },
            });

        });
    </script>
@endsection
