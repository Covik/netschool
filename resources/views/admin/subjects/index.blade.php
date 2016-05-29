<?php $title = 'Predmeti'; ?>

@extends('master')

@section('head')
    <script src="/scripts/crud.js"></script>
@endsection

@section('content')
    <div id="subjects">
        <div id="subjects__append" class="default__append__component">
            <h2>Dodaj predmet</h2>
            <form class="append-form" method="post" action="/subjects" onsubmit="return false;">
                <div>
                    Ime:<br />
                    <input type="text" name="name" maxlength="60" autocomplete="off" />
                </div>

                <div>
                    <button type="submit"><i class="glyphicon glyphicon-send"></i> Pošalji</button>
                </div>
            </form>
        </div>

        <div>
            @if(count($subjects) > 0)
                <table class="table">
                    <thead>
                    <tr>
                        <th>Ime</th>
                        <th>Razredi</th>
                        <th>Akcije</th>
                    </tr>
                    </thead>
                    <tbody id="subjects-body">
                    @foreach($subjects as $subject)
                        <tr data-crud-id="{{ $subject->id }}">
                            <td data-crud-ref="name">{{ $subject->name }}</td>
                            <td><a href="/subjects/{{ str_slug($subject->name, '-') }}">{{ count($subject->courses) }}</a></td>
                            <td data-crud-actions>
                                <button class="crud-modify crud-button"><i class="glyphicon glyphicon-pencil"></i></button>
                                <button class="crud-delete crud-button"><i class="glyphicon glyphicon-remove"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">Nema predmeta!</p>
            @endif
        </div>
    </div>

    <script>
        $('#subjects-body').crud({
            updateOptions: {
                url: '/subjects/{id}',
                type: 'PUT',
                element: '.crud-modify'
            },
            deleteOptions: {
                url: '/subjects/{id}',
                type: 'DELETE',
                element: '.crud-delete'
            },
            def: {
                'name': {
                    'type': 'string',
                    'length': 60
                }
            }
        });
    </script>
@endsection
