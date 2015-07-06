<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>VK Trial. This is a willzyx's property</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="common.css" rel="stylesheet">
</head>

<?php
require 'auth.php';
require 'util_wallet.php';
require 'util_listing.php';
require 'db_vk.php';

$authInfo = NULL;
if (isset($_POST["inputSignOut"])) {
    setcookie("session", NULL);
} else {
    if (!$authInfo) $authInfo = getAuthInfo();
    if (!$authInfo) {
        $authInfo = createAuthInfo();
        if ($authInfo) $authInfo ->saveSignedCookie();
    }
}
?>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">VK Trial</a>
        </div>
        <?php if ($authInfo) { ?>
            <div id="navbar" class="navbar-collapse collapse">
                <form class="navbar-form navbar-right" method="POST">
                    <input type="hidden" name="inputSignOut" value="yes">
                    <button type="submit" class="btn btn-danger">Sign out</button>
                </form>
            </div>
        <?php } ?>
    </div>
</nav>

<div class="container">

    <div class="starter-template">
        <?php function writeLoginForm()
        {
            ?>
            <div class="container">
                <form class="form-signin" method="POST">
                    <h2 class="form-signin-heading">Please sign in</h2>
                    <label for="inputLogin" class="sr-only">Login</label>
                    <input type="text" name="inputLogin"
                           id="inputLogin" class="form-control"
                           maxlength="25"
                           placeholder="Login" required autofocus>

                    <div class="radio">
                        <label><input type="radio" name="inputRole" value="creator" checked>Creator</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="inputRole" value="consumer">Consumer</label>
                    </div>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                </form>
            </div>
        <?php } ?>

        <?php function writeUserPage($authInfo)
        {
            $db = openDB();
            ?> <h2>Hello <?php echo $authInfo->login ?>!</h2> <?php

            if ($authInfo ->isConsumer()) {
                ?>
                <div id="div-wallet">
                    <?php reportWallet($db, $authInfo ->login); ?>
                </div>
            <?php
            }
            if ($authInfo ->isCreator()) {
                ?>

                <form method="POST" id="form-create-order" class="form-create-order">
                    <div class="form-group">
                        <label for="inputCODesc">Description</label>
                        <textarea id="inputCODesc" class="form-control"
                                  name="inputDesc"
                                  maxlength="3000"
                                  rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="inputCOPrice">Price (in nails)</label>

                        <div class="input-group">
                            <div class="input-group-addon">N</div>
                            <input type="text"
                                   id="inputCOPrice" class="form-control"
                                   name="inputPrice"
                                   onkeydown="if (event.keyCode == 13) return false;"
                                   maxlength="7"
                                   placeholder="Price">

                            <div class="input-group-addon">.00</div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="publishOrder()">Publish</button>
                </form>

            <?php
            }

            ?>
            <div id="div-notify"></div>
            <div id="div-listing">
                <?php reportListing($db, $authInfo); ?>
            </div>

        <?php } ?>

        <?php
        if ($authInfo) writeUserPage($authInfo);
        else writeLoginForm();
        ?>
    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="common.js"></script>
</body>
</html>

