<?php

require 'auth.php';
require 'db_vk.php';
require 'util_wallet.php';

$authInfo = getAuthInfo();

if (!$authInfo) {
    http_response_code(401);
    return;
}

$db = openDB();
if ($db) {
    $wallet = refreshWallet($db, $authInfo ->login);
    if ($wallet) writeWallet($wallet);
    else http_response_code(500);
} else http_response_code(500);