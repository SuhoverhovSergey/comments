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
}