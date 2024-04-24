@extends('admin.layouts.app')
@section('title', 'Parrainages')

@section('content')
    @include('admin.includes._breadcrumb', ['title' => 'Parrainages', 'items' => ['Home' => route('admin.dashboard'), 'parrainages' => route('admin.parrainages.list'), 'Parrainage' => null]])

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="form" action="{{route("admin.parrainages.save")}}" method="POST"
                          enctype="multipart/form-data"
                          data-parsley-validate>
                        @csrf

                        @isset($user)
                            <input type="hidden" name="id" value="{{ $user->id }}">
                        @endisset


                        <div class="row">
                            <div class="col-md-6">
                                <label for="email">Téléphone <span style="color: red;">*</span></label>
                                <input type="text"
                                       name="email"
                                       required="required"
                                       value="{{ old('email', $user->email ?? null) }}" id="email" class="form-control" data-parsley-trigger="focusout"
                                       data-parsley-required
                                       data-parsley-required-message="Veuillez entrer votre numéro de téléphone">
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <label for="commune">Commune <span style="color: red;">*</span></label>
                                <input type="text"
                                       name="commune"
                                       required="required"
                                       value="{{ old('commune', $user->commune ?? null) }}"
                                       id="commune" class="form-control" data-parsley-trigger="focusout"
                                       data-parsley-required
                                       data-parsley-required-message="Veuillez entrer votre commune">
                            </div>

                        </div>


{{--                        <div class="row">--}}
{{--                            <div class="col-md-6">--}}
{{--                                <label for="code_affiliation">Code de parrainage <span style="color: red;">*</span></label>--}}
{{--                                <input type="text"--}}
{{--                                       name="code_affiliation"--}}
{{--                                       required="required"--}}
{{--                                       value="{{ old('code_affiliation', $user->code_affiliation ?? null) }}"--}}
{{--                                       id="code_affiliation" class="form-control" data-parsley-trigger="focusout"--}}
{{--                                       data-parsley-required--}}
{{--                                       data-parsley-required-message="Veuillez entrer votre code de parrainage">--}}
{{--                            </div>--}}

{{--                        </div>--}}

                        <button type="submit" class="btn btn-primary my-2">Enregistrer</button>
                    </form>

                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->


        <!-- end col -->
    </div>
@endsection

@push('scripts')
    <script type="module">

        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });

    </script>
@endpush
