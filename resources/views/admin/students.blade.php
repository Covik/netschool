<?php $title = 'Učenici'; ?>

@extends('master')

@section('head')
    <script src="/scripts/crud.js"></script>
@endsection

@section('content')
    <div id="students">
        <div>
            @if(count($students) > 0)
                <table class="table">
                    <thead>
                    <tr>
                        <th>Ime</th>
                        <th>Email</th>
                        <th>Razred</th>
                        <th>Akcije</th>
                    </tr>
                    </thead>
                    <tbody id="students-body">
                    @foreach($students as $student)
                        <tr data-crud-id="{{ $student->id }}">
                            <td data-crud-ref="name">{{ $student->name }}</td>
                            <td data-crud-ref="email">{{ $student->email }}</td>
                            <td data-crud-ref="class">{{ $student->theClass !== null ? $student->theClass->name : '' }}</td>
                            <td data-crud-actions>
                                <button class="crud-modify crud-button"><i class="glyphicon glyphicon-pencil"></i></button>
                                <button class="crud-delete crud-button"><i class="glyphicon glyphicon-remove"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">Nema učenika!</p>
            @endif
        </div>
    </div>

    <script>
        $('#students-body').crud({
            updateOptions: {
                url: '/students/{id}',
                type: 'PUT',
                element: '.crud-modify'
            },
            deleteOptions: {
                url: '/students/{id}',
                type: 'DELETE',
                element: '.crud-delete'
            },
            def: {
                'name': {
                    'type': 'string',
                    'length': 50
                },
                'email': {
                    'type': 'email',
                    'length': 255
                },
                'class': {
                    'type': 'integer',
                    'select':{!! $allClasses !!}
                }
            }
        });
    </script>
@endsection
