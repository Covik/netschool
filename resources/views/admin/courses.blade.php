<?php $title = 'Smjerovi'; ?>

@extends('master')

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
            @if(!empty($courses))
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ime</th>
                            <th>Trajanje (godina)</th>
                            <th>Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td>{{ $course->name }}</td>
                        <td>{{ $course->duration }}</td>
                        <td>
                            <i class="glyphicon glyphicon-pencil"></i>
                            <i class="glyphicon glyphicon-remove"></i>
                        </td>
                    </tr>
                @endforeach
                    </tbody>
                </table>
            @else
                <p>Nema smjerova!</p>
            @endif
        </div>
    </div>
@endsection