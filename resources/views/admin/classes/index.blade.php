<?php $title = 'Razredi'; ?>

<?php

$startyear = date("Y") - 4;
$endyear = date("Y");

if((int) date('n') >= 9) $endyear++;

$years = range($startyear, $endyear);

$crudYears = '{';

foreach ($years as $year) {
    $crudYears .= ($year-1).': \''.(($year - 1).'. / '.$year.'.').'\', ';
}

$crudYears .= '}';


$crudCourses = '{';

foreach($courses as $course) {
    $crudCourses .= $course->id.': \''.$course->name.'\',';
}

$crudCourses .= '}';

?>

@extends('master')

@section('head')
    <script src="/scripts/crud.js"></script>
@endsection

@section('content')
    <div id="classes">
        <div id="classes__append" class="default__append__component">
            <h2>Dodaj razred</h2>
            <form class="append-form" method="post" action="/classes" onsubmit="return false;">
                <div>
                    Abecedna oznaka:<br />
                    <input type="text" name="label" maxlength="3" autocomplete="off" />
                </div>

                <div>
                    Smjer:<br />
                    <select name="course">
                        <option value="-1">Odaberi smjer</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    Školska godina:<br />
                    <select name="year">
                        @foreach(array_reverse($years) as $year)
                            <option value="{{ $year-1 }}">{{ $year-1 }}. / {{ $year }}.</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit"><i class="glyphicon glyphicon-send"></i> Pošalji</button>
                </div>
            </form>
        </div>

        <div>
            @if(count($classes) > 0)
                <table class="table">
                    <thead>
                    <tr>
                        <th>Razred</th>
                        <th>Školska godina</th>
                        <th>Smjer</th>
                        <th>Predmeta</th>
                        <th>Učenika</th>
                        <th>Akcije</th>
                    </tr>
                    </thead>
                    <tbody id="classes-body">
                    @foreach($classes as $class)
                        <tr data-crud-id="{{ $class->id }}">
                            <td data-crud-ref="label" data-crud-value="{{ $class->label }}">{{ $class->name }}</td>
                            <td data-crud-ref="year" data-crud-value="{{ $class->year }}">{{ $class->year }}. / {{ $class->year + 1 }}.</td>
                            <td data-crud-ref="course" data-crud-value="{{ $class->course->id }}">{{ $class->course->name }}</td>
                            <td><a href="{{ route('single-class', ['id' => $class->id]) }}">{{ count($class->ps) }}</a></td>
                            <td>{{ count($class->students) }}</td>
                            <td data-crud-actions>
                                <button class="crud-modify crud-button"><i class="glyphicon glyphicon-pencil"></i></button>
                                <button class="crud-delete crud-button"><i class="glyphicon glyphicon-remove"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">Nema razreda!</p>
            @endif
        </div>
    </div>

    <script>
        $('#classes-body').crud({
            updateOptions: {
                url: '/classes/{id}',
                type: 'PUT',
                element: '.crud-modify'
            },
            deleteOptions: {
                url: '/classes/{id}',
                type: 'DELETE',
                element: '.crud-delete'
            },
            def: {
                'label': {
                    'type': 'string',
                    'length': 3
                },
                'year': {
                    'type': 'number',
                    'select':{!! $crudYears !!}
                },
                'course': {
                    'type': 'number',
                    'select':{!! $crudCourses !!}
                }
            }
        });
    </script>
@endsection
