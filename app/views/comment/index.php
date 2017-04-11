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
<script>
    $(document).ready(function () {
        function addForm(link, li) {
            var form = $('<form><textarea></textarea><br></form>');
            var button = $('<button>Отправить</button>');
            var cancel = $('<a href="#" style="margin-left: 10px;">Отмена</a>');

            form.append(button);
            form.append(cancel);

            li.append(form);
            link.hide();

            button.on('click', function () {
                var text = form.find('textarea').val();
                var parentId = li.data('id');

                sendComment(parentId, text, function () {
                    link.show();
                    form.remove();
                });

                return false;
            });

            cancel.on('click', function () {
                link.show();
                form.remove();
                return false;
            });
        }

        function sendComment(parentId, text, success) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: "/comment/create",
                data: {
                    parentId: parentId,
                    text: text
                },
                success: function(result) {
                    if (result.success) {
                        if (parentId) {
                            var parentLi = $('li[data-id="' + parentId + '"]');
                            var ul = parentLi.children('ul');

                            if (!ul.length) {
                                ul = $('<ul></ul>');
                                parentLi.append(ul);
                            }

                            ul.append(getCommentTemplate(result.data.id, result.data.text));
                        } else {
                            $('ul.comments').append(getCommentTemplate(result.data.id, result.data.text));
                        }
                        success();
                    } else {
                        alert(result.message);
                    }
                }
            });
        }

        function getCommentTemplate(id, text) {
            return '<li data-id="' + id + '">' +
                '<span>' + text + '</span><br>' +
                '<a href="#" class="add">Ответить</a> ' +
                '<a href="#" class="edit">Редактировать</a> ' +
                '<a href="#" class="delete">Удалить</a></li>';
        }

        $('body').on('click', 'li > a.add', function() {
            var link = $(this);
            var li = link.parent();
            addForm(link, li);
            return false;
        });

        $('body').on('click', 'form > button.send', function () {
            var form = $(this).parent();
            var textarea = form.find('textarea');
            var text = textarea.val();
            var parentId = 0;

            sendComment(parentId, text, function () {
                textarea.val('');
            });

            return false;
        });
    });
</script>