<?php

require 'auth.php';
require 'db_vk.php';
require 'util_listing.php';

$authInfo = getAuthInfo();

if (!$authInfo) {
    http_response_code(401);
    return;
}

reportListing(openDB(), $authInfo);