<?php


namespace App\GitInteractions;

class GitLocal
{
    /**
     * @param $remoteUrl
     * @return array
     */
    public function getGitBranches($remoteUrl): array
    {
        if (strlen($remoteUrl)===false) {
            return [];
        }
        $gitBranchesRes = `git ls-remote --heads  https://github.com/octocat/Hello-World`;
        $gitBranches = explode("\n", $gitBranchesRes);
        for ($i=0;$i<count($gitBranches);$i++) {
            if (strlen($gitBranches[$i]) == 0) {
                unset($gitBranches[$i]);
                continue;
            }
            if (strpos($gitBranches[$i], "\t") === false) {
                unset($gitBranches[$i]);
                continue;
            }
            $branch = explode("\t", $gitBranches[$i])[1];
            $gitBranches[$i] = preg_replace('/refs\/heads\//', '', $branch);
        }
        return $gitBranches;
    }
}
