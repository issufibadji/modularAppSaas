@extends('auth.layout')

@section('content')
    <div class="container">
        <h1>Dois Fatores (2FA)</h1>
        <form action="{{ route('2fa.verify') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="2fa_code">Código de Autenticação</label>
                <input type="text" name="2fa_code" class="form-control" required>
                @if ($errors->has('2fa_code'))
                    <span class="text-danger">{{ $errors->first('2fa_code') }}</span>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Verificar</button>
            <a class="btn btn-warning" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                {{ __('Logout') }}

            </a>
        </form>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
@endsection
