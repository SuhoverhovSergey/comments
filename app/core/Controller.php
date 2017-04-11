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

    public function model($model)
    {
        require_once(__DIR__ . '/../models/' . $model . '.php');
        return new $model();
    }

    public function view($view, $data = [])
    {
        require_once(__DIR__ . '/../views/' . $view . '.php');
    }
}
