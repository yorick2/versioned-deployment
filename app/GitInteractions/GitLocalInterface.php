<?php


namespace App\GitInteractions;


interface GitLocalInterface
{
    /**
     * @param string $remoteUrl
     * @return mixed
     */
    public function getGitBranches(string $remoteUrl): array;
}
