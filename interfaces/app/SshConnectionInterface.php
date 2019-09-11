<?php

namespace Interfaces\App;

interface SshConnectionInterface
{
    public function connect();

    /**
     * @return string
     */
    public function getPublicKey();

    /**
     * @param string $cmd
     * @return DeploymentMessageInterface
     */
    public function execute($cmd);

    /**
     * @return void
     */
    public function disconnect();

}
