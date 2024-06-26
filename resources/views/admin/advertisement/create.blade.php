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
                                       name="name" id="name"
                                       class="form-control">
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="link" class="required">Vidéo:</label>
                                <input type="file" data-parsley-required="true" data-parsley-trigger="focusout" name="file" id="link"
                                       value="{{ old('link_text', '') }}"
                                       placeholder="path"
                                       class="form-control">
                                @error('link')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="max_views" class="required">Max views:</label>
                                <input type="number" data-parsley-trigger="focusout"
                                       value="{{ old('max_views','') }}"
                                       step="1"
                                       min="1"
                                       name="max_views"
                                       class="form-control">
                                @error('max_views')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary my-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection