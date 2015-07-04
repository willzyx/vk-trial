<?php

require_once 'proto_model.php';

function reportListing($db, $authInfo)
{
    $sql = $db ->prepare(
        "SELECT data_order
          FROM t_orders
          WHERE data_perform IS NULL
          ORDER BY order_id"
    );
    if ($sql) {
        if ($sql ->execute()) {

            ?>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Description</th>
                    <th>Price</th>
                    <?php if ($authInfo ->isConsumer()) { ?>
                        <th></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php

                $sql ->bind_result($data_order);
                while ($sql ->fetch()) {
                    $orderInfo = new OrderInfo();
                    $orderInfo ->parseFromString($data_order);
                    ?>

                    <tr id="order_<?php echo $orderInfo->getId(); ?>">
                        <td><?php echo $orderInfo->getDescription(); ?> </td>
                        <td>
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

                ?>
                </tbody>
            </table>
        <?php
        }
        $sql ->close();
    }

}