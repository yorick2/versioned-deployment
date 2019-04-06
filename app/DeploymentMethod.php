<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeploymentMethod extends Model
{
    protected $guarded = [];

    private $git;


    public function __construct(
        array $attributes = []
    ) {
        $this->git = new Git();
        parent::__construct($attributes);
    }

    /**
     * @param $deployment
     * @return array
     */
    public function execute($deployment)
    {
        return $this->git->deploy($deployment);
    }

    public function cloneRepository ($repository)
    {

    }

}
