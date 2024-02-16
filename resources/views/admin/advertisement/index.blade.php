@extends('admin.layouts.app')
@section('title', 'Admins')

@section('content')
    @include('admin.includes._breadcrumb', ['title' => 'Advertisement', 'items' => ['Home' => route('admin.dashboard'), 'Advertisement' => null]])

    <div class="row mb-3">
        <div class="col-12 text-end">
            <a href="{{ route('admin.advertisement.create') }}" class="btn btn-success btn-rounded btn-min-width"><i
                        class="fa-solid fa-plus pe-2"></i>
                Create New Advertisement</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush


