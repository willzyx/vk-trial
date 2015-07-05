function consumeOrder(id) {
    $.ajax("order_consume.php?order-id=" + id).done(
        function (html) {
            $("#order_" + id).remove();
            updateWallet();
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

function eventOrderPublished() {
    $("#inputCODesc").val("");
    $("#inputCOPrice").val("");
    updateListing();
}

function publishOrder() {
    $.post('order_create.php', $('#form-create-order').serialize()).done(
        function (html) {
            $("#div-notify").html(html);
        }
    );
}

function updateListing() {
    $.ajax("order_listing.php").done(
        function (html) {
            $("#div-listing").html(html);
        }
    )
}