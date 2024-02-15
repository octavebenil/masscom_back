@php use App\Models\Answer;use App\Models\QuestionOption; @endphp
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>

    <style>
        table {
            width: 95%;
            border-collapse: collapse;
            margin: 50px auto;
        }

        /* Zebra striping */
        tr:nth-of-type(odd) {
            background: #eee;
        }

        th {
            background: #3498db;
            color: white;
            font-weight: bold;
        }

        td,
        th {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 18px;
        }


    </style>

</head>

<body>

<div style="width: 95%; margin: 0 auto;">
    <div style="width: 10%; float:left; margin-right: 20px;">
        <img src="{{ public_path('logo.png') }}" width="100%" alt="">
    </div>
    <div style="width: 50%; float: left;">
        <h1>{{$survey->title}}</h1>
    </div>
</div>

<table style="position: relative; top: 50px;">
    <thead>
    <tr>
        <th>User Email/Phone</th>
        <th>Question Title</th>
        <th>Selected Option</th>
    </tr>
    </thead>
    <tbody>


    @foreach($survey->answers()->get() as $q)

        <tr>
            <td data-column="Email" style="color: dodgerblue;">
                {{ $q->user->email }}
            </td>
            <td data-column="Email" style="color: dodgerblue;">
                {{ $q->question->question_text }}
            </td>
            <td data-column="Email" style="color: dodgerblue;">
                @if($q->question->question_type === 'MCQ')
                {{strtok(QuestionOption::query()->find(json_decode($q->selected_options, true))->option_text, '_')}}
                @elseif($q->question->question_type === 'input')
                    {{json_decode($q->selected_options, true)[array_key_last(json_decode($q->selected_options, true))]}}
                @elseif($q->question->question_type === 'MAQ')
                   @foreach(json_decode($q->selected_options, true) as $a)
                        {{strtok(QuestionOption::query()->find(json_decode($a, true))->option_text, '_')}},
                   @endforeach
                @endif

            </td>
        </tr>

    @endforeach

    </tbody>
</table>

</body>

</html>
