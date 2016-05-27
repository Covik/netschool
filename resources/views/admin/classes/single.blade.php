<?php $title = $class->name.' - Razredi'; ?>

@extends('master')

@section('head')
    <script src="/scripts/crud.js"></script>
    <script>
        $('#class__body').crud({
            deleteOptions: {
                url: '{{ Request::url().'/ps/{id}' }}',
                type: 'DELETE',
                element: '.crud-delete'
            }
        });
    </script>
@endsection

@section('content')
    <div class="default__append__component">
        <h2>Dodaj</h2>
        <form class="append-form" method="post" action="{{ Request::url() }}/ps" onsubmit="return false;" autocomplete="off">
            <div>
                Predmet:<br />
                <select name="subject">
                    <option value="-1">Odaberi predmet</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                Nastavnik:<br />
                <select name="professor">
                    <option value="-1">Odaberi nastavnika</option>
                    @foreach($professors as $professor)
                        <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit"><i class="glyphicon glyphicon-send"></i> Pošalji</button>
            </div>
        </form>
    </div>
    <div style="overflow: hidden">
        <h1 id="class__name">{{ $class->name }}</h1>
        @if(count($class->ps) > 0)
            <table class="table">
                <thead>
                <tr>
                    <th>Predmet</th>
                    <th>Nastavnik</th>
                    <th>Akcije</th>
                </tr>
                </thead>

                <tbody id="class__body">
                    @foreach($class->ps as $ps)
                        <tr data-crud-id="{{ $ps->id }}">
                            <td>{{ $ps->subject->name }}</td>
                            <td>{{ $ps->professor->name }}</td>
                            <td data-crud-actions>
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
@endsection