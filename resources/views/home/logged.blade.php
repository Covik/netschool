<?php $title = 'Početna'; ?>

@extends('master')

@section('menu')
    @if($user->isAdmin())
        <li class="navigation--active">
            <div class="navigation__icon">
                <i class="glyphicon glyphicon-home"></i>
            </div>
            Početna
        </li>
        <li>
            <div class="navigation__icon">
                <i class="glyphicon glyphicon-file"></i>
            </div>
            Datoteke
        </li>
        <li>
            <div class="navigation__icon">
                <i class="glyphicon glyphicon-user"></i>
            </div>
            Profesori
        </li>
        <li>
            <div class="navigation__icon">
                <i class="glyphicon glyphicon-education"></i>
            </div>
            Studenti
        </li>
    @endif
@endsection