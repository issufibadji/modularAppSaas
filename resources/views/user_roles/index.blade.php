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
        <h1>Atribuir/Remover Role de Usuário</h1>
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h5 class="panel-title">Role/Usuario</h5>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Roles</th>
                            <th>Atribuir Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>
                                    @foreach ($user->roles as $role)
                                        <div>
                                            <span>{{ $role->name }}</span>
                                            <form action="{{ route('user_roles.revoke', $user) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-times-circle"></i> <!-- Ícone "x" vermelho -->
                                                </button>
                                            </form>
                                            <br>
                                        </div>
                                    @endforeach

                                </td>
                                <td>
                                    <form action="{{ route('user_roles.assign', $user) }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-8">
                                                <select name="role_id" id="role_id" class="form-control" required>
                                                    <option value="" disabled selected>Selecione uma role</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4" style="align-content: end;">
                                                <button type="submit" class="btn btn-sm btn-primary">Atribuir</button>
                                            </div>
                                        </div>
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
