<?php

require_once 'proto_model.php';

function reportListing($db, $authInfo)
{
    $sql = $db ->prepare(
        "SELECT data_order
          FROM t_orders
          WHERE data_perform IS NULL
          ORDER BY order_id DESC"
    );
    if ($sql) {
        if ($sql ->execute()) {

            function writeTableHead($authInfo)
            {
                ?>
                <table class="table table-striped table-listing">
                <thead>
                <tr>
                    <th class="td-desc">Description</th>
                    <th class="td-price">Price</th>
                    <?php if ($authInfo ->isConsumer()) { ?>
                        <th></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
            <?php
            }

            function writeTableTail()
            {
                ?>
                </tbody>
                </table>
            <?php
            }

            function writeEmptyTable()
            {
                ?>
                <div class="alert alert-info" role="alert">No orders found</div>
            <?php
            }

            function writeTableRow($authInfo, $orderInfo)
            {
                ?>

                <tr id="order_<?php echo $orderInfo->getId(); ?>">
                    <td class="td-desc"><?php echo $orderInfo->getDescription(); ?> </td>
                    <td class="td-price">
                        <?php echo $orderInfo->getPerformCost(); ?> nail(s)
                    </td>
                    <?php if ($authInfo ->isConsumer()) { ?>
                        <td>
                            <button type="button" class="btn btn-primary"
                                    onclick="consumeOrder('<?php echo $orderInfo->getId(); ?>')">
                                Buy it!
                            </button>
                        </td>
                    <?php } ?>
                </tr>
            <?php
            }

            $firstLine = true;
            $sql ->bind_result($data_order);
            while ($sql ->fetch()) {
                if ($firstLine) {
                    writeTableHead($authInfo);
                    $firstLine = false;
                }
                $orderInfo = new OrderInfo();
                $orderInfo ->parseFromString($data_order);
                writeTableRow($authInfo, $orderInfo);
            }
            if ($firstLine) writeEmptyTable();
            else writeTableTail();
        }
        $sql ->close();
    }

}