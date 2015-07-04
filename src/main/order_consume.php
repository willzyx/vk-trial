<?php function reportSuccess($performInfo)
{
    ?>
    <div class="alert alert-success" role="alert">
        Order has been consumed!
        Your profit <?php echo $performInfo->getPerformProfit(); ?> nail(s)
    </div>
<?php } ?>

<?php function reportError($msg)
{
    ?>
    <div class="alert alert-danger" role="alert">
        Can't consume order. <?php echo $msg; ?>
    </div>
<?php }

function reportDBError() {
    reportError("No access to database");
}
?>

<?php

$CONSUME_PENALTY = 0.5;

require 'auth.php';
require 'proto_model.php';
require 'db_vk.php';

$authInfo = getAuthInfo();

if (!$authInfo) {
    http_response_code(401);
    return;
}

if (!isset($_GET["order-id"])) return;
$order_id = $_GET["order-id"];
if (!ctype_alnum($order_id)) return;

$db = openDB();
if (!$db) {
    reportDBError();
    return;
}

$sqlSelect = $db->prepare("SELECT data_order FROM t_orders WHERE order_id = ?");
$sqlSelect->bind_param("s", $order_id);
if ($sqlSelect->execute()) {
    $sqlSelect->bind_result($data_order);
    if ($sqlSelect->fetch()) {
        $order = new OrderInfo();
        $order->parseFromString($data_order);
    }
}
$sqlSelect->close();

$performInfo = new OrderPerformInfo();
$performInfo ->setUserId($authInfo->login);
$performInfo ->setTimestamp(time());
$performInfo ->setPerformProfit($order->getPerformCost() * $CONSUME_PENALTY);

$sqlUpdate = $db ->prepare(
    "UPDATE t_orders SET " .
    "data_perform = ?, " .
    "perform_uid = ? " .
    "WHERE order_id = ? AND data_perform IS NULL"
);
if (!$sqlUpdate) reportDBError();
else {
    $sqlUpdate ->bind_param(
        "sss",
        $performInfo ->serializeToString(),
        $performInfo ->getUserId(),
        $order_id
    );
    if ($sqlUpdate->execute()) {
        if ($sqlUpdate->affected_rows > 0) {
            reportSuccess($performInfo);
        } else {
            reportError("It's already been consumed!");
        }
    } else reportDBError();
    $sqlUpdate ->close();
}