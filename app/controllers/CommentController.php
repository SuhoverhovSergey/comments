<?php

class CommentController extends Controller
{
    public function index()
    {
        /** @var Comment $commentModel */
        $commentModel = $this->model(Comment::class);
        $comments = $commentModel->getTopLevel();
        $this->view('comment/index', ['comments' => $comments]);
    }

    public function create()
    {
        $parentId = $_POST['parentId'] ?? 0;
        $text = $_POST['text'] ?? '';

        $result = [
            'success' => false,
        ];

        /** @var Comment $commentModel */
        $commentModel = $this->model(Comment::class);
        $parentComment = $commentModel->getById($parentId);

        if ($text) {
            $comment = $commentModel->add($text, $parentComment);
            if ($comment) {
                $result['success'] = true;
                $result['data'] = ['id' => $comment->id, 'text' => $comment->text];
            } else {
                $result['message'] = 'Не удалось добавить комментарий';
            }
        } else {
            $result['message'] = 'Введите текст коментария';
        }

        echo json_encode($result);
    }

    public function update()
    {
        $id = $_POST['id'] ?? 0;
        $text = $_POST['text'] ?? '';

        $result = [
            'success' => false,
        ];

        /** @var Comment $commentModel */
        $commentModel = $this->model(Comment::class);
        $comment = $commentModel->getById($id);

        if ($text) {
            if ($comment && $comment = $commentModel->updateText($text, $comment)) {
                $result['success'] = true;
                $result['data'] = ['id' => $comment->id, 'text' => $comment->text];
            } else {
                $result['message'] = 'Не удалось обновить комментарий';
            }
        } else {
            $result['message'] = 'Введите текст коментария';
        }

        echo json_encode($result);
    }

    public function delete()
    {
        $id = $_POST['id'] ?? 0;

        $result = [
            'success' => false,
        ];

        /** @var Comment $commentModel */
        $commentModel = $this->model(Comment::class);
        $comment = $commentModel->getById($id);

        if ($comment && $commentModel->delete($comment)) {
            $result['success'] = true;
        } else {
            $result['message'] = 'Не удалось удалить комментарий';
        }

        echo json_encode($result);
    }

    public function getChildNodes()
    {
        $id = $_POST['id'] ?? 0;

        $result = [
            'success' => false,
        ];

        /** @var Comment $commentModel */
        $commentModel = $this->model(Comment::class);
        $comment = $commentModel->getById($id);

        if ($comment) {
            $nodes = $commentModel->getChildNodes($comment);
            $result['success'] = true;
            $result['data']['nodes'] = $nodes;
        } else {
            $result['message'] = 'Не удалось получить список комментариев';
        }

        echo json_encode($result);
    }
}