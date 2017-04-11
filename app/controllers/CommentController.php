<?php

class CommentController extends Controller
{
    public function index()
    {
        $this->view('comment/index');
    }
}