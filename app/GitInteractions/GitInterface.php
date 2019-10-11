<?php


namespace App\GitInteractions;

use App\DeploymentInterface;
use App\ServerInterface;
use App\SshConnectionInterface;

interface GitInterface
{
    /**
     * Git constructor.
     * @param SshConnectionInterface $sshConnection
     * @param ServerInterface $server
     */
    public function __construct(SshConnectionInterface $sshConnection, ServerInterface $server);

    /**
     * @param DeploymentInterface $deployment
     */
    public function deploy(DeploymentInterface $deployment): void;

    /**
     * @return array
     */
    public function getGitLog(): array;

    /**
     * @param $commitRef string
     * @return string
     */
    public function getGitDiff(string $commitRef): string;
}
