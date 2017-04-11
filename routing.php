<?php
/**
 * Файл роутинга.
 */

$filePath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$file = __DIR__ . '/' . trim($filePath, '/');

if ($filePath != '/' && file_exists($file)) {
    return false; // файл (или директория) существует, отдаем как есть
}

$_GET['url'] = $filePath;

unset($filePath, $file);

require_once(__DIR__ . '/public/index.php');
