<!DOCTYPE html>
<html lang="en">
@include('layouts.head')

<body class="az-body">
    <div class="az-signin-wrapper">
        <div class="az-card-signin h-100 rounded-10">
            <div class="az-signin-header mb-3">
                <h2 class="text-center text-black-50">Sign In</h2>

                <form action="index.html" class="py-1">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" placeholder="Enter your email"
                            value="demo@bootstrapdash.com">
                    </div><!-- form-group -->
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Enter your password"
                            value="thisisademo">
                    </div><!-- form-group -->
                    <button class="btn btn-primary btn-block fw-bold">Sign In</button>
                </form>
            </div><!-- az-signin-header -->
            <div class="az-signin-footer">
                <p class="text-right"><a href="">Forgot password?</a></p>
            </div><!-- az-signin-footer -->
        </div><!-- az-card-signin -->
    </div><!-- az-signin-wrapper -->

    @include('layouts.script')
</body>

</html>
