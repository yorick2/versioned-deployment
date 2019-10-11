<?php
/**
 * Created by PhpStorm.
 * User: yorick
 * Date: 29/08/19
 * Time: 17:30
 */

namespace Tests\phpunit\ReflectionClasses;

use App\GitInteractions\GitMirror;

class ReflectedGitMirrorClass extends GitMirror
{
    /**
     * @return string
     */
    public function getRefFolder(): string
    {
        return $this->refFolder;
    }
}
