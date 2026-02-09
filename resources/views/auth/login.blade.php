@extends('layouts.app')

@section('content')
    <div class="container-fluid  main-page">
        <div class="row justify-content-center  ">
            {{--        <div class="col-md-5"></div>--}}
            <div class="col-md-6 mt-5">
                <div class="card mt-5">
                    <div class="card-header"><h3 class="h3">{{ __('Login') }}</h3></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required autocomplete="email"
                                    autofocus>
                                {{--                                    aria-describedby="emailHelp">--}}
                                {{--                                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>--}}
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password"
                                       class="form-label"
                                >Password</label>

                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required autocomplete="current-password"
                                >
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       name="remember"
                                       id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Login</button>

{{--                            <div class="form-group row">--}}
{{--                                <label for="email"--}}
{{--                                       class="col-md-4 col-form-label text-md-right"--}}
{{--                                >{{ __('E-Mail Address') }}</label>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <input id="email"--}}
{{--                                           type="email"--}}
{{--                                           class="form-control @error('email') is-invalid @enderror"--}}
{{--                                           name="email"--}}
{{--                                           value="{{ old('email') }}"--}}
{{--                                           required autocomplete="email"--}}
{{--                                           autofocus>--}}

{{--                                    @error('email')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="form-group row">--}}
{{--                                <label for="password"--}}
{{--                                       class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

{{--                                <div class="col-md-6">--}}
{{--                                    <input id="password" type="password"--}}
{{--                                           class="form-control @error('password') is-invalid @enderror" name="password"--}}
{{--                                           required autocomplete="current-password">--}}

{{--                                    @error('password')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="form-group row">--}}
{{--                                <div class="col-md-6 offset-md-4">--}}
{{--                                    <div class="form-check">--}}
{{--                                        <input class="form-check-input" type="checkbox" name="remember"--}}
{{--                                               id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

{{--                                        <label class="form-check-label" for="remember">--}}
{{--                                            {{ __('Remember Me') }}--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="form-group row mb-0 mt-3">--}}
{{--                                <div class="col-md-8 offset-md-4 ">--}}
{{--                                    <button type="submit" class="btn btn-primary">--}}
{{--                                        {{ __('Login') }}--}}
{{--                                    </button>--}}

                                    {{--                                @if (Route::has('password.request'))--}}
                                    {{--                                    <a class="btn btn-link" href="{{ route('password.request') }}">--}}
                                    {{--                                        {{ __('Forgot Your Password?') }}--}}
                                    {{--                                    </a>--}}
                                    {{--                                @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </form>
                    </div>
                </div>
            </div>
            {{--        <div class="col"></div>--}}
        </div>
        <div class="spacer"></div>

    </div>
@endsection
