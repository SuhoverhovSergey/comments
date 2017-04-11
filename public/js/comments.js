$(document).ready(function () {
    function addForm(link, li, buttonClickCallback) {
        var form = $('<form><textarea></textarea><br></form>');
        var button = $('<button>Отправить</button>');
        var cancel = $('<a href="#" style="margin-left: 10px;">Отмена</a>');

        if (link.hasClass('edit')) {
            form.children('textarea').val(li.children('span').text());
        }

        form.append(button);
        form.append(cancel);

        li.append(form);
        link.hide();

        button.on('click', function () {
            buttonClickCallback(form, li);
            return false;
        });

        cancel.on('click', function () {
            link.show();
            form.remove();
            return false;
        });
    }

    function createComment(parentId, text, success) {
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
                        if (!$('ul.comments').length) {
                            $('body').prepend('<ul class="comments"></ul>');
                        }
                        $('ul.comments').append(getCommentTemplate(result.data.id, result.data.text));
                    }
                    success();
                } else {
                    alert(result.message);
                }
            }
        });
    }

    function updateComment(id, text, success) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: "/comment/update",
            data: {
                id: id,
                text: text
            },
            success: function(result) {
                if (result.success) {
                    var li = $('li[data-id="' + id + '"]');
                    li.children('span').text(result.data.text);
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
        addForm(link, li, function (form, li) {
            var text = form.find('textarea').val();
            var parentId = li.data('id');

            createComment(parentId, text, function () {
                link.show();
                form.remove();
            });

            return false;
        });
        return false;
    });

    $('body').on('click', 'li > a.edit', function() {
        var link = $(this);
        var li = link.parent();
        addForm(link, li, function (form, li) {
            var text = form.find('textarea').val();
            var id = li.data('id');

            updateComment(id, text, function () {
                link.show();
                form.remove();
            });

            return false;
        });
        return false;
    });

    $('body').on('click', 'form > button.send', function () {
        var form = $(this).parent();
        var textarea = form.find('textarea');
        var text = textarea.val();
        var parentId = 0;

        createComment(parentId, text, function () {
            textarea.val('');
        });

        return false;
    });

    $('body').on('click', 'li > a.delete', function () {
        var link = $(this);
        var li = link.parent();
        var id = li.data('id');

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: "/comment/delete",
            data: {
                id: id
            },
            success: function(result) {
                if (result.success) {
                    var ul = li.parent();
                    li.remove();
                    if (!ul.children('li').length) {
                        ul.remove();
                    }
                } else {
                    alert(result.message);
                }
            }
        });

        return false;
    });

    $('body').on('click', 'li > a.toggle', function () {
        var link = $(this);
        var li = link.parent();
        var id = li.data('id');

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: "/comment/getChildNodes",
            data: {
                id: id
            },
            success: function(result) {
                if (result.success) {
                    li.children('ul').remove();

                    var nodes = result.data.nodes;

                    var prevLevel = 1;
                    var parentLi = li;
                    var lastLi = li;
                    for (var key in nodes) {
                        var node = nodes[key];
                        var nextLevel = parseInt(node.level);

                        if ((nextLevel - prevLevel) > 0) {
                            var ul = lastLi.find('ul[data-level="' + node.level + '"]:last');
                            if (!ul.length) {
                                ul = $('<ul data-level="' + node.level + '"></ul>');
                                lastLi.append(ul);
                            }
                            lastLi = $(getCommentTemplate(node.id, node.text));
                            ul.append(lastLi);
                            prevLevel = parseInt(node.level);
                        } else if ((nextLevel - prevLevel) == 0) {
                            var ul = lastLi.parent();
                            lastLi = $(getCommentTemplate(node.id, node.text));
                            ul.append(lastLi);
                            prevLevel = parseInt(node.level);
                        } else {
                            var ul = parentLi.find('ul[data-level="' + node.level + '"]:last');
                            lastLi = $(getCommentTemplate(node.id, node.text));
                            ul.append(lastLi);
                            prevLevel = parseInt(node.level);
                        }
                    }
                } else {
                    alert(result.message);
                }
            }
        });

        return false;
    });
});