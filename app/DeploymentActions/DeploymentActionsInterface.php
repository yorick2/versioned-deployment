<?php

namespace App\DeploymentActions;

use App\DeploymentInterface;
use App\SshConnectionInterface;

interface DeploymentActionsInterface
{
    public function __construct(SshConnectionInterface $connection, DeploymentInterface $deployment);
}
