<?php $title = 'Datoteke'; ?>

@extends('master')

@section('head')
    <script src="/scripts/crud.js"></script>
@endsection

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>Naziv</th>
            <th>Opis</th>
            @if($au->isAdmin() || $au->isProfessor())<th>Razred</th>@endif
            <th>Predmet</th>
            @if($au->isAdmin())<th>Autor</th>@endif
            <th>Preneseno</th>
            <th>Akcije</th>
        </tr>
        </thead>
        <tbody id="files__index">
            @forelse($files as $file)
                @include('home.files.single', ['file' => $file, 'myfiles' => true])
            @empty
                <tr class="table__empty">
                    <td colspan="7">Nema datoteka!</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        $('#files__index').crud({
            updateOptions: {
                url: '/files/{id}',
                type: 'PUT',
                element: '.crud-modify'
            },
            deleteOptions: {
                url: '/files/{id}',
                type: 'DELETE',
                element: '.crud-delete'
            },
            def: {
                'description': {
                    'type': 'string'
                }
            }
        });
    </script>
@endsection