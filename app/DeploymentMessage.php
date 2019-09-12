<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeploymentMessage extends Model implements DeploymentMessageInterface
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'success', 'message'];

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var bool
     */
    protected $success = false;

    /**
     * @var string
     */
    protected $message = '';

    /**
     * DeploymentMessage constructor.
     * @param string $name
     * @param bool $success
     * @param string $message
     */
    public function __construct(string $name, bool $success, string $message)
    {
        return parent::__construct(
            [
                'name' => $name,
                'success' => $success,
                'message' => $message
            ]
        );
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if($key === 'success'){
            $value = boolval($value);
        }
        return parent::setAttribute($key, $value);
    }


}
