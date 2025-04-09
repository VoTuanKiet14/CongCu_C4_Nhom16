<?php

namespace App\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\Role;

class NewsController
{
    public function index()
    {
        $news = News::all();
        require_once '../app/views/news/index.php';
    }

    public function create()
    {
        require_once '../app/views/news/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $author = $_POST['author'];
            $imageUrl = $_POST['imageUrl'];
            $timestamp = date('Y-m-d H:i:s');

            $news = new News();
            $news->title = $title;
            $news->content = $content;
            $news->author = $author;
            $news->imageUrl = $imageUrl;
            $news->timestamp = $timestamp;

            $news->save();
            header('Location: /news');
        }
    }

    public function edit($id)
    {
        $news = News::find($id);
        require_once '../app/views/news/edit.php';
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $news = News::find($id);
            $news->title = $_POST['title'];
            $news->content = $_POST['content'];
            $news->author = $_POST['author'];
            $news->imageUrl = $_POST['imageUrl'];
            $news->timestamp = date('Y-m-d H:i:s');

            $news->save();
            header('Location: /news');
        }
    }

    public function delete($id)
    {
        $news = News::find($id);
        $news->delete();
        header('Location: /news');
    }
}