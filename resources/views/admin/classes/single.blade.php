<?php $title = $class->name.' - Razredi'; ?>

<?php

    $allProfessors = '{';

    foreach($professors as $professor) {
        $allProfessors .= $professor->id.': \''.$professor->name.'\',';
    }

    $allProfessors .= '}';

?>

@extends('master')

@section('head')
    <script src="/scripts/crud.js"></script>
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
        <h1 id="class__name">
            <span id="class__name__hover">
                {{ $class->name }}<i class="glyphicon glyphicon-chevron-down"></i>
                <ul id="class__tab__items"><li class="class__tab__item--active">Predmeti</li><li>Datoteke</li></ul>
            </span>
        </h1>
        <div id="class__tabs">
            <section class="class__tab__content">
                @if(count($class->ps) > 0)
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Predmet</th>
                            <th>Nastavnik</th>
                            <th>Datoteka</th>
                            <th>Akcije</th>
                        </tr>
                        </thead>

                        <tbody id="class__body">
                            @foreach($class->ps as $ps)
                                <tr data-crud-id="{{ $ps->id }}">
                                    <td>{{ $ps->subject->name }}</td>
                                    <td data-crud-ref="professor" data-crud-value="{{ $ps->professor->id }}">{{ $ps->professor->name }}</td>
                                    <td>{{ count($ps->subject->files()->where('class_id', '=', $class->id)->get()) }}</td>
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
            </section>
            <section class="class__tab__content">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Naziv</th>
                        <th>Opis</th>
                        <th>Predmet</th>
                        <th>Autor</th>
                        <th>Preneseno</th>
                        <th>Akcije</th>
                    </tr>
                    </thead>

                    <tbody class="files__list">
                    @forelse($class->files()->orderBy('created_at', 'desc')->get()->sortByDesc(function ($file) { return $file->user->isProfessor(); }) as $file)
                        @include('home.files.single', ['file' => $file, 'showSubject' => true])
                    @empty
                        <tr class="table__empty">
                            <td colspan="6">Nema datoteka!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </section>
        </div>
    </div>

    <script>
        $('#class__body').crud({
            updateOptions: {
                url: '{{ Request::url().'/ps/{id}' }}',
                type: 'PUT',
                element: '.crud-modify'
            },
            deleteOptions: {
                url: '{{ Request::url().'/ps/{id}' }}',
                type: 'DELETE',
                element: '.crud-delete'
            },
            def: {
                professor: {
                    type: 'integer',
                    select: {!! $allProfessors !!}
                }
            }
        });
    </script>
    @include('home.files.crud-script', ['currentSubject' => 'null'])
@endsection