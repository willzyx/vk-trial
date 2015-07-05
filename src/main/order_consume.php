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
<?php
}

function reportDBError()
{
    reportError("No access to database");
}

?>

<?php

$CONSUME_PENALTY = 0.5; // TODO: this should be stored in database

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

$db = openDB();
if (!$db) reportDBError();
else {
    $orderInfo = null;

    $sql = $db->prepare("SELECT data_order FROM t_orders WHERE order_id = ?");
    if ($sql) {
        if (
            $sql->bind_param("s", $order_id) &&
            $sql->execute() &&
            $sql->bind_result($data_order) &&
            $sql->fetch()
        ) {
            $orderInfo = new OrderInfo();
            $orderInfo->parseFromString($data_order);
        }
        $sql->close();
    }

    if (!$orderInfo) {
        reportDBError();
        return;
    }

    $performInfo = new OrderPerformInfo();
    $performInfo ->setUserId($authInfo->login);
    $performInfo ->setTimestamp(time());
    $performInfo ->setPerformProfit($orderInfo->getPerformCost() * $CONSUME_PENALTY);

    $fl = false;
    $sql = $db ->prepare(
        "UPDATE t_orders
          SET data_perform = ?, perform_uid = ?
          WHERE order_id = ? AND data_perform IS NULL"
    );
    if ($sql) {
        $fl = $sql ->bind_param("sss",
            $performInfo ->serializeToString(),
            $performInfo ->getUserId(),
            $order_id
        );
        $fl = $fl && $sql->execute();
        if ($fl) {
            if ($sql->affected_rows > 0) {
                reportSuccess($performInfo);
            } else {
                reportError("It's already been consumed!");
            }
        }
        $sql ->close();
    }
    if (!$fl) reportDBError();
}