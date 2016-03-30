<?php $title = 'Početna'; ?>

@extends('master')

@section('menu')
    @if($user->isAdmin())
        <li>Početna</li>
        <li>Datoteke</li>
        <li>Profesori</li>
        <li>Studenti</li>
    @endif
@endsection