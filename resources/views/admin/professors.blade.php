<?php $title = 'Nastavnici'; ?>

@extends('master')

@section('head')
    <script src="/scripts/crud.js"></script>
@endsection

@section('content')
    <div id="professors">
        <div id="professors__append" class="default__append__component">
            <h2>Dodaj profesora</h2>
            <form class="append-form" method="post" action="/professors" onsubmit="return false;">
                <div>
                    Ime:<br />
                    <input type="text" name="name" maxlength="255" autocomplete="off" />
                </div>

                <div>
                    Email:<br />
                    <input type="email" name="email" maxlength="255" autocapitalize="off" autocorrect="off" autocomplete="email" />
                </div>

                <div>
                    Lozinka:<br />
                    <input type="password" name="password">
                </div>
                <div>
                    Potvrda lozinke:<br />
                    <input type="password" name="password_confirmation">
                </div>


                <div>
                    <button type="submit"><i class="glyphicon glyphicon-send"></i> Pošalji</button>
                </div>
            </form>
        </div>

        <div>
            @if(count($professors) > 0)
                <table class="table">
                    <thead>
                    <tr>
                        <th>Ime</th>
                        <th>Email</th>
                        <th>Datoteka</th>
                        <th>Akcije</th>
                    </tr>
                    </thead>
                    <tbody id="professors-body">
                    @foreach($professors as $professor)
                        <tr data-crud-id="{{ $professor->id }}">
                            <td data-crud-ref="name">{{ $professor->name }}</td>
                            <td data-crud-ref="email">{{ $professor->email }}</td>
                            <td>{{ count($professor->files) }}</td>
                            <td data-crud-actions>
                                <button class="crud-modify crud-button"><i class="glyphicon glyphicon-pencil"></i></button>
                                <button class="crud-delete crud-button"><i class="glyphicon glyphicon-remove"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">Nema profesora!</p>
            @endif
        </div>
    </div>

    <script>
        $('#professors-body').crud({
            updateOptions: {
                url: '/professors/{id}',
                type: 'PUT',
                element: '.crud-modify'
            },
            deleteOptions: {
                url: '/professors/{id}',
                type: 'DELETE',
                element: '.crud-delete'
            },
            def: {
                'name': {
                    'type': 'string',
                    'length': 255
                },
                'email': {
                    'type': 'email',
                    'length': 255
                }
            }
        });
    </script>
@endsection
