@extends('admin.layout.auth')

<!-- Main Content -->
@section('content')
    <div class="auth-title-section mb-3 text-center"> 
        <h3 class="text-dark fs-20 fw-medium mb-2">Reset Password</h3>
        <p class="text-dark text-capitalize fs-14 mb-0">Enter your email address and we'll send you an email with instructions to reset your password.</p>
    </div>

    <div class="pt-0">
        <form role="form" method="POST" action="{{ url('/admin/password/email') }}" class="my-4">
            <div class="form-group mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="emailaddress" class="form-label">Email address</label>
                <input class="form-control" type="email" name="email" id="emailaddress" required="" placeholder="Enter your email">
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            
            <div class="form-group mb-0 row">
                <div class="col-12">
                    <div class="d-grid">
                        <button class="btn btn-primary" type="submit"> Recover Password </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="text-center text-muted">
            <p class="mb-0">Changed your mind  ?<a class='text-primary ms-2 fw-medium' href='{{ ('/admin/login') }}'>Back to Login</a></p>
        </div>
    </div>
    
@endsection
