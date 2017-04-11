<?php

class Controller
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * Controller constructor.
     * @param PDO|null $pdo
     */
    public function __construct(PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $model
     * @return Model
     */
    public function model($model)
    {
        require_once(__DIR__ . '/../models/' . $model . '.php');
        return new $model();
    }

    /**
     * @param string $view
     * @param array $data
     */
    public function view($view, $data = [])
    {
        require_once(__DIR__ . '/../views/' . $view . '.php');
    }
}
