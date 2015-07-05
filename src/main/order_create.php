<?php function reportError($msg)
{
    ?>
    <div class="alert alert-danger" role="alert">
        Can't create order. <?php echo $msg; ?>
    </div>
<?php } ?>

<?php function reportSuccess()
{
    ?>
    <div class="alert alert-success" role="alert">Order has been successfully created!</div>
    <script language="JavaScript">eventOrderPublished();</script>
<?php } ?>

<?php

require 'auth.php';
require 'proto_model.php';
require 'db_vk.php';

$authInfo = getAuthInfo();

if (!$authInfo) {
    http_response_code(401);
    return;
}

$valueDesc = NULL;
$valuePrice = NULL;
if (isset($_POST["inputDesc"])) {
    $valueDesc = $_POST["inputDesc"];
}
if (isset($_POST["inputPrice"])) {
    $valuePrice = floatval($_POST["inputPrice"]);
}

if (is_null($valueDesc) || strlen($valueDesc) < 30) reportError("Description should contain at least 30 symbols");
else if (is_null($valuePrice) || $valuePrice <= 0) reportError("Correct price should be specified");
else {
    $timestamp = time();
    $item = new OrderInfo();
    $item->setId(sprintf('%016x', $timestamp) . "T" . sprintf('%012x', rand()));
    $item->setTimestamp($timestamp);
    $item->setUserId($authInfo->login);
    $item->setDescription($valueDesc);
    $item->setPerformCost($valuePrice);
    $fl = false;
    $db = openDB();
    if ($db) {
        $sql = $db ->prepare("INSERT INTO t_orders (order_id, data_order) VALUES (?, ?)");
        if ($sql) {
            $fl = $sql ->bind_param("ss", $item->getId(), $item->serializeToString()) && $sql ->execute();
            $fl = $sql ->close() && $fl;
        }
    }
    if ($fl) reportSuccess();
    else reportError("There is a problem with database");
}