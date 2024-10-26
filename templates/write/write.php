<?php
//protection
if (!isset($_SESSION["user"]["email"])) {
    header('Location: /');
    exit();
}

$content = '';

//message d'ereur
if (isset($message)) {
    if (stristr($message, 'Congrate')) {
        $content .= '
            <div class="d-grid gap-2 messageInfo">
                <button class="btn btn-success" type="button">' . $message . '</button>
            </div>
        ';
    }
    if (stristr($message, 'Sorry')) {
        $content .= '
            <div class="d-grid gap-2 messageInfo">
                <button class="btn btn-danger" type="button">' . $message . '</button>
            </div>
        ';
    }
}

$content .= '<h3>' . $name . ' <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
  <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
</svg></h3>';
$content .= '<div class="row">';
$content .= '
<div class="writeArticle">
    <form action="save_article" method="POST" id="writeArticleForm" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Title" required>
        </div>
        <div class="mb-3">
            <label for="floatingTextarea2">Article</label>
            <textarea class="form-control" placeholder="Write your idea here..." id="floatingTextarea2" name="content" style="height: 250px"></textarea>
        </div>
        <div class="mb-3">
        <label for="image" class="form-label">Add image (.png, .jpg, .jpeg)</label>
            <input type="file" name="img"  />
        </div>
        <input type="hidden" name="email" value="' . $_SESSION["user"]["email"] . '" />
        <div class="d-flex gap-2 col-6 mx-auto">
            <button class="btn btn-success flex-grow-1" type="submit">Create</button>
        </div>
    </form>
</div>';
$content .= '</div>';
//require base.php file
require_once(dirname(__DIR__) . "/base.php");
