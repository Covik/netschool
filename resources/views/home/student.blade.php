<?php $title = 'Početna'; ?>

@extends('master')

@section('head')
    @if(count($au->theClass->ps) > 0)
        <script>
            var currentClass = {{ $au->theClass->id }};
        </script>
        <script src="/scripts/file-upload.js"></script>
        <script src="/scripts/crud.js"></script>
    @endif
@endsection

@section('content')
    <h1 id="class__name">{{ $au->theClass->name }}
        @if(count($au->theClass->ps) > 0)
            <button id="file__upload__button"><i class="glyphicon glyphicon-upload"></i> Prenesi</button>
            <div id="hiddenfile">
                <input type="file" multiple id="hiddenfileinput" />
            </div>
        @endif
    </h1>
    @if(count($au->theClass->ps) > 0)
        <div id="csf" class="csf__class" data-id="{{ $au->theClass->id }}">
            <div style="overflow: hidden; height: 100%">@include('home.files.subject-files', ['allPS' => $au->theClass->ps, 'class' => $au->theClass])</div>
        </div>
        @include('home.files.crud-script', ['currentSubject' => $au->theClass->ps()->first()->subject->id])
    @else
        <p class="no-data">Nema predmeta!</p>
    @endif
@endsection