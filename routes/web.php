<?php
App::bind('App\DeploymentActions\DeploymentActionsAbstractInterface',
    'App\DeploymentActions\DeploymentActionsAbstract');
App::bind('App\DeploymentActions\PostDeploymentCommandsInterface',
    'App\DeploymentActions\PostDeploymentCommands');
App::bind('App\DeploymentActions\PreDeploymentCommandsInterface',
    'App\DeploymentActions\PreDeploymentCommands');
App::bind('App\DeploymentActions\RemoveOldReleasesInterface',
    'App\DeploymentActions\RemoveOldReleases');
App::bind('App\DeploymentActions\LinkSharedFilesInterface','App\DeploymentActions\LinkSharedFiles');
App::bind('App\DeploymentActions\UpdateCurrentAndPreviousLinksInterface',
    'App\DeploymentActions\UpdateCurrentAndPreviousLinks');
App::bind('App\GitInteractions\GitLocalInterface',
    'App\GitInteractions\GitLocal');
App::bind('App\GitInteractions\GitMirrorInterface',
    'App\GitInteractions\GitMirror');
App::bind('App\GitInteractions\GitInterface',
    'App\GitInteractions\Git');
App::bind('App\DeploymentMessageInterface',
    'App\DeploymentMessage');
App::bind('App\DeploymentInterface',
    'App\Deployment');
App::bind('App\ProjectInterface',
    'App\Project');
App::bind('App\ServerInterface',
    'App\Server');
App::bind('App\SshConnectionInterface',
    'App\SshConnection');
App::bind('App\UserInterface',
    'App\User');

App::singleton('App\DeploymentMessageCollectionSingletonInterface', function()
{
    return \App\DeploymentMessageCollectionSingleton::getInstance();
});

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

Route::get('/projects', 'ProjectController@index')->name('ProjectsIndex');
Route::get('/projects/create', 'ProjectController@create')->name('CreateProject');
Route::get('/projects/{project}', 'ProjectController@show')->name('ShowProject');
Route::get('/projects/{project}/edit', 'ProjectController@edit')->name('EditProject');
Route::get('/projects/{project}/delete', 'ProjectController@delete')->name('DeleteProject');
Route::post('/create-project', 'ProjectController@store')->name('SubmitCreateProject');
Route::delete('/projects/{project}', 'ProjectController@destroy')->name('DestroyProject');
Route::patch('/projects/{project}', 'ProjectController@update')->name('SubmitEditProject');

Route::get('/projects/{project}/servers', 'ServerController@index')->name('ServersIndex');
Route::get('/projects/{project}/servers/create', 'ServerController@create')->name('CreateServer');
Route::get('/projects/{project}/servers/{server}', 'ServerController@show')->name('ShowServer');
Route::get('/projects/{project}/servers/{server}/edit', 'ServerController@edit')
    ->name('EditServer');
Route::get('/projects/{project}/servers/{server}/delete', 'ServerController@delete')
    ->name('DeleteServer');
Route::post('/projects/{project}/create-server', 'ServerController@store')->name('SubmitCreateServer');
Route::delete('/projects/{project}/servers/{server}', 'ServerController@destroy')->name('DestroyServer');
Route::patch('/projects/{project}/servers/{server}', 'ServerController@update')->name('SubmitEditServer');

Route::get('/projects/{project}/servers/{server}/deployments', 'DeploymentController@index')
    ->name('DeploymentsIndex');
Route::get('/projects/{project}/servers/{server}/deployments/create', 'DeploymentController@create')
    ->name('CreateDeployment');
Route::get('/projects/{project}/servers/{server}/deployments/{deployment}', 'DeploymentController@show')
    ->name('ShowDeployment');
Route::post('/projects/{project}/servers/{server}/deployments/diff', 'DeploymentController@gitDiff')
    ->name('GitDiffDeployment');
Route::post('/projects/{project}/servers/{server}/create-deployment', 'DeploymentController@store')
    ->name('SubmitCreateDeployment');
