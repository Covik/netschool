<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'HomeController@index');
    Route::post('/login', ['middleware' => ['guest', 'ajax'], 'uses' => 'HomeController@signin']);
    Route::post('/register', ['middleware' => ['guest', 'ajax'], 'uses' => 'HomeController@register']);
    Route::get('/logout', 'HomeController@logout');
    Route::get('/file/{id}/{name}', ['uses' => 'FileController@get', 'as' => 'file-download']);

    // Admin
    Route::group(['middleware' => 'admin'], function () {
        Route::resource('courses', 'CourseController', ['only' => ['index', 'store', 'update', 'destroy']]);
        Route::resource('classes', 'ClassController', ['only' => ['index', 'store', 'update', 'destroy']]);
        Route::get('/classes/{id}', ['uses' => 'ClassController@single', 'as' => 'single-class']);
        Route::post('/classes/{id}/ps', 'ClassController@storePS');
        Route::delete('/classes/{id}/ps/{psid}', 'ClassController@destroyPS');
        Route::resource('professors', 'ProfessorsController', ['only' => ['index', 'store', 'update', 'destroy']]);
        Route::resource('subjects', 'SubjectController', ['only' => ['index', 'store', 'update', 'destroy']]);
        Route::get('/subjects/{slug}', 'SubjectController@single');
        Route::post('/subjects/{slug}', 'SubjectController@saveCourses');
        Route::resource('students', 'StudentController', ['only' => ['index', 'store', 'update', 'destroy']]);
    });

    // Professor/Student
    Route::group(['middleware' => ['ajax', 'professor-student']], function () {
        Route::post('/files/store', 'FileController@store');
    });

    // Files
    Route::resource('files', 'FileController', ['only' => ['index', 'update', 'destroy']]);
});
