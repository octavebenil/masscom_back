@extends('admin.layouts.app')
@section('title', 'Questions')

@section('content')
    @include('admin.includes._breadcrumb', ['title' => 'Questions', 'items' => ['Home' => route('admin.dashboard'), 'Questions' => null]])

    <div class="row mb-3">
        <div class="col-12 text-end">
            <a href="{{ route('admin.companies.create') }}" class="btn btn-success btn-rounded btn-min-width"><i
                    class="fa-solid fa-plus pe-2"></i>
                Create Company</a>
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


