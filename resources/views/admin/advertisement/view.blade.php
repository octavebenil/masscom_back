@extends('admin.layouts.app')
@section('title', '')

@section('content')
    @include('admin.includes._breadcrumb', ['title' => 'Create new advertisement', 'items' => ['Home' => route('admin.dashboard'), 'Create new advertisement' => null]])

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="name" class="required">Name:</label>
                            <input type="text" disabled data-parsley-required="true" data-parsley-trigger="focusout"
                                   value="{{ $advertisement->name  }}"
                                   data-parsley-required-message="Please enter name" name="name" id="name"
                                   class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="link">The path for the cached data:</label>
                            <input type="text" disabled name="link" id="link"
                                   value="{{$advertisement->link }}"
                                   data-parsley-required="true"
                                   data-parsley-trigger="focusout"
                                   class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

