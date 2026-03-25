@extends('auth.layout')

@section('content')
    <form action="{{ route('register') }}" method="POST" class="fs-13px">
        @csrf

        <div class="mb-3">
            <label for="name" class="mb-2">Nome <span class="text-danger">*</span></label>
            <div class="row gx-3">
                <input type="text" class="form-control fs-13px @error('name') is-invalid @enderror" placeholder="Name"
                    name="name" id="name" value="{{ old('name') }}" required autocomplete="name" autofocus />
            </div>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>
        <div class="mb-3">
            <label class="mb-2" for="email">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control fs-13px @error('email') is-invalid @enderror"
                placeholder="Email address" id="email" name="email" value="{{ old('email') }}" required
                autocomplete="email" />

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>
        <div class="mb-4">
            <label class="mb-2" for="password">Senha <span class="text-danger">*</span></label>
            <input id="password" type="password" class="form-control fs-13px @error('password') is-invalid @enderror"
                name="password" required autocomplete="new-password" placeholder="Password" />

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>

        <div class="mb-4">
            <label class="mb-2" for="password-confirm">Confirme a Senha <span class="text-danger">*</span></label>
            <input id="password-confirm" type="password" class="form-control fs-13px " name="password_confirmation" required
                autocomplete="new-password" placeholder="Password" />

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>
        {{-- <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value id="agreementCheckbox" />
                            <label class="form-check-label" for="agreementCheckbox">
                                By clicking Sign Up, you agree to our <a href="javascript:;">Terms</a> and that you have
                                read our <a href="javascript:;">Data Policy</a>, including our <a
                                    href="javascript:;">Cookie Use</a>.
                            </label>
                        </div> --}}
        <div class="mb-4">
            <button type="submit" class="btn btn-primary d-block w-100 btn-lg h-45px fs-13px">Sign
                Up</button>
        </div>
        <div class="mb-4 pb-5">
            Já é Membro? Click <a href="{{ route('login') }}">aqui</a> para login.
        </div>
    </form>
@endsection
