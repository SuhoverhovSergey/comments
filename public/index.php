<?php
/**
 * Точка входа в приложение.
 */

require_once(__DIR__ . '/../app/bootstrap.php');

$config = include(__DIR__ . '/../app/config/common.php');

$app = new App($config);
