<?php

function getFileLine($filename)
{
    $file = fopen($filename, "r") or die("no $filename file");
    $line = fgets($file);
    fclose($file);
    return $line;
}

function getAuthSignature($login, $role)
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

    function isCorrect()
    {
        if (is_null($this->login)) return false;
        if (strlen($this ->login) > 25) return false;
        if (strlen($this ->login) < 1) return false;
        if (!ctype_alnum($this->login)) return false;
        if (!$this ->isCreator() && !$this ->isConsumer()) return false;
        return true;
    }

    function saveSignedCookie()
    {
        $sign = getAuthSignature($this->login, $this->role);
        setcookie("session", "$this->login.$this->role.$sign");
    }
}

function parseAuthSession($session)
{
    list($v1, $v2, $v3) = explode(".", $session);
    if ($v3 == getAuthSignature($v1, $v2)) {
        $authInfo = new AuthInfo();
        $authInfo ->login = $v1;
        $authInfo ->role = $v2;
        if ($authInfo->isCorrect()) return $authInfo;
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
    if ($info->isCorrect()) return $info;
    else return null;
}