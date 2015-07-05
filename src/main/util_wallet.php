<?php function writeWallet($wallet)
{
    ?>
    <div class="bs-callout bs-callout-success">
        <h4>Your Wallet</h4>
        Current assets: <strong> <?php echo $wallet ->getQuantityOfMoney(); ?> nail(s) </strong>
    </div>

<?php } ?>

<?php

require_once 'proto_model.php';

function doArrangeOrders($db, $user)
{
    $sql = $db ->prepare(
        "SET @i = (SELECT IFNULL(MAX(perform_order), 0) FROM t_orders WHERE perform_uid = ?)"
    );
    $fl = $sql && $sql ->bind_param("s", $user) && $sql ->execute();
    $fl = $sql && $sql ->close() && $fl;
    if (!$fl) return false;
    $sql = $db ->prepare(
        "UPDATE t_orders
          SET perform_order = (@i:=@i+1)
          WHERE perform_uid = ? AND perform_order IS NULL"
    );
    $fl = $sql && $sql ->bind_param("s", $user) && $sql ->execute();
    $fl = $sql && $sql ->close() && $fl;
    if (!$fl) return false;
    return true;
}

function findWallet($db, $user)
{
    $sql = $db ->prepare("SELECT data_wallet FROM t_users WHERE uid = ?");
    if (!$sql) return null;
    $wallet = null;
    if ($sql ->bind_param("s", $user) && $sql ->execute()) {
        $sql ->bind_result($dataWallet);
        $wallet = new UserWalletInfo();
        if ($sql->fetch()) {
            $wallet ->parseFromString($dataWallet);
        } else {
            $wallet ->setQuantityOfMoney(0);
            $wallet ->setPerformOrder(0);
        }
    }
    $sql ->close();
    return $wallet;
}

function saveWallet($db, $user, $wallet)
{
    $sql = $db ->prepare(
        "INSERT INTO t_users (uid, data_wallet) VALUES (?, ?)
           ON DUPLICATE KEY UPDATE data_wallet = ?"
    );
    if ($sql) {
        $data_wallet = $wallet ->serializeToString();
        $sql ->bind_param("sss", $user, $data_wallet, $data_wallet) && $sql ->execute();
        $sql ->close();
    }
}

function refreshWallet($db, $user)
{
    doArrangeOrders($db, $user);
    $wallet = findWallet($db, $user);
    if (!$wallet) return null;
    $valueOrder = $wallet ->getPerformOrder();
    $valueMoney = $wallet ->getQuantityOfMoney();
    $sql = $db ->prepare("SELECT data_perform, perform_order FROM t_orders WHERE perform_uid = ? AND perform_order > ?");
    if ($sql) {
        $fl = $sql ->bind_param("si", $user, $valueOrder) && $sql ->execute();
        if ($fl) {
            $fl = $sql ->bind_result($data_perform, $perform_order);
            if ($fl) {
                while ($sql ->fetch()) {
                    if ($valueOrder < $perform_order) {
                        $valueOrder = $perform_order;
                    }
                    $infoPerform = new OrderPerformInfo();
                    $infoPerform ->parseFromString($data_perform);
                    $valueMoney += $infoPerform ->getPerformProfit();
                }
            }
        }
        if ($sql ->close() && $fl) {
            $wallet ->setPerformOrder($valueOrder);
            $wallet ->setQuantityOfMoney($valueMoney);
            saveWallet($db, $user, $wallet);
        }
    }
    return $wallet;
}

function reportWallet($db, $user)
{
    $wallet = refreshWallet($db, $user);
    if ($wallet) writeWallet($wallet);
}