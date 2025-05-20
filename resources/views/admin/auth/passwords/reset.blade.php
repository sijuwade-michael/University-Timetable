@extends('admin.layout.auth')

@section('content')

<div class="pt-0">
    <form role="form" method="POST" action="{{ url('/admin/password/reset') }}" class="my-4">
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

        <div class="form-group mb-3{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
            <label for="password_confirm" class="form-label">Confirm Password</label>
            <input class="form-control" type="password" name="password_confirmation" required="" id="password" placeholder="Enter your password">
            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
        </div>


        
        <div class="form-group mb-0 row">
            <div class="col-12">
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit"> Reset Password </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
