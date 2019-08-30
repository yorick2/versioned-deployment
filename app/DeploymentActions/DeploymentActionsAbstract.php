<?php

namespace App\DeploymentActions;


use App\Deployment;
use App\SshConnection;

abstract class DeploymentActionsAbstract
{
    protected $connection;
    protected $deployment;

    public function __construct(SshConnection $connection, Deployment $deployment)
    {
        $this->connection = $connection;
        $this->deployment = $deployment;
    }

    abstract protected function execute();
}