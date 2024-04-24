@extends('admin.layouts.app')
@section('title', 'Parrainages')

@section('content')
    @include('admin.includes._breadcrumb', ['title' => 'Parrainages', 'items' => ['Home' => route('admin.dashboard'), 'Parrainages' => null]])

    <div class="row mb-3">
        <div class="col-12 text-end">
            <button data-bs-toggle="modal" data-bs-target="#objectifModal" class="btn btn-success btn-rounded btn-min-width"><i
                    class="fa-solid fa-plus pe-2"></i>
                Paramètres des objectifs
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <p>L'objectif maximal pour chaque parrain est de <strong>{{$objectif}}</strong></p>

                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="objectifModal" tabindex="-1" aria-labelledby="objectifModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{route("admin.parrainages.objectif")}}" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="objectifModalLabel">Paramètre des objectifs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mt-3">
                                    <label for="objectif">Objectif (Nombre maximal des personnes parrainnées)<span style="color: red;">*</span> </label>
                                    <input type="number" min="1" step="1" name="objectif" id="objectif" class="form-control" value="{{$objectif}}"
                                           required="required"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush


