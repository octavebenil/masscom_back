@extends('admin.layouts.app')
@section('title', 'Users')

@section('content')
    @include('admin.includes._breadcrumb', ['title' => 'Users', 'items' => ['Home' => route('admin.dashboard'), 'Users' => null]])


    <div class="row mb-3 justify-content-end">
        <div class="col-6 text-end flex align-items-center ">
            <a href="{{route('export-users')}}">
                <button class="btn btn-primary">
                    Export To Csv
                </button>
            </a>
            <a href="{{route('export-users-pdf')}}">
                <button class="btn btn-primary">
                    Export To Pdf
                </button>
            </a>
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


