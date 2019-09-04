<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeploymentMessage extends Model
{
    protected $fillable = ['name', 'success', 'message'];

    protected $name;
    protected $success;
    protected $message;

}
