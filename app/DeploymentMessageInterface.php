<?php

namespace App;

interface DeploymentMessageInterface
{
    /**
     * DeploymentMessageInterface constructor.
     * @param string $name
     * @param bool $success
     * @param string $message
     */
    public function __construct(string $name, bool $success, string $message);
}
