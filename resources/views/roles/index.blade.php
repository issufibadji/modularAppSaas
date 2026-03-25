@extends('layouts.app') <!-- Você pode usar seu layout base aqui -->

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
        <h1>Roles</h1>

        <div class="mb-3">
            <a href="{{ route('roles.create') }}" class="btn btn-primary">Adicionar Nova Role</a>
        </div>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h5 class="panel-title">Roles</h5>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">Editar</a>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Deletar</button>
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
