<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/projects', 'ProjectController@index')->name('Projects');
Route::get('/projects/create', 'ProjectController@create')->name('CreateProject');
Route::get('/projects/{project}', 'ProjectController@show');
Route::get('/projects/{project}/edit', 'ProjectController@Edit')->name('EditProject');
Route::post('/create-project', 'ProjectController@store')->name('SubmitCreateProject');
Route::delete('/projects/{project}', 'ProjectController@destroy')->name('DestroyProject');
Route::patch('/projects/{project}', 'ProjectController@update')->name('SubmitEditProject');


Route::get('/projects/{project}/servers', 'ServerController@index')->name('ServersIndex');
Route::get('/projects/{project}/servers/create', 'ServerController@create')->name('CreateServer');
Route::get('/projects/{project}/servers/{server}', 'ServerController@show');
Route::get('/projects/{project}/servers/{server}/edit', 'ServerController@Edit')->name('EditServer');
Route::post('/projects/{project}/create-server', 'ServerController@store')->name('SubmitCreateServer');
Route::delete('/projects/{project}/servers/{server}', 'ServerController@destroy')->name('DestroyServer');
Route::patch('/projects/{project}/servers/{server}', 'ServerController@update')->name('SubmitEditServer');

Route::get('/projects/{project}/servers/{server}/deployments', 'DeploymentController@index');
Route::get('/projects/{project}/servers/{server}/deployments/{deployment}', 'DeploymentController@show');
