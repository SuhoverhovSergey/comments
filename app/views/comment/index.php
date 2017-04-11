<?php
/**
 * @var array $data
 */

$comments = $data['comments'] ?? [];
?>

<?php if ($comments) : ?>
<ul>
<?php foreach ($comments as $comment) { ?>
    <li><?php echo $comment['text'] ?></li>
<?php } ?>
</ul>
<?php endif; ?>