@extends('admin.layout.auth')

@section('content')
<div class="auth-title-section mb-3 text-center"> 
    <h3 class="text-dark fs-20 fw-medium mb-2">Welcome back</h3>
    <p class="text-dark text-capitalize fs-14 mb-0">Sign in to continue to silve.</p>
</div>

<div class="pt-0">
    <form method="POST" role="form" action="{{ url('/admin/login') }}" class="my-4">
        @csrf
        <div class="form-group mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="emailaddress" class="form-label">Email address</label>
            <input class="form-control" type="email" name="email" id="emailaddress" required="" placeholder="Enter your email">
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group mb-3{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="form-label">Password</label>
            <input class="form-control" type="password" name="password" required="" id="password" placeholder="Enter your password">
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group d-flex mb-3">
            <div class="col-sm-6">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="checkbox-signin" checked>
                    <label class="form-check-label" for="checkbox-signin">Remember me</label>
                </div>
            </div>
            <div class="col-sm-6 text-end">
                <a class='text-muted fs-14' href='{{ url('/admin/password/reset') }}'>Forgot password?</a>                             
            </div>
        </div>
        
        <div class="form-group mb-0 row">
            <div class="col-12">
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit"> Log In </button>
                </div>
            </div>
        </div>
    </form>
    
</div>

@endsection
