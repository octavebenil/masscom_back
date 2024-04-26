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
                    <p>L'objectif maximal pour chaque parrain est de :</p>
                    <ul class="list">
                        <li class="list-item"><strong>{{$objectif_1}}</strong> parrainages = <strong>{{$lot_1}}</strong> lots</li>
                        <li class="list-item"><strong>{{$objectif_2}}</strong> parrainages = <strong>{{$lot_2}}</strong> lots</li>
                        <li class="list-item"><strong>{{$objectif_3}}</strong> parrainages = <strong>{{$lot_3}}</strong> lots</li>
                    </ul>

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
                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label for="objectif_1">Objectif 1 (Nombre maximal des personnes parrainnées)<span style="color: red;">*</span> </label>
                                    <input type="number" min="1" step="1" name="objectif_1" id="objectif_1" class="form-control" value="{{$objectif_1}}"
                                           required="required"/>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label for="lot_1">Nombre des lots<span style="color: red;">*</span> </label>
                                    <input type="number" min="1" step="1" name="lot_1" id="lot_1" class="form-control" value="{{$lot_1}}"
                                           required="required"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label for="objectif_2">Objectif 2 </label>
                                    <input type="number" min="1" step="1" name="objectif_2" id="objectif_2" class="form-control" value="{{$objectif_2}}"/>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label for="lot_2">Nombre des lots<span style="color: red;">*</span> </label>
                                    <input type="number" min="1" step="1" name="lot_2" id="lot_2" class="form-control" value="{{$lot_2}}"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label for="objectif_3">Objectif 3 </label>
                                    <input type="number" min="1" step="1" name="objectif_3" id="objectif_3" class="form-control" value="{{$objectif_3}}"/>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label for="lot_3">Nombre des lots<span style="color: red;">*</span> </label>
                                    <input type="number" min="1" step="1" name="lot_3" id="lot_3" class="form-control" value="{{$lot_3}}"/>
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


