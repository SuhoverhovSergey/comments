<?php

class Model
{
    /**
     * @var null|PDO
     */
    protected $pdo;

    /**
     * Model constructor.
     * @param PDO|null $pdo
     */
    public function __construct(PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->setAttribute($name, $value);
    }

    /**
     * @param $name
     * @param $value
     * @return bool
     */
    public function setAttribute($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            return false;
        }
        return true;
    }
}