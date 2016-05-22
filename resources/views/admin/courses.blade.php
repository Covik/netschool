<?php $title = 'Smjerovi'; ?>

@extends('master')

@section('head')
    <script src="/scripts/crud.js"></script>
    <script>
        $('#courses-body').crud({
            updateOptions: {
                url: '/courses/{id}',
                type: 'PUT',
                element: '.crud-modify'
            },
            deleteOptions: {
                url: '/courses/{id}',
                type: 'DELETE',
                element: '.crud-delete'
            },
            def: {
                'name': {
                    'type': 'string',
                    'length': 30
                },
                'duration': {
                    'type': 'number',
                    'select': [3,4,5]
                }
            }
        });
    </script>
@endsection

@section('content')
    <div id="courses">
        <div id="courses__append" class="default__append__component">
            <h2>Dodaj smjer</h2>
            <form class="append-form" method="post" action="/courses" onsubmit="return false;">
                <div>
                    Ime:<br />
                    <input type="text" name="name" autocomplete="off" />
                </div>

                <div>
                    Trajanje:<br />
                    <select name="duration">
                        <option value="3">3 godine</option>
                        <option value="4" selected>4 godine</option>
                        <option value="5">5 godina</option>
                    </select>
                </div>
                <div>
                    <button type="submit"><i class="glyphicon glyphicon-send"></i> Pošalji</button>
                </div>
            </form>
        </div>

        <div class="data-display1">
            @if(count($courses) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ime</th>
                            <th>Trajanje (godina)</th>
                            <th>Akcije</th>
                        </tr>
                    </thead>
                    <tbody id="courses-body">
                @foreach($courses as $course)
                    <tr data-crud-id="{{ $course->id }}">
                        <td data-crud-ref="name">{{ $course->name }}</td>
                        <td data-crud-ref="duration">{{ $course->duration }}</td>
                        <td data-crud-actions>
                            <button class="crud-modify crud-button"><i class="glyphicon glyphicon-pencil"></i></button>
                            <button class="crud-delete crud-button"><i class="glyphicon glyphicon-remove"></i></button>
                        </td>
                    </tr>
                @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">Nema smjerova!</p>
            @endif
        </div>
    </div>
@endsection