@extends('auth.layout')

@section('content')
    <form action="{{ route('login') }}" method="POST" class="fs-13px">

        @csrf

        <div class="form-floating mb-15px">
            <input type="text" class="form-control h-45px fs-13px @error('email') is-invalid @enderror"
                placeholder="Email Address" id="email" name="email" value="{{ old('email') }}" required
                autocomplete="email" autofocus />

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <label for="email" class="d-flex align-items-center fs-13px text-gray-600">Email
                Address</label>
        </div>
        <div class="form-floating mb-15px">
            <input type="password" class="form-control h-45px fs-13px @error('password') is-invalid @enderror"
                placeholder="Password" id="password" name="password" required autocomplete="current-password" />

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <label for="password" class="d-flex align-items-center fs-13px text-gray-600">Password</label>
        </div>
        <div class="form-check mb-30px">
            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                {{ old('remember') ? 'checked' : '' }} />
            <label class="form-check-label" for="remember">
                Remember Me
            </label>
        </div>

        <div class="mb-15px">
            <button type="submit" class="btn btn-success d-block h-45px w-100 btn-lg fs-14px">Login</button>
        </div>
        <p>
            Esqueceu a senha? Click <a href="{{ route('password.request') }}" class="text-primary">aqui</a>
            para
            recuperar.
        </p>
        <div class="mb-40px pb-40px text-dark">
            Ainda não é membro? Click <a href="{{ route('register') }}" class="text-primary">aqui</a>
            para
            se registrar.
        </div>


    </form>
@endsection
