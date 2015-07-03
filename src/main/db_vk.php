<?php

function openDB()
{
    $ref = NULL;
    $filename = "../props/prop_mysql";
    $file = fopen($filename, "r") or die("no $filename file");
    $valueLogin = fgets($file);
    $valuePassw = fgets($file);
    if ($valueLogin && $valuePassw) {
        $ref = new mysqli("localhost", trim($valueLogin), trim($valuePassw), "db_vk");
    }
    fclose($file);
    return $ref;
}