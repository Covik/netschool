<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class FileController extends Controller
{
    public function index() {
        $files = [
            0 => [
                'name' => 'test-fajl.txt',
                'description' => 'Neki opis',
                'author' => 'Tome',
                'hash' => 'd2jklaslk21ppkqq2kfjaksl'
            ]
        ];

        return view('files.index', compact('files'));
    }
}
