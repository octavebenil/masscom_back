@extends('admin.layouts.app')
@section('title', 'Admin User Create')

@section('content')
    @include('admin.includes._breadcrumb', ['title' => 'Create Admin User', 'items' => ['Home' => route('admin.dashboard'), 'companies' => route('admin.admins.list'), 'Create Admin User' => null]])


    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="form" action="{{route("admin.admins.save")}}" method="POST"
                          enctype="multipart/form-data"
                          data-parsley-validate>
                        @csrf

                        @isset($admin)
                            <input type="hidden" name="id" value="{{ $admin->id }}">
                        @endisset
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name" class="required">Name:</label>
                                <input type="text" data-parsley-required="true" data-parsley-trigger="focusout"
                                       value="{{ old('name', $admin->name ?? null) }}"
                                       data-parsley-required-message="Please enter name" name="name" id="name"
                                       class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="required">Email:</label>
                                <input type="email" name="email" data-parsley-required="true"
                                       value="{{ old('email', $admin->email ?? null) }}" data-parsley-trigger="focusout"
                                       data-parsley-required-message="Please enter email" id="email"
                                       class="form-control">
                            </div>

                                <div class="col-md-6">
                                    <label for="password" class="required">Password:</label>
                                    <input type="password" name="password" id="password" value="{{ old('password_text', $admin->password_text ?? null) }}" data-parsley-required="true"
                                           data-parsley-trigger="focusout"
                                           data-parsley-required-message="Please enter email" class="form-control">
                                </div>

                        </div>


                        <button type="submit" class="btn btn-primary my-2">Submit</button>
                    </form>

                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->


        <!-- end col -->
    </div>
@endsection

