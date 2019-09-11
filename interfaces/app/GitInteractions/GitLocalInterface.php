<?php


namespace Interfaces\App\GitInteractions;


interface GitLocalInterface
{
    /**
     * @param string $remoteUrl
     * @return mixed
     */
    public function getGitBranches(string $remoteUrl);
}
