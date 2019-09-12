<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Bind Custom Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed.
|
*/
$app->singleton('App\DeploymentMessageCollectionSingletonInterface', function()
{
    return \App\DeploymentMessageCollectionSingleton::getInstance();
});
$app->bind('App\DeploymentActions\DeploymentActionsAbstractInterface',
    'App\DeploymentActions\DeploymentActionsAbstract');
$app->bind('App\DeploymentActions\PostDeploymentCommandsInterface',
    'App\DeploymentActions\PostDeploymentCommands');
$app->bind('App\DeploymentActions\PreDeploymentCommandsInterface',
    'App\DeploymentActions\PreDeploymentCommands');
$app->bind('App\DeploymentActions\RemoveOldReleasesInterface',
    'App\DeploymentActions\RemoveOldReleases');
$app->bind('App\DeploymentActions\LinkSharedFilesInterface','App\DeploymentActions\LinkSharedFiles');
$app->bind('App\DeploymentActions\UpdateCurrentAndPreviousLinksInterface',
    'App\DeploymentActions\UpdateCurrentAndPreviousLinks');
$app->bind('App\GitInteractions\GitLocalInterface',
    'App\GitInteractions\GitLocal');
$app->bind('App\GitInteractions\GitMirrorInterface',
    'App\GitInteractions\GitMirror');
$app->bind('App\GitInteractions\GitInterface',
    'App\GitInteractions\Git');
$app->bind('App\DeploymentMessageInterface',
    'App\DeploymentMessage');
$app->bind('App\DeploymentInterface',
    'App\Deployment');
$app->bind('App\ProjectInterface',
    'App\Project');
$app->bind('App\ServerInterface',
    'App\Server');
$app->bind('App\SshConnectionInterface',
    'App\SshConnection');
$app->bind('App\UserInterface',
    'App\User');



/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
