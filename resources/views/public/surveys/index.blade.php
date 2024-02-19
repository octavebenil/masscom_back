@extends('public.layouts.app')
@section('title', 'Public')

@section('content')
    @include('admin.includes._breadcrumb', ['title' => 'Statistics', 'items' => ['Statistics' => null]])
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
