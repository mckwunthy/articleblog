<nav class="navbar navbar-expand-lg bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand">WriteTruth <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                <path
                    d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
            </svg></a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                </li>
                <?php
                if (isset($_SESSION["user"])) {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/write_article">WriteArticle</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/my_articles">MyArticles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Se deconnecter</a>
                    </li>
                    <div class="username">
                        <?php echo "Welcome " . strtoupper($_SESSION["user"]["fullname"]) . "</em>"
                        ?>
                    </div>
                <?php }
                if (!isset($_SESSION["user"])) {
                ?>
                    <div class="loginForm">
                        <form action="/login" method="POST">
                            <input type="email" name="email" class="usermail" placeholder="email" required>
                            <input type="password" name="pwd" class="userpwd" placeholder="password" required>
                            <input type="submit" value="log In">
                        </form>
                    </div>
                    <div class="SingupForm">
                        <button class="btn-warning" id="createAccountBt">Create Account</button>
                    </div>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>

<!--create account form-->

<div class="createAccountBox d-none">
    <form action="create_account" method="POST" id="createAccountForm">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="fullname" class="form-label">fullname</label>
            <input type="text" class="form-control" id="fullname" name="fullname" required>
        </div>
        <div class="d-flex gap-2 col-6 mx-auto">
            <button class="btn btn-warning flex-grow-1" type="submit">Create</button>
            <button class="btn btn-danger flex-grow-3 closeBt" type="button">close</button>
        </div>
    </form>
</div>