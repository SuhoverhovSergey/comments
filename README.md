Comment list
========

### Установка
1. Создать базу данных.
2. Накатить дамп `sql/db.sql`.
3. Создать локальный конфиг `app/config/local.php` из файла `app/config/local.sample.php`.
4. Настроить подключение к БД в файле `app/config/local.php`.

### Запуск приложения
1. В корне проекта выполнить команду `php -S localhost:9995 -t ./public router.php`.
2. Открыть в браузере страницу `http://localhost:9995/comment/index`.