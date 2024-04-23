@extends('public.layouts.app')
@section('title', 'Gagnants')

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">

            <div class="avatar-md profile-user-wid mb-4 text-center">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{ Vite::asset('resources/images/logo.png') }}" alt=""
                                                     class="rounded-circle" width="45px" height="100%">
                                            </span>
            </div>
            <div class="card">
                <div class="card-body">

                    <br/>
                    <h1 class="text-center">Liste des gagnants</h1>
                    <div class="row">
                        @foreach($gagnants as $win)
                            <div class="col-md-3">
                                <div class="img-place">
                                    <img src="{{route("gagnants.photo",$win->photo)}}" alt="{{$win->nom}}" class="img-fluid"/>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
