<!-- Header  -->
<?php include("layouts/header.php"); ?>

<?php
if (isset($_POST['order_pay_btn'])) {
    $order_status = $_POST['order_status'];
    $order_total_price = $_POST['order_total_price'];
}

?>


    <!-- Payment -->
    <section class="payment my-5 py-5">
        <div class="container  mt-3 pt-5">
            <h2 class="form-weight-bold">Payment</h2>
            <hr>
        </div>
        <div class="mx-auto container ">


            <?php if(isset($_POST['order_status']) && $_POST['order_status'] == 'not paid') { ?>
                <?php $amount = strval($_POST['order_total_price']); ?>
                <?php $order_id = $_POST['order_id']; ?>

                <p>Total payment : $<?php
                                    echo $_POST['order_total_price'];
                                    ?></p>
                <!-- <input type="submit" class="btn pay-now-btn" value="Pay Now" /> -->
                <!-- Set up a container element for the button is clicked -->
                <div id="paypal-button-container"></div>
                
            <?php } else if (isset($_SESSION['total']) && $_SESSION['total'] != 0) { ?>
                <?php $amount = strval($_SESSION['total']); ?>
                <?php $order_id = $_SESSION['order_id']; ?>

                <p>Total payment : $<?php
                                    echo $_SESSION['total'];
                                    ?></p>
                <!-- <input type="submit" class="btn pay-now-btn" value="Pay Now" /> -->
                <!-- Set up a container element for the button is clicked -->
                <div id="paypal-button-container"></div>




            <?php } else { ?>
                <p>You don't have an order</p>
            <?php } ?>



        </div>
    </section>



    <!-- Copy from pay pal -->


    <script src="https://www.paypal.com/sdk/js?client-id=AUylHglyg2rbAX5jEs0XXwhxuDDcX8ozt5VDKhFWFnXxdPGaz8lxfBKamrWi4-c7bE6SMSXchqEc34Ne&currency=USD"></script>


    <!-- Replace the "test" client-id value with your client-id -->

    <script>
        paypal.Buttons({
            // Call your server to set up the transaction
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo $amount ?>'
                        }
                    }]
                });
            },
            // Authorize or capture the transaction after payer approves
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(orderData) {
                    console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                    var transaction = orderData.purchase_units[0].payments.captures[0];
                    alert('Transaction' + transaction.status + ': ' + transaction.id + '\n\n See console for all available details.');

                    window.location.href = "server/complete_payment.php?transaction_id="+transaction.id+"&order_id="+<?php echo $order_id;?>;


                });
            }
        }).render('#paypal-button-container');
    </script>


    <!-- Footer -->
    <?php include("layouts/footer.php") ?>

   