<?php

class CommentController extends Controller
{
    public function index()
    {
        /** @var Comment $commentModel */
        $commentModel = $this->model('Comment');
        $comments = $commentModel->getTopLevel();
        $this->view('comment/index', ['comments' => $comments]);
    }
}