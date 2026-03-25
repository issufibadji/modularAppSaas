<!-- resources/views/notifications/send.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Enviar Notificação</h2>

    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h5 class="panel-title">Notificação</h5>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i
                        class="fa fa-expand"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i
                        class="fa fa-minus"></i></a>
            </div>
        </div>
        <div class="panel-body">
            <form action="{{ route('notifications.send') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="title">Título</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="form-group mb-3">
                    <label for="message">Mensagem</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="link">Link de Redirecionamento</label>
                    <input class="form-control" id="link" name="link" ></input>
                </div>

                <div class="form-group mb-3">
                    <label for="icon">Icone Notificação</label>
                    <input class="form-control" id="icon" name="icon" ></input>
                </div>

                <div class="form-group mb-3">
                    <label for="users">Enviar para</label>
                    <select class="form-control" id="users" name="users[]" multiple>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="notification_type">Tipo de Notificação</label>
                    <select class="form-control" id="notification_type" name="notification_type">
                        <option value="internal">Interna</option>
                        <option value="webpush">Web Push</option>
                        <option value="both">Ambas</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Enviar Notificação</button>
            </form>
        </div>
    </div>
</div>
@endsection
