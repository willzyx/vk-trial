function consumeOrder(id) {
    $.ajax("order_consume.php?order-id=" + id).done(
        function () {
            $("#order_" + id).remove();
            updateWallet();
        }
    ).always(
        function (html) {
            $("#div-notify").html(html);
        }
    );
}

function updateWallet() {
    $.ajax("user_wallet.php").done(
        function (html) {
            $("#div-wallet").html(html);
        }
    )
}