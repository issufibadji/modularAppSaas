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
        <h1>Audit Logs</h1>

        <div class="panel panel-inverse">
            <div class="panel-heading">
                <h5 class="panel-title">Logs</h5>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                            class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                            class="fa fa-minus"></i></a>
                </div>
            </div>
            <div class="panel-body">

                <!-- Formulário de filtro -->
                <form action="{{ route('audit.logs') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="event">Event Type:</label>
                            <select name="event" id="event" class="form-control">
                                <option value="">All Events</option>
                                <option value="created" {{ request('event') == 'created' ? 'selected' : '' }}>Created
                                </option>
                                <option value="updated" {{ request('event') == 'updated' ? 'selected' : '' }}>Updated
                                </option>
                                <option value="deleted" {{ request('event') == 'deleted' ? 'selected' : '' }}>Deleted
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="user_id">User ID:</label>
                            <input type="text" name="user_id" id="user_id" class="form-control"
                                value="{{ request('user_id') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="start_date">Start Date:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date">End Date:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-4">Filter</button>
                            <a href="{{ route('audit.logs') }}" class="btn btn-secondary mt-4">Reset</a>
                        </div>
                    </div>
                </form>

                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Model</th>
                            <th>Old Values</th>
                            <th>New Values</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($audits as $audit)
                            <tr>
                                <td>{{ $audit->id }}</td>
                                <td>{{ optional($audit->user)->name ?? 'N/A' }}</td>
                                <td>{{ $audit->event }}</td>
                                <td>{{ $audit->auditable_type }} (ID: {{ $audit->auditable_id }})</td>
                                <td>{{ json_encode($audit->old_values) }}</td>
                                <td>{{ json_encode($audit->new_values) }}</td>
                                <td>{{ $audit->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Paginação -->
                {{ $audits->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function() {

            $('.table').DataTable({
                responsive: true,
                paging: false,
                info: false,
                language: {
                    "url": "{{ asset('lang/pt-BR.json') }}"
                },
            });
        });
    </script>
@endsection
