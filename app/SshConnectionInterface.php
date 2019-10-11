<?php

namespace App;

interface SshConnectionInterface
{
    public function connect();

    /**
     * @return string
     */
    public function getPublicKey(): string;

    /**
     * @param string $cmd
     * @return DeploymentMessageInterface
     */
    public function execute($cmd): DeploymentMessageInterface;

    /**
     * @return void
     */
    public function disconnect(): void;
}
