<?php


namespace Interfaces\App\GitInteractions;


use App\DeploymentInterface;
use App\DeploymentMessageCollectionSingletonInterface;
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
    public function deploy(DeploymentInterface $deployment);

    /**
     * @return array
     */
    public function getGitLog();

    /**
     * @param $commitRef string
     * @return string
     */
    public function getGitDiff(string $commitRef);
}
