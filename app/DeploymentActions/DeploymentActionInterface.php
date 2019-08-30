<?php
/**
 * Created by PhpStorm.
 * User: yorick
 * Date: 29/08/19
 * Time: 14:59
 */

namespace App\DeploymentActions;


interface DeploymentActionInterface
{
    public function __construct(\App\SshConnection $connection,\App\Deployment $deployment);
    public function execute();
}