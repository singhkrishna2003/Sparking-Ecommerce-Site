<?php include("header.php") ?>

<?php
if (isset($_GET['product_id'])) {

    $product_id = $_GET['product_id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id =?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $products =  $stmt->get_result(); // [] Array of products 



} else if (isset($_POST['edit_btn'])) {

    $product_id = $_POST['product_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $offer = $_POST['offer'];
    $category = $_POST['category'];
    $color = $_POST['color'];



    $stmt = $conn->prepare("UPDATE products SET product_name = ?,product_discription=?,product_price=?,product_special_offer=?,product_color=?,product_category=? WHERE product_id =?");
    $stmt->bind_param("ssssssi", $title, $description, $price, $offer, $color, $category, $product_id);

    if ($stmt->execute()) {
        header("location: products.php?edit_success_message=Product has been updated successfully!");
    } else {
        header("location: products.php?edit_failure_message=Error occured, try again !");
    }
} else {
    header("location: products.php");
    exit;
}

?>

<div class="container-fluid">
    <div class="row">

        <?php include("sidemenu.php") ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">

                    </div>
                </div>
            </div>

            <h2>Edit</h2>
            <div class="table-responsive">
                <form id="edit-form" method="POST" action="edit_product.php">
                    <p style="color: red;"><?php if (isset($_GET['error'])) {
                                                echo $_GET['error'];
                                            } ?></p>
                    <?php foreach ($products as $product) { ?>

                        <div class="form-group mt-2">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>" />
                            <label for="product-name">Title</label>
                            <input type="text" class="form-control" id="product-name" value="<?php echo $product['product_name']; ?>" name="title" placeholder="Title" required />
                        </div>
                        <!-- <div class="form-group mt-2">
                            <label for="product-desc">Description</label>
                            <input type="text" class="form-control" id="product-desc" value="<?php echo $product['product_discription']; ?>" name="description" placeholder="Description" required />
                        </div> -->
                        <div class="mt-2">
                            <label for="product-desc">Description</label>
                            <textarea class="form-control" id="product-desc" rows="3" name="description" placeholder="Description" required><?php echo $product['product_discription']; ?>
                            </textarea>
                        </div>
                        <div class="form-group mt-2">
                            <label for="product-price">Price</label>
                            <input type="text" class="form-control" id="product-price" value="<?php echo $product['product_price']; ?>" name="price" placeholder="Price" required />
                        </div>
                        <div class="form-group mt-2">
                            <label for="product-category">Category</label>
                            <select name="category" class="form-select" id="product-category" required>
                                <option value="bags">Bags</option>
                                <option value="shoes">Shoes</option>
                                <option value="watches">Watches</option>
                                <option value="coats">Coats</option>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="product-color">Color</label>
                            <input type="text" class="form-control" id="product-color" value="<?php echo $product['product_color']; ?>" name="color" placeholder="Color" required />
                        </div>
                        <div class="form-group mt-2">
                            <label for="product-special-offer">Special Offer/Sale</label>
                            <input type="number" class="form-control" id="product-special-offer" value="<?php echo $product['product_special_offer']; ?>" name="offer" placeholder="Sale%" required />
                        </div>
                        <div class="form-group mt-3">
                            <input type="submit" class="btn btn-primary" name="edit_btn" value="Edit" />
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