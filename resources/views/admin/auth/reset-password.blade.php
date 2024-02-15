@extends('admin.auth.app')

@section('title', 'Login')

@section('content')
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-primary bg-soft">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-4">
                                        <h5 class="text-primary">Welcome Back !</h5>
                                        <p>Sign in to continue to.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="auth-logo">
                                <a href="#">
                                    <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{ Vite::asset('resources/images/logo.png') }}" alt=""
                                                     class="rounded-circle" width="45px" height="100%">
                                            </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                <form class="form-horizontal" id="loginForm" action="{{ route('admin.login.post') }}"
                                      method="post"
                                      data-parsley-validate>
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="email"
                                               placeholder="Enter email" required
                                               value="{{ old('email') }}"
                                               data-parsley-trigger="focusout"
                                               data-parsley-required
                                               data-parsley-type-message="Please enter valid email"
                                               data-parsley-required-message="Please enter email">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password"
                                               placeholder="Enter password"
                                               aria-label="Password" aria-describedby="password-addon" required
                                               data-parsley-trigger="focusout"
                                               data-parsley-required
                                               data-parsley-required-message="Please enter password">
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <button id="submitBtn" class="btn btn-primary waves-effect waves-light"
                                                type="submit">Log In
                                        </button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <a href="#" class="text-muted"><i class="fa-solid fa-lock me-1"></i> Forgot your password?</a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <div>
                            <p>{!! config('constant.copyright') !!}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- end account-pages -->

@endsection

@push('script')
    <script type="module">
        $('#loginForm').on('submit', function () {
            if ($(this).parsley().isValid()) {
                $('#submitBtn').html('<i class="fa-solid fa-circle-notch fa-spin"></i>').attr('disabled', 'disabled')
            }
        });
    </script>
@endpush
