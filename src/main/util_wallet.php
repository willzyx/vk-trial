<?php function writeWallet($wallet)
{
    ?>

    <div id="div-wallet" class="bs-callout bs-callout-success">
        <h4>Your Wallet</h4>
        Current assets: <strong> <?php echo $wallet ->getQuantityOfMoney(); ?> nail(s) </strong>
    </div>

<?php } ?>

<?php

require 'proto_model.php';

function doArrangeOrders($db, $user)
{
    $sql = $db ->prepare(
        "SET @i = (SELECT IFNULL(MAX(perform_order), 0) FROM t_orders WHERE perform_uid = ?)"
    );
    if (!$sql) return false;
    if (!$sql ->bind_param("s", $user)) return false;
    if (!$sql ->execute()) return false;
    $sql = $db ->prepare(
        "UPDATE t_orders
          SET perform_order = (@i:=@i+1)
          WHERE perform_uid = ? AND perform_order IS NULL"
    );
    if (!$sql) return false;
    if (!$sql ->bind_param("s", $user)) return false;
    if (!$sql ->execute()) return false;
    return true;
}

function getWallet($db, $user)
{
    $wallet = null;
    $sql = $db ->prepare("SELECT data_wallet FROM t_users WHERE uid = ?");
    $sql ->bind_param("s", $user);
    if ($sql ->execute()) {
        $sql ->bind_result($dataWallet);
        if ($sql->fetch()) {
            $wallet = new UserWalletInfo();
            $wallet ->parseFromString($dataWallet);
        }
    }
    $sql ->close();
    if (!$wallet) {
        $wallet = new UserWalletInfo();
        $wallet ->setQuantityOfMoney(0);
        $wallet ->setPerformOrder(0);
    }
    return $wallet;
}

function putWallet($db, $user, $wallet)
{
    $sql = $db ->prepare(
        "INSERT INTO t_users (uid, data_wallet) VALUES (?, ?)
           ON DUPLICATE KEY UPDATE data_wallet = ?"
    );
    if ($sql) {
        $data_wallet = $wallet ->serializeToString();
        if ($sql ->bind_param("sss", $user, $data_wallet, $data_wallet)) {
            $sql ->execute();
            $sql ->close();
        }
    }
}

function refreshWallet($db, $user)
{
    doArrangeOrders($db, $user);
    $wallet = getWallet($db, $user);
    $valuePerformOrder = $wallet ->getPerformOrder();
    $valueMoney = $wallet ->getQuantityOfMoney();
    $sql = $db ->prepare("SELECT data_perform, perform_order FROM t_orders WHERE perform_uid = ? AND perform_order > ?");
    $sql ->bind_param("si", $user, $valuePerformOrder);
    if ($sql ->execute()) {
        $sql ->bind_result($data_perform, $perform_order);
        while ($sql ->fetch()) {
            if ($valuePerformOrder < $perform_order) {
                $valuePerformOrder = $perform_order;
            }
            $infoPerform = new OrderPerformInfo();
            $infoPerform ->parseFromString($data_perform);
            $valueMoney += $infoPerform ->getPerformProfit();
        }
        if ($sql ->close()) {
            $wallet ->setPerformOrder($valuePerformOrder);
            $wallet ->setQuantityOfMoney($valueMoney);
            putWallet($db, $user, $wallet);
        }
    }
    return $wallet;
}

function reportWallet($db, $user)
{
    $wallet = refreshWallet($db, $user);
    if ($wallet) writeWallet($wallet);
}