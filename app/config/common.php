<?php
/**
 * Конфигурационный файл.
 */

$config = [
    'db' => [
        'connectionString' => '',
        'username' => '',
        'password' => '',
    ],
];

$localConfig = [];
if (file_exists(__DIR__ . '/local.php')) {
    $localConfig = include(__DIR__ . '/local.php');
}

return array_merge($config, $localConfig);