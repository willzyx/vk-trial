<?php

function getFileLine($filename)
{
    $file = fopen($filename, "r") or die("no $filename file");
    $line = fgets($file);
    fclose($file);
    return $line;
}

function getAuthSign($login, $role)
{
    $salt = getFileLine("../props/prop_salt");
    return md5("$login.$role.$salt");
}

class AuthInfo
{
    public $login;
    public $role;

    public function __toString()
    {
        return "AuthInfo [$this->login, $this->role]";
    }

    public function isCreator()
    {
        return $this ->role == "creator";
    }

    public function isConsumer()
    {
        return $this ->role == "consumer";
    }

    function is_correct()
    {
        $f1 = $this->login && ctype_alnum($this->login);
        $f2 = $this ->isCreator() || $this ->isConsumer();
        return $f1 && $f2;
    }

    function save_signed_cookie()
    {
        $sign = getAuthSign($this->login, $this->role);
        setcookie("session", "$this->login.$this->role.$sign");
    }
}

function parseAuthSession($session)
{
    list($v1, $v2, $sign) = explode(".", $session);
    if ($sign == getAuthSign($v1, $v2)) {
        $authInfo = new AuthInfo();
        $authInfo->login = $v1;
        $authInfo->role = $v2;
        if ($authInfo->is_correct()) return $authInfo;
    }
    return NULL;
}

function getAuthInfo()
{
    if (!isset($_COOKIE["session"])) return null;
    else return parseAuthSession($_COOKIE["session"]);
}

function createAuthInfo()
{
    if (!isset($_POST["inputLogin"])) return null;
    if (!isset($_POST["inputRole"])) return null;
    $info = new AuthInfo();
    $info->login = $_POST["inputLogin"];
    $info->role = $_POST["inputRole"];
    if (!$info->is_correct()) return null;
    return $info;
}