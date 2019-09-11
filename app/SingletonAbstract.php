<?php

namespace App;

use Illuminate\Database\Eloquent\Concerns\GuardsAttributes;
use Illuminate\Database\Eloquent\Concerns\HidesAttributes;

abstract class SingletonAbstract {

    use GuardsAttributes,
        HidesAttributes;

    /**
     * Call this method to get singleton
     */
    public static function getInstance()
    {
        static $instance = false;
        if( $instance === false )
        {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Make constructor private, so nobody can call "new Class".
     */
    private function __construct()
    {
    }

    /**
     * Make clone magic method private, so nobody can clone instance.
     */
    private function __clone() {}

    /**
     * Make sleep magic method private, so nobody can serialize instance.
     */
    private function __sleep() {}

    /**
     * Make wakeup magic method private, so nobody can unserialize instance.
     */
    private function __wakeup() {}

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get(string $key)
    {
        $setter = 'get'.$key;
        if (method_exists($this, $setter)) {
            return $this->{$setter};
        } else {
            return $this->getAttribute($key);
        }
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string $key
     * @param  mixed $value
     */
    public function __set(string $key, $value): void
    {
        $setter = 'set'.$key;
        if (method_exists($this, $setter)) {
            $this->{$setter}($value);
        } else {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * Get an attribute from the $attributes array.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute(string $key)
    {
        if (isset($this->{$key})) {
            return $this->{$key};
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setAttribute(string $key, $value)
    {
        if(!$this->isFillable($key)){
            throw new \InvalidArgumentException(sprintf(
                'Method %s::Attribute "%s" is not fillable', static::class, $key
            ));
        }
        $this->{$key} = $value;
        return $this;
    }

}
