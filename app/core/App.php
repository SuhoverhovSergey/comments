<?php

class App
{
    /**
     * Контроллер по умолчанию.
     * @var string
     */
    protected $controller = "home";

    /**
     * Метод по умолчанию.
     * @var mixed|string
     */
    protected $method = "index";

    /**
     * Параметры, которые передаются в метод контроллера.
     * @var array
     */
    protected $params = [];

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * App constructor.
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {
        $dbConfig = $config['db'] ?? [];
        if ($dbConfig) {
            $dsn = $dbConfig['connectionString'] ?? '';
            $userName = $dbConfig['username'] ?? '';
            $password = $dbConfig['password'] ?? '';
            $this->pdo = new PDO($dsn, $userName, $password);
        }

        $url = $this->parseUrl();
        if (file_exists(__DIR__ . '/../controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once(__DIR__ . '/../controllers/' . $this->controller . '.php');

        $this->controller = new $this->controller($this->pdo);

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Метод для разбора Url.
     * @return array
     */
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(trim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
