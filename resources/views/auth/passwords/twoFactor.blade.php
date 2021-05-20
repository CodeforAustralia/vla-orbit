@extends('layout.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-group">
            <div class="card p-4">
                <div class="card-body">
                    @if(session()->has('message'))
                    <p class="alert alert-info">
                        {{ session()->get('message') }}
                    </p>
                    @endif
                    <form method="POST" action="{{ route('verify.store') }}">
                        {{ csrf_field() }}
                        <h1>Two Factor Verification</h1>
                        <p class="text-muted">
                            You have received an email which contains two factor login code.
                            If you haven't received it, press <a href="{{ route('verify.resend') }}">here</a>.
                        </p>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </div>
                            <input name="two_factor_code" type="text"
                                class="form-control{{ $errors->has('two_factor_code') ? ' is-invalid' : '' }}" required
                                autofocus placeholder="Two Factor Code">
                            @if($errors->has('two_factor_code'))
                            <div class="invalid-feedback">
                                {{ $errors->first('two_factor_code') }}
                            </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-primary px-4">
                                    Verify
                                </button>
                            </div>
                    </form>
                    <div class="col-6 text-right">
                        <form action="{{ config('app.url') }}/logout">
                            <input class="btn btn-danger px-4" type="submit" value="Logout" />
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection