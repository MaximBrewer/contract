@extends('layouts.app')

@section('content')
<all-auctions></all-auctions>
@endsection

@section('loginform')
        <form method="POST" action="{{ route('login') }}"  class="px-4 py-3">
            @csrf
            <div class="form-group">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('E-Mail Address') }}">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
            </div>
            <div class="form-group">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
            </div>
                    <div class="form-check form-group">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

            <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Login') }}
                    </button>
                </div>
  <div class="dropdown-divider"></div>

                    @if (Route::has('password.request'))
                    <a class="dropdown-item" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                    @endif


                {{-- <div class="col-md-4">
                    <p>{{ __('if this is your first time in the project, click') }} <a class="btn btn-primary" href="{{ route('password.request') }}">
                            {{ __('Register') }}
                        </a></p>
                </div> --}}
        </form>
        
@endsection