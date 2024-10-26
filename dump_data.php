<?php

use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;

//fichier neccessaire : chermin vers bootstrap.php 
require_once "bootstrap.php";

//fichier neccessaire : chermin vers fichier entity.php 
require_once "src/Entity/User.php";
require_once "src/Entity/Comment.php";
require_once "src/Entity/Article.php";

// Faker\Generator instance
$faker = Factory::create();



/* creer des fake 50 fake users*/
//create user
$userNum = 10;
$password = "azerty123";

for ($i = 0; $i < $userNum; $i++) {
    $createUser = (new User())->setFullname($faker->name())
        ->setEmail($faker->email())
        ->setPassword(sha1($password));

    $em->persist($createUser);
    $users[] = $createUser;
}
$em->flush();
/**/

/* creer des fake 100 fake articles*/
//create article
$articleNum = 50;

for ($i = 0; $i < $articleNum; $i++) {
    $createArticle = (new Article())->setTitle($faker->text(rand(50, 100)))
        ->setContent($faker->text(rand(2000, 3000)))
        ->setImgUrl($faker->imageUrl(360, 360, 'animals', true, 'cats'))
        ->setAuthor($users[rand(0, $userNum - 1)]);

    $em->persist($createArticle);
    $articles[] = $createArticle;
}
$em->flush();

/**/

/* creer des fake 500 fake comments*/
//create comment
$commentNum = 500;

for ($i = 0; $i < $commentNum; $i++) {
    $createComment = (new Comment())->setCommentContent($faker->text(rand(300, 1000)))
        ->setAuthor($users[rand(0, $userNum - 1)])
        ->setArticleCommented($articles[rand(0, $articleNum - 1)])
        ->setArticleCommented($articles[rand(0, $articleNum - 1)])
        ->setArticleCommented($articles[rand(0, $articleNum - 1)])
        ->setArticleCommented($articles[rand(0, $articleNum - 1)])
        ->setArticleCommented($articles[rand(0, $articleNum - 1)]);

    $em->persist($createComment);
    $comment[] = $createComment;
}
$em->flush();
/**/