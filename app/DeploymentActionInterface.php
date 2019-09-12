<?php

namespace App;

interface DeploymentActionInterface
{


    public function __construct(array $attributes = []);

    /**
     * @param Deployment $deployment
     * @return mixed
     */
    public function execute(Deployment $deployment);

}
