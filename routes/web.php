<?php
//App::bind('Interfaces\App\DeploymentActions\DeploymentActionsAbstractInterface',
//    'App\DeploymentActions\DeploymentActionsAbstract');
//App::bind('Interfaces\App\DeploymentActions\LinkSharedFilesInterface',
//    'App\DeploymentActions\LinkSharedFiles');
//App::bind('Interfaces\App\DeploymentActions\PostDeploymentCommandsInterface',
//    'App\DeploymentActions\PostDeploymentCommands');
App::bind('Interfaces\App\DeploymentActions\PreDeploymentCommandsInterface',
    'App\DeploymentActions\PreDeploymentCommands');
App::bind('Interfaces\App\DeploymentActions\RemoveOldReleasesInterface',
    'App\DeploymentActions\RemoveOldReleases');
App::bind('Interfaces\App\DeploymentActions\UpdateCurrentAndPreviousLinksInterface',
    'App\DeploymentActions\UpdateCurrentAndPreviousLinks');
App::bind('Interfaces\App\GitInteractions\GitLocalInterface',
    'App\GitInteractions\GitLocal');
App::bind('Interfaces\App\GitInteractions\GitMirrorInterface',
    'App\GitInteractions\GitMirror');
App::bind('Interfaces\App\GitInteractions\GitInterface',
    'App\GitInteractions\Git');
App::bind('Interfaces\App\DeploymentMessageCollectionSingletonInterface',
    'App\DeploymentMessageCollectionSingleton');
App::bind('Interfaces\App\DeploymentMessageInterface',
    'App\DeploymentMessage');
App::bind('Interfaces\App\DeploymentInterface',
    'App\Deployment');
App::bind('Interfaces\App\ProjectInterface',
    'App\Project');
App::bind('Interfaces\App\ServerInterface',
    'App\Server');
App::bind('Interfaces\App\SshConnectionInterface',
    'App\SshConnection');
App::bind('Interfaces\App\UserInterface',
    'App\User');

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
