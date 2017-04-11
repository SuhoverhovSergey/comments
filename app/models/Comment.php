<?php

class Comment extends Model
{
    /**
     * Первый уровень комментариев.
     */
    const TOP_LEVEL = 1;

    /**
     * Название таблицы модели.
     * @var string
     */
    public static $tableName = 'comment';

    /**
     * Возвращает комментарии первого уровеня.
     */
    public function getTopLevel()
    {
        $pdoStatement = $this->pdo->prepare("SELECT * FROM {$this::$tableName} WHERE level = :level");
        $pdoStatement->execute([':level' => static::TOP_LEVEL]);
        return $pdoStatement->fetchAll();
    }
}
