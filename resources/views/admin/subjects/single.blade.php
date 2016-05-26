<?php $title = $subject->name.' - Predmeti'; ?>

@extends('master')

@section('content')
    <h1 id="subject__name">{{ $subject->name }}</h1>
    <div id="subject__courses__actions">
        <button id="subject__courses__checkbox__control"><i class="glyphicon glyphicon-check"></i> Označi sve</button>
        <button id="subject__courses__save"><i class="glyphicon glyphicon-ok"></i> Spremi</button>
    </div>
    <ul id="subject__courses">
        @foreach($courses as $course)
            <li data-id="{{ $course->id }}">
                <h3 class="courses__title">{{ $course->name }}</h3>
                <div class="courses__classes">
                    Razredi:
                    @for($r = 1; $r <= $course->duration; $r++)
                        <label class="courses__classes__year">
                            <input type="checkbox" {{ count($subject->courses()->where('course_id', '=', $course->id)->where('course_year', '=', $r)->get()) == 1 ? ' checked' : '' }} autocomplete="off" /> {{ $r }}. razred
                        </label>
                    @endfor
                </div>
            </li>
        @endforeach
    </ul>
@endsection
