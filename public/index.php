<?php
session_start();

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use Slim\Views\PhpRenderer;
use Slim\Factory\AppFactory;

require_once dirname(__DIR__) . "/bootstrap.php";

// Create App
$app = AppFactory::create();

//Add route : home
$app->get('/', function ($request, $response) {
    $renderer = new PhpRenderer(dirname(__DIR__) . '/templates');

    //charger les articles
    $articlesRepo = $GLOBALS["em"]->getRepository(Article::class);
    $articles = $articlesRepo->findAll();

    $viewData = [
        'name' => 'Home Page',
        'articles' => $articles,
    ];

    return $renderer->render($response, '/home/home.php', $viewData);
})->setName('profile');

//Add route : write article
$app->get('/write_article', function ($request, $response) {
    $renderer = new PhpRenderer(dirname(__DIR__) . '/templates');

    $viewData = [
        'name' => 'Write Your Idea',
    ];

    return $renderer->render($response, '/write/write.php', $viewData);
})->setName('profile');

//Add route : save article
$app->post('/save_article', function ($request, $response, $args) {
    $renderer = new PhpRenderer(dirname(__DIR__) . '/templates');

    //clean data
    $requestData = $request->getParsedBody();
    foreach ($requestData as $key => $value) {
        $data_t = trim($value);
        $data_t = stripslashes($data_t);
        $data_t = strip_tags($data_t);
        $data_t = htmlspecialchars($data_t);
        $requestData[$key] = $data_t;
    }

    //find user and continu 
    $userRepo = $GLOBALS["em"]->getRepository(User::class);
    $user = $userRepo->findBy(array('email' => $requestData["email"]));

    if (count($user) > 0) {

        //->renaming
        $new_name = "";
        $code = "az12345678MWXC9ertyuiUIOPQSDFGHJopqsdfgh123456789jklmwxcvbn123456789AZERTYKLVBN";

        $index = 1;
        while ($index <= 20) {
            $new_name .= $code[rand(0, 78)];
            $index++;
        }


        //get file
        $files = $request->getUploadedFiles();
        //file not exist
        if ($files["img"]->getSize() == 0) {
            $new_name = 'foo.png';
        }
        //file exist
        if (!$files["img"]->getSize() == 0) {
            $newfile = $files['img'];
            if ($newfile->getError() === UPLOAD_ERR_OK) {
                $uploadFileName = $newfile->getClientFilename();
                $table_explode = explode('.', $uploadFileName);
                $extension = $table_explode[count($table_explode) - 1];

                //check file extention
                if (in_array($extension, ["png", "jpg", 'jpeg'])) {
                    $newfile->moveTo(__DIR__ . "/assets/img/" . $new_name . "." . $extension);
                } else {
                    $new_name = 'foo.png';
                    $extension = null;
                }
            }
        }


        //path to image
        // $root = $_SERVER["HTTP_HOST"];
        $root = "/assets/img/";
        $path_to_img = $root . $new_name;
        $path_to_img = isset($extension) ? $path_to_img . "." . $extension : $path_to_img;

        //sauvegarde des infos
        $newArticle = (new Article())->setTitle($requestData["title"])
            ->setContent($requestData["content"])
            ->setImgUrl($path_to_img)
            ->setAuthor($user[0]);

        $GLOBALS["em"]->persist($newArticle);
        $GLOBALS["em"]->flush();

        $message = "Congrate, you post your idea !";
    } else {
        $user[0] = NULL;
        $message = "Sorry, an error occur !";
    }

    $viewData = [
        'name' => 'Write Your Idea',
        "user" => $user[0],
        "message" => $message
    ];

    return $renderer->render($response, '/write/write.php', $viewData);
})->setName('profile');

//Add route : read article
$app->get('/details/{slug}', function ($request, $response) {
    $renderer = new PhpRenderer(dirname(__DIR__) . '/templates');

    //charger larticle grace à son slug
    $slug = $request->getAttribute('slug');
    $articlesRepo = $GLOBALS["em"]->getRepository(Article::class);
    $article = $articlesRepo->findOneBySlug($slug);

    $viewData = [
        'name' => 'Reading...',
        'article' => $article,
    ];

    return $renderer->render($response, '/read/read.php', $viewData);
})->setName('profile');

//Add route : comment article : post
$app->post('/comment_article/{slug}', function ($request, $response, $args) {
    $renderer = new PhpRenderer(dirname(__DIR__) . '/templates');

    //clean data
    $requestData = $request->getParsedBody();
    foreach ($requestData as $key => $value) {
        $data_t = trim($value);
        $data_t = stripslashes($data_t);
        $data_t = strip_tags($data_t);
        $data_t = htmlspecialchars($data_t);
        $requestData[$key] = $data_t;
    }


    //find user and continu 
    $userRepo = $GLOBALS["em"]->getRepository(User::class);
    $user = $userRepo->findBy(array('email' => $requestData["email"]));;
    if (count($user) > 0) {
        //charger larticle grace à son slug
        $slug = $request->getAttribute('slug');
        $articlesRepo = $GLOBALS["em"]->getRepository(Article::class);
        $article = $articlesRepo->findOneBySlug($slug);

        //ajout du commentaires
        if (gettype($article) === "object") {

            $createComment = (new Comment())->setCommentContent($requestData["content"])
                ->setAuthor($user[0])
                ->setArticleCommented($article);

            $GLOBALS["em"]->persist($createComment);
            $GLOBALS["em"]->flush();
        }
    }


    $viewData = [
        'name' => 'Reading...',
        'article' => $article
    ];

    return $renderer->render($response, '/read/read.php', $viewData);
})->setName('profile');

//Add route : cdelete article
$app->get('/delete/{slug}', function ($request, $response) {
    $renderer = new PhpRenderer(dirname(__DIR__) . '/templates');

    //charger l'article grace à son slug
    $slug = $request->getAttribute('slug');
    $articlesRepo = $GLOBALS["em"]->getRepository(Article::class);
    $article = $articlesRepo->findOneBySlug($slug);

    //remove articles's comment before article itself
    $comments = $article->getCommentsOfArticle()->getValues();
    foreach ($comments as $comment) {
        $GLOBALS["em"]->remove($comment);
        $GLOBALS["em"]->flush();
    }

    $GLOBALS["em"]->remove($article);
    $GLOBALS["em"]->flush();

    //charger les articles
    $articlesRepo = $GLOBALS["em"]->getRepository(Article::class);
    $articles = $articlesRepo->findAll();

    $viewData = [
        'name' => 'Home Page',
        'articles' => $articles,
    ];

    return $renderer->render($response, '/my_articles/my_articles.php', $viewData);
})->setName('profile');

//Add route : my_article
$app->get('/my_articles', function ($request, $response) {
    $renderer = new PhpRenderer(dirname(__DIR__) . '/templates');

    //charger les articles
    $articlesRepo = $GLOBALS["em"]->getRepository(Article::class);
    $articles = $articlesRepo->findAll();

    $viewData = [
        'name' => 'My Articles',
        'articles' => $articles,
    ];

    return $renderer->render($response, '/my_articles/my_articles.php', $viewData);
})->setName('profile');

//add route : logout : get
$app->get('/logout', function ($request, $response) {
    $renderer = new PhpRenderer(dirname(__DIR__) . '/templates');

    $viewData = [
        "name" => "Logout Page"
    ];

    return $renderer->render($response, '/logout/logout.php', $viewData);
})->setName('profile');

//add route : log in : se connecter : post
$app->post('/login', function ($request, $response) {
    $renderer = new PhpRenderer(dirname(__DIR__) . '/templates');
    $userParams = $request->getParsedBody();

    $email = $userParams["email"];
    $pwd = $userParams["pwd"];

    //charger user
    $userRepo = $GLOBALS["em"]->getRepository(User::class);
    $user = $userRepo->findBy(array('email' => $email, 'password' => sha1($pwd)));

    //si pas d'utilisateur trouvé
    if (count($user) == 0) {
        $user[0] = NULL;
    }

    //charger les articles
    $articlesRepo = $GLOBALS["em"]->getRepository(Article::class);
    $articles = $articlesRepo->findAll();

    $viewData = [
        'name' => 'Home Page',
        "articles" => $articles,
        "user" => $user[0]
    ];

    return $renderer->render($response, '/home/home.php', $viewData);
})->setName('profile');

//Add routes : create_account : post
$app->post('/create_account', function ($request, $response) {
    $renderer = new PhpRenderer(dirname(__DIR__) . '/templates');
    $requestData = $request->getParsedBody();

    //clean data
    foreach ($requestData as $key => $value) {
        $data_t = trim($value);
        $data_t = stripslashes($data_t);
        $data_t = strip_tags($data_t);
        $data_t = htmlspecialchars($data_t);
        $requestData[$key] = $data_t;
    }

    //verifiy if email exist
    $user = $GLOBALS["em"]->getRepository(User::class)->findBy(array('email' => $requestData["email"]));

    if (count($user) > 0) {
        //user already exist
        $user[0] = 1;
    } else {
        //create user
        $createUser = (new User())->setFullname($requestData["fullname"])
            ->setEmail($requestData["email"])
            ->setPassword(sha1($requestData["password"]));

        $GLOBALS["em"]->persist($createUser);
        $GLOBALS["em"]->flush();

        $user = $GLOBALS["em"]->getRepository(User::class)->findBy(array('email' => $requestData["email"], 'password' => sha1($requestData["password"])));

        //if error when uploading user creted data
        if (count($user) == 0) {
            $user[0] = 2;
        }
    }

    //charger les articles
    $articlesRepo = $GLOBALS["em"]->getRepository(Article::class);
    $articles = $articlesRepo->findAll();

    $viewData = [
        'name' => 'Home Page',
        "articles" => $articles,
        "user" => $user[0]
    ];

    return $renderer->render($response, '/home/home.php', $viewData);
})->setName('profile');


$app->run();
