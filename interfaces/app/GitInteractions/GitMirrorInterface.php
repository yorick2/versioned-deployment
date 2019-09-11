<?php

namespace Interfaces\App\GitInteractions;

use Interfaces\App\ServerInterface;
use Interfaces\App\SshConnectionInterface;

interface GitMirrorInterface
{

    /**
     * Git constructor.
     * @param SshConnectionInterface $sshConnection
     * @param ServerInterface $server
     */
    public function __construct(SshConnectionInterface $sshConnection, ServerInterface $server);

    /**
     * make the git reference folder to use as a mirror. So the whole repo isn't downloaded each time. Reducing download time
     */
    public function update();

    /**
     * @return array
     */
    public function clear();
}