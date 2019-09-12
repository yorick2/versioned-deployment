<?php

namespace App\DeploymentActions;


use App\DeploymentInterface;
use App\SshConnectionInterface;

abstract class DeploymentActionsAbstract
{
    protected $connection;
    protected $deployment;

    public function __construct(SshConnectionInterface $connection, DeploymentInterface $deployment)
    {
        $this->connection = $connection;
        $this->deployment = $deployment;
    }

    abstract protected function execute();
}