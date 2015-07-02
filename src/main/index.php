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
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
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

        <?php function writeUserPage($login, $role) { ?>
            <h2>Hello <?php echo $login ?>!</h2>
        <?php } ?>

        <?php
        function checkRole($value)
        {
            return $value == "creator" || $value == "consumer";
        }

        function getLine($filename)
        {
            $file = fopen($filename, "r") or die("no $filename file");
            $line = fgets($file);
            fclose($file);
            return $line;
        }

        function getSign($login, $role)
        {
            $salt = getLine("../props/prop_salt");
            return md5("$login.$role.$salt");
        }

        $valueLogin = NULL;
        $valueRole = NULL;
        $valueSession = NULL;
        if (isset($_POST["inputLogin"])) {
            $valueLogin = $_POST["inputLogin"];
        }
        if (isset($_POST["inputRole"])) {
            $valueRole = $_POST["inputRole"];
        }
        if (isset($_COOKIE["session"])) {
            $valueSession = $_COOKIE["session"];
        }
        if (!$valueLogin && !$valueRole && $valueSession) {
            list($v1, $v2, $sign) = explode(".", $valueSession);
            if ($sign == getSign($v1, $v2)) {
                $valueLogin = $v1;
                $valueRole = $v2;
            }
        }
        if (!$valueLogin || !$valueRole || !ctype_alnum($valueLogin) || !checkRole($valueRole)) writeLoginForm();
        else {
            if (!$valueSession) {
                $sign = getSign($valueLogin, $valueRole);
                setcookie("session", "$valueLogin.$valueRole.$sign");
            }
            writeUserPage($valueLogin, $valueRole);
        }
        ?>
    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="common.js"></script>
</body>
</html>

