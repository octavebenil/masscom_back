@extends('admin.layouts.app')
@section('title', 'Question Create')

@section('content')
    @include('admin.includes._breadcrumb', ['title' => 'Create Question', 'items' => ['Home' => route('admin.dashboard'), 'surveys' => route('admin.surveys.list'), 'Create Question' => null]])

    <style>
        .question {
            margin-bottom: 20px;
        }

        .option {
            margin-bottom: 10px;
        }
    </style>
    @php
        $options = ["Option 1","Option 2", "Option 3","Option 4"];
    @endphp


    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="form" action="{{route("admin.surveys.save")}}" method="POST"
                          enctype="multipart/form-data"
                          data-parsley-validate>
                        @csrf

                        @isset($survey)
                            <input type="hidden" name="id" value="{{ $survey->id }}">
                        @endisset

                        <div class="row">
                            <div class="col-md-6">
                                <label for="company">Company Name:</label>
                                <select required
                                        data-parsley-trigger="focusout"
                                        data-parsley-required
                                        data-parsley-required-message="Please enter this value" name="company"
                                        id="company" class="form-control">

                                    @foreach($companies as $company)
                                        <option
                                            {{ $company === old('company', $survey->company ?? null) ? 'selected' :''}} value={{$company->id}}>
                                            <img src={{$company->logo}}  alt=""/> {{$company->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="survey_title">Survey Title:</label>
                                <input type="text" name="survey_title" id="survey_title" class="form-control" required
                                       data-parsley-trigger="focusout"
                                       data-parsley-required
                                       value="{{ old('survey_title', $survey->title ?? null) }}"
                                       data-parsley-required-message="Please enter title">
                            </div>


                        </div>

                        <div id="questions" class="my-4">
                            <div class="question row">
                                <div class="col-md-6">
                                    <label for="total_users">Maximum participants:</label>
                                    <input type="number" name="max_participants" id="total_users" class="form-control"
                                           required
                                           data-parsley-trigger="focusout"
                                           data-parsley-required
                                           value="{{ old('max_participants', $survey->max_participants ?? null) }}"
                                           data-parsley-required-message="Please enter max participants">
                                </div>


                                <div class="col-md-6 mb-4">
                                    <label>Question 1:</label>
                                    <input type="text" name="questions[1][text]" class="form-control" required value="{{ old('questions', $survey->questions[0]->question_text ?? null) }}">
                                </div>

                                <div class="col-md-12">
                                    <label>Question Type:</label>
                                    <select name="questions[1][type]" class="form-control question-type" >
                                        <option value="MCQ" {{ isset($survey) ?? old('max_participants', $survey->questions[0]->question_type == 'MCQ' ? 'selected' : '') }}>One Choice Answer</option>
                                        <option value="MAQ" {{ isset($survey) ?? old('max_participants', $survey->questions[0]->question_type == 'MAQ' ? 'selected' : '') }}>Multiple Answer</option>
                                        <option value="input" {{ isset($survey) ??  old('max_participants', $survey->questions[0]->question_type == 'input' ? 'selected' : '') }}>Input</option>
                                    </select>
                                </div>

                                <div class="col-md-6 question-options my-3">
                                    <label>Options:</label>
                                    <div class="options-container my-3">
                                        <input type="text" name="questions[1][options][]" class="form-control"
                                               placeholder="Option 1">
                                    </div>

                                    <button type="button" class="btn btn-primary add-option">Add Option</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="add-question" class="btn btn-success">Add Question</button>
                        <button type="submit" class="btn btn-primary">Submit Survey</button>
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


        $('#imageImg').change(function (event) {
            document.getElementById("image-img").src = URL.createObjectURL(event.target.files[0])
        })

        $(document).ready(function () {
            let questionIndex = 1;

            $('#add-question').click(function () {
                if ($('.question').length >= 5) {
                    return;
                }

                const questionIndex = $('.question').length + 1;

                const questionDiv = $('<div>').addClass('question row');
                questionDiv.html(`
                <div class="col-md-6">
                    <label>Question ${questionIndex}:</label>
                    <input type="text" name="questions[${questionIndex}][text]" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>Question Type:</label>
                    <select name="questions[${questionIndex}][type]" class="form-control question-type">
                        <option value="MCQ">One Choice Answer</option>
                        <option value="MAQ">Multiple Answer</option>
                        <option value="input">Input</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger delete-question" style="margin-top: 32px;">Delete</button>
                </div>
                <div class="col-md-12 question-options">
                    <label>Options:</label>
                    <div class="options-container">
                        <div class="option">
                            <input type="text" name="questions[${questionIndex}][options][]" class="form-control" placeholder="Option 1">
                            <button type="button" class="btn btn-danger delete-option">Delete</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary add-option">Add Option</button>
                </div>
            `);

                $('#questions').append(questionDiv);
            });

            $(document).on('change', '.question-type', function () {
                const optionsDiv = $(this).closest('.question').find('.question-options');

                if ($(this).val() === 'MCQ' || $(this).val() === 'MAQ') {
                    optionsDiv.show();
                } else {
                    optionsDiv.hide();
                }
            });

            $(document).on('click', '.add-option', function () {
                const optionsContainer = $(this).siblings('.options-container');
                const optionIndex = optionsContainer.children('.option').length + 1;

                if (optionIndex > 4) {
                    return;
                }

                const optionInput = $('<input>').attr({
                    type: 'text',
                    name: `questions[${$(this).closest('.question').index() + 1}][options][]`,
                    class: 'form-control my-3',
                    placeholder: `Option ${optionIndex + 1}`
                });

                // const conclusionInput = $('<input>').attr({
                //     type: 'text',
                //     name: `questions[${$(this).closest('.question').index() + 1}][conclusion][]`,
                //     class: 'form-control my-3',
                //     placeholder: `Conclusion ${optionIndex + 1}`
                // });

                const deleteButton = $('<button>').attr({
                    type: 'button',
                    class: 'btn btn-danger delete-option'
                }).text('Delete').click(function () {
                    $(this).parent().remove();
                });

                optionsContainer.append(
                    $('<div>').addClass('option').append(optionInput).append(deleteButton)
                );
            });

            $(document).on('click', '.delete-option', function () {
                $(this).closest('.option').remove();
            });

            $(document).on('click', '.delete-question', function () {
                $(this).closest('.question').remove();
            });
        });
    </script>
@endpush
