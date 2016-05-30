<?php $title = 'Početna'; ?>

@extends('master')

<?php
    $firstPS = $au->ps()->first();
    $classes = $au->classes();
    if($firstPS !== null) $firstClass = reset($classes);
?>

@section('head')
    @if($firstPS !== null)
        <script>
            var currentClass = {{ $firstClass->id }};
        </script>
        <script src="/scripts/file-upload.js"></script>
        <script src="/scripts/crud.js"></script>
    @endif
@endsection

@section('content')
    @if($firstPS !== null)
        <h1 id="class__name">{{ $firstClass->name }}
            <button id="file__upload__button"><i class="glyphicon glyphicon-upload"></i> Prenesi</button>
            <div id="hiddenfile">
                <input type="file" multiple id="hiddenfileinput" />
            </div>
        </h1>
        <div id="csf">
            <div style="overflow: hidden; height: 100%">
            <section id="csf__classes__list">
                <ul>
                    @foreach($classes as $class)
                        <li class="csf__class__item{{ $class->id == $firstClass->id ? ' csf__class--active' : '' }}" data-id="{{ $class->id }}">
                            <span class="csf__class__name">{{ $class->name }}</span>
                        </li>
                    @endforeach
                </ul>
            </section><!--
            --><section id="csf__classes__content">
                <ul>
                    @foreach($classes as $class)
                        <li class="csf__class" data-id="{{ $class->id }}">@include('home.files.subject-files', ['allPS' => $class->ps()->where('professor_id', '=', $au->id)->get(), 'class' => $class])</li>
                    @endforeach
                </ul>
            </section>
            </div>
        </div>
        @include('home.files.crud-script', ['currentSubject' => $firstClass->ps()->where('professor_id', '=', $au->id)->first()->subject->id])
    @else
        <p class="no-data">Nema razreda i predmeta</p>
    @endif
@endsection