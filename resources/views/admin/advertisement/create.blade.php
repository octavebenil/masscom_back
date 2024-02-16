@extends('admin.layouts.app')
@section('title', '')

@section('content')
    @include('admin.includes._breadcrumb', ['title' => 'Create new advertisement', 'items' => ['Home' => route('admin.dashboard'), 'Create new advertisement' => null]])

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="form" action="{{route("admin.advertisement.store")}}" method="POST"
                          enctype="multipart/form-data"
                          data-parsley-validate>
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <label for="name" class="required">Name:</label>
                                <input type="text" data-parsley-required="true" data-parsley-trigger="focusout"
                                       value="{{ old('name','') }}"
                                       data-parsley-required-message="Please enter name" name="name" id="name"
                                       class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label for="link">The path for the cached data:</label>
                                <input type="text" name="link" id="link"
                                       value="{{ old('link_text', '') }}"
                                       data-parsley-required="true"
                                       data-parsley-trigger="focusout"
                                       class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary my-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

