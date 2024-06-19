<!DOCTYPE html>
<html lang="en">
@include('layouts.head')

<body class="az-body">
    <div class="az-signin-wrapper">
        <div class="az-card-signin h-100 rounded-10">
            <div class="az-signin-header mb-3">
                <div class="text-center">
                    <img src="{{ asset('img/yasaka-icon-horizontal.png') }}" width="60%" alt="">
                </div>

                <form action="{{ route('authentication') }}" method="post" class="py-1">
                    @csrf
                    <div class="form-group">
                        <label for="email_or_username">Email or Username</label>
                        <input type="text" class="form-control @error('email_or_username') is-invalid @enderror" name="email_or_username"
                            placeholder="Enter your email or username" value="{{ old('username') }}" required>
                        @error('email_or_username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div><!-- form-group -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter your password">
                            <span class="input-group-text" id="togglePassword" onclick="togglePasswordVisibility()" value="{{ old('password') }}" required>
                                <i class="fas fa-eye" id="eye-icon"></i>
                            </span>
                        </div>
                    </div><!-- form-group -->
                    <button type="submit" class="btn btn-primary btn-block fw-bold">Sign In</button>
                </form>
            </div><!-- az-signin-header -->
            <div class="az-signin-footer">
                {{-- <p class="text-right"><a href="">Forgot password?</a></p> --}}
            </div><!-- az-signin-footer -->
        </div><!-- az-card-signin -->
    </div><!-- az-signin-wrapper -->

    @include('layouts.script')
    @include('js.auth')
</body>

</html>
