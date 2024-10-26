<?php
if ($article) {

    $content = '<h3>' . $name . '</h3>';
    //display article
    $content = '
<div class="single_event">
    <div class="img" style="background-image: url(' . $article->getImgUrl() . ')">

    </div>
    <div class="infos">
        <h4 class="title">' . $article->getTitle() . '</h4>
        <p class="created_at">' . $article->getCreated_at() . '</p>
        <p class="promotedBy"><strong>' . $article->getAuthor()->getFullname() . '</strong></p>
        <p class="description">' . $article->getContent() . '</p>
    </div>
</div>';

    //display comments and authors
    $comments = $article->getCommentsOfArticle()->getValues();
    $content .= '
<button type="button" class="btn btn-primary commentQty">
  Comments : <span class="badge text-bg-secondary">' . count($comments) . '</span>
</button>
';
    $content .= '<div class="commentBox">';
    foreach ($comments as $key => $comment) {
        $content .= '<div class="commentItem">
        <h6 class="commentItemAuthor"><span>' . $comment->getAuthor()->getFullname() . '</span> <em style="font-size: 0.8rem">' . $comment->getCreated_at() . '</em></h6>
        <p class="commentItemContent">' . $comment->getCommentContent() . '</p>
    </div>';
    }
    $content .= '</div>';

    //add comment form
    if (isset($_SESSION["user"]["email"])) {
        $content .= '
<div class="row blocOne">
<div class="commentArticle">
    <form action="/comment_article/' . $article->getSlug() . '" method="POST" id="commentArticleForm">
         <div class="mb-3">
            <textarea class="form-control" placeholder="Comment..." id="floatingTextarea2" name="content" style="height: 80px"></textarea>
        </div>
        <input type="hidden" name="email" value="' . $_SESSION["user"]["email"] . '" />
        <div class="d-flex gap-2 col-6 mx-auto">
            <button class="sendCommentBt" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
  <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"/>
</svg></button>
        </div>
    </form>
</div>
</div>';
    }
    $content .= '<div class="row">';
} else {
    $content = '';
}
//require base.php file
require_once(dirname(__DIR__) . "/base.php");
