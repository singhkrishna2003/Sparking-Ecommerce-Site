<?php include("header.php") ?>

<?php
if (isset($_GET['order_id'])) {

    $order_id = $_GET['order_id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id =?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order =  $stmt->get_result(); // [] Array of products 

} else if (isset($_POST['edit_order'])) {

    $order_status = $_POST['order_status'];
    $order_id = $_POST['order_id'];

    $stmt = $conn->prepare("UPDATE orders SET order_status=? WHERE order_id=?");
    $stmt->bind_param("si", $order_status, $order_id);

    if ($stmt->execute()) {
        header("location: index.php?order_updated=Order has been updated successfully!");
    } else {
        header("location: index.php?order_failed=Error occured, try again !");
    }
} else {
    header("location: index.php");
    exit;
}
?>

<div class="container-fluid">
    <div class="row" style="min-height:1000px">

        <?php include("sidemenu.php") ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">

                    </div>
                </div>
            </div>

            <h2>Edit Order</h2>
            <div class="table-responsive">
                <form id="edit-order-form" method="POST" action="edit_order.php">

                    <?php foreach ($order as $r) { ?>

                        <div class="form-group mt-3">
                            <label for="order_id">OrderId</label>
                            <input type="text" class="form-control" id="order_id" value="<?php echo $r['order_id']; ?>" name="order_id" placeholder="Price" readonly />
                        </div>
                        <div class="form-group mt-3">
                            <label for="order_cost">OrderPrice</label>
                            <input type="text" class="form-control" id="order_cost" value="<?php echo $r['order_cost']; ?>" name="order_cost" placeholder="Price" readonly />
                        </div>

                        <input type="hidden" name="order_id" value="<?php echo $r['order_id']; ?>" />

                        <div class="form-group mt-3">
                            <label>OrderStatus</label>
                            <select name="order_status" class="form-select" required>
                                <option value="not paid" <?php if ($r['order_status'] == 'not paid') {
                                                                echo "selected";
                                                            } ?>>Not Paid</option>
                                <option value="paid" <?php if ($r['order_status'] == 'paid') {
                                                            echo "selected";
                                                        } ?>>Paid</option>
                                <option value="shipped" <?php if ($r['order_status'] == 'shipped') {
                                                            echo "selected";
                                                        } ?>>Shipped</option>
                                <option value="delivered" <?php if ($r['order_status'] == 'delivered') {
                                                                echo "selected";
                                                            } ?>>Delivered</option>

                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="order_date">OrderDate</label>
                            <input type="text" class="form-control" id="order_date" value="<?php echo $r['order_date']; ?>" name="order_date" placeholder="Date" readonly />
                        </div>
                        <div class="form-group mt-3">
                            <input type="submit" class="btn btn-primary" name="edit_order" value="Edit" />
                        </div>
                    <?php } ?>
                </form>
            </div>

        </main>
    </div>
</div>


<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
<script src="dashboard.js"></script>
</body>

</html>