<?php

class Comment extends Model
{
    /**
     * Первый уровень комментариев.
     */
    const TOP_LEVEL = 1;

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $text;

    /**
     * @var int
     */
    public $left_key;

    /**
     * @var int
     */
    public $right_key;

    /**
     * @var int
     */
    public $level;

    /**
     * Название таблицы модели.
     * @var string
     */
    public static $tableName = 'comment';

    /**
     * Возвращает комментарии первого уровеня.
     * @return Comment[]
     */
    public function getTopLevel()
    {
        $pdoStatement = $this->pdo->prepare("SELECT * FROM {$this::$tableName} WHERE level = :level");
        $pdoStatement->execute([':level' => static::TOP_LEVEL]);
        return $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class, [$this->pdo]);
    }

    /**
     * @param int $id
     * @return null|Comment
     */
    public function getById($id)
    {
        $pdoStatement = $this->pdo->prepare("SELECT * FROM {$this::$tableName} WHERE id = :id");
        $pdoStatement->execute([':id' => $id]);
        $object = $pdoStatement->fetchObject(self::class, [$this->pdo]);
        return $object ?? null;
    }

    /**
     * @param $text
     * @param Comment|null $parentComment
     * @return null|Comment
     */
    public function add($text, $parentComment = null)
    {
        $pdo = $this->pdo;
        $pdo->beginTransaction();

        $newId = 0;
        try {
            $text = htmlentities($text, ENT_QUOTES, "UTF-8");

            if ($parentComment) {
                $pdoStatement = $pdo->prepare("UPDATE {$this::$tableName} SET left_key = left_key + 2, right_key = right_key + 2 WHERE left_key > :right_key");
                $pdoStatement->execute([':right_key' => $parentComment->right_key]);

                $pdoStatement = $pdo->prepare("UPDATE {$this::$tableName} SET right_key = right_key + 2 WHERE right_key >= :right_key AND left_key < :right_key");
                $pdoStatement->execute([':right_key' => $parentComment->right_key]);

                $pdoStatement = $pdo->prepare("INSERT INTO {$this::$tableName} SET left_key = :right_key, right_key = :right_key + 1, level = :level + 1, text = :text");
                $pdoStatement->execute([':right_key' => $parentComment->right_key, ':level' => $parentComment->level, ':text' => $text]);
            } else {
                $rightKey = $pdo->query("SELECT MAX(right_key) FROM {$this::$tableName}")->fetchColumn();
                if (!$rightKey) {
                    $rightKey = 1;
                }
                $rightKey++;

                $pdoStatement = $pdo->prepare("INSERT INTO {$this::$tableName} SET left_key = :right_key, right_key = :right_key + 1, level = :level, text = :text");
                $pdoStatement->execute([':right_key' => $rightKey, ':level' => static::TOP_LEVEL, ':text' => $text]);
            }

            $newId = $pdo->lastInsertId();
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
        }

        return $newId ? $this->getById($newId) : null;
    }
}
