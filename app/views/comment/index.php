<?php
/**
 * @var array $data
 */

$comments = $data['comments'] ?? [];
?>

<?php if ($comments) : ?>
<ul class="comments">
<?php foreach ($comments as $comment) { ?>
    <li data-id="<?php echo $comment->id ?>">
        <a href="#" class="toggle" style="text-decoration: none">+</a>
        <span><?php echo $comment->text ?></span>
        <br>
        <a href="#" class="add">Ответить</a>
        <a href="#" class="edit">Редактировать</a>
        <a href="#" class="delete">Удалить</a>
    </li>
<?php } ?>
</ul>
<?php endif; ?>
<form>
    <textarea></textarea>
    <br>
    <button class="send">Отправить</button>
</form>

<script src="/js/jquery-3.2.1.min.js"></script>
<script src="/js/comments.js"></script>