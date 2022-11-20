@extends('layouts.login.master_login')

@section('content')
<form method="post" action="{{ route('login.perform') }}">
        
  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
  
  <div class="card card-primary">
    <div class="card-header"><h4>Login</h4></div>

        <div class="card-body">
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required="required" autofocus>
                @if ($errors->has('username'))
                    <span class="text-danger text-left">{{ $errors->first('username') }}</span>
                @endif
            </div>
        
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Password" required="required">
                
                @if ($errors->has('password'))
                    <span class="text-danger text-left">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
        </div>
    </div>
  
</form>
@endsection