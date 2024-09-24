<!-- Header  -->
<?php include("layouts/header.php"); ?>
<?php


if (isset($_POST['search'])) { //used for search section

    //1. Determine no product Page
    if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
        //if User has already entered page. Then page number is the one that they selected
        $page_no = $_GET['page_no'];
    } else {
        //if user just entered the page the defaultpage is 1
        $page_no = 1;
    }


    $category = $_POST['category'];
    $price = $_POST['price'];
    //2. return the number of products
    //return no. of products
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products WHERE product_category=? AND product_price <=?");
    $stmt1->bind_param('si', $category, $price);
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();


    //3. set total no. of products per page
    $total_records_per_page = 8;
    $offset = ($page_no - 1) * $total_records_per_page;


    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;

    $adjacents = "2";
    $total_no_of_pages = ceil($total_records / $total_records_per_page);

    //4. get all products

    $stmt2 = $conn->prepare("SELECT * FROM products WHERE  product_category=? AND product_price<=? LIMIT $offset,$total_records_per_page");
    $stmt2->bind_param("si", $category, $price);
    $stmt2->execute();
    $products =  $stmt2->get_result(); // [] Array of products


} else { //return all product --->if you have smaller number of products
    //pagination

    //1. Determine no product Page
    if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
        //if User has already entered page. Then page number is the one that they selected
        $page_no = $_GET['page_no'];
    } else {
        //if user just entered the page the defaultpage is 1
        $page_no = 1;
    }


    //2. return the number of products
    //return no. of products
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM products");
    $stmt1->execute();
    $stmt1->bind_result($total_records);
    $stmt1->store_result();
    $stmt1->fetch();

    //3. set total no. of products per page
    $total_records_per_page = 8;
    $offset = ($page_no - 1) * $total_records_per_page;


    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;

    $adjacents = "2";
    $total_no_of_pages = ceil($total_records / $total_records_per_page);

    //4. get all products

    $stmt2 = $conn->prepare("SELECT * FROM products LIMIT $offset,$total_records_per_page");
    // $stmt2->bind_param("ii", $offset, $total_records_per_page);
    $stmt2->execute();
    $products =  $stmt2->get_result(); // [] Array of products
}

?>


<!-- Search -->
<section id="search" class="my-5 py-5 ms-2">
    <div class="container mt-5 py-5">
        <p>Search Product</p>
        <hr>
    </div>
    <form action="shop.php" method="POST">
        <div class="row max-auto container">
            <div class=" col-lg-12 col-md-12 col-sm-12">

                <p>Category</p>
                <div class="form-check">
                    <input class="form-check-input" value="shoes" type="radio" name="category" id="category_one" <?php if (isset($category) && $category == 'shoes') {
                                                                                                                        echo 'checked';
                                                                                                                    } ?> />
                    <label class="form-check-label" for="category_one">Shoes</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" value="coats" type="radio" name="category" id="category_two" <?php if (isset($category) && $category == 'coats') {
                                                                                                                        echo 'checked';
                                                                                                                    } ?> />
                    <label class="form-check-label" for="category_two">Coats</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" value="watches" type="radio" name="category" id="category_three" <?php if (isset($category) && $category == 'watches') {
                                                                                                                            echo 'checked';
                                                                                                                        } ?> />
                    <label class="form-check-label" for="category_three">Watches</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" value="bags" type="radio" name="category" id="category_four" <?php if (isset($category) && $category == 'bags') {
                                                                                                                        echo 'checked';
                                                                                                                    } ?> />
                    <label class="form-check-label" for="category_four">Bags</label>
                </div>
            </div>
        </div>
        <div class="row mx-auto container mt-5">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <p>Price</p>
                <input type="range" class="form-range w-50" name="price" value="<?php if (isset($price)) {
                                                                                    echo  $price;
                                                                                } else {
                                                                                    echo "100";
                                                                                }  ?>" min="1" max="1000" id="customRange2" />
                <div class="w-50 mt-3">
                    <span style="float: left;">1</span>
                    <span style="float: right;">1000</span>
                </div>
            </div>
        </div>
        <div class="form-group my-3 mx-3">
            <input type="submit" name="search" value="Search" class="btn search-btn" />
        </div>
    </form>
</section>

<!-- Shop -->
<section id="shop" class="my-5 py-4">
    <div class="container mt-5 py-5">
        <h3>Our Products</h3>
        <hr>
        <p>Here you can check out our products</p>
    </div>

    <div class="row mx-auto container">

        <?php while ($row = $products->fetch_assoc()) { ?>
            <div onclick="window.location.href='single_product.html';" class="product text-center col-lg-3 col-md-4 col-sm-12">
                <img class="img-fluid mb-3" src="assets\imgs\<?php echo $row['product_image']; ?>" />
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
                <h4 class="p-price">$<?php echo $row['product_price']; ?></h4>
                <a class="btn buy-btn" href="<?php echo "single_product.php?product_id=" . $row['product_id']; ?>">Buy Now</a>
            </div>

        <?php } ?>

        <!--Page Navigation -->
        <nav aria-label="Page navigation example">
            <ul class="pagination mt-5">

                <li class="page-item <?php if ($page_no <= 1) {
                                            echo 'disabled';
                                        } ?>">
                    <a href="<?php if ($page_no <= 1) {
                                    echo '#';
                                } else {
                                    echo '?page_no=' . ($page_no - 1);
                                } ?>" class="page-link">Previous</a>
                </li>
                <li class="page-item"><a href="?page_no=1" class="page-link">1</a></li>
                <li class="page-item"><a href="?page_no=2" class="page-link">2</a></li>
                <?php if ($page_no >= 3) { ?>
                    <li class="page-item"><a href="#" class="page-link">...</a></li>
                    <li class="page-item"><a href="<?php echo "?page_no=" . ($page_no); ?>" class="page-link"><?php echo $page_no; ?></a></li>

                <?php } ?>

                <li class="page-item <?php if ($page_no >= $total_no_of_pages) {
                                            echo 'disabled';
                                        } ?>">
                    <a href="<?php if ($page_no >= $total_no_of_pages) {
                                    echo '#';
                                } else {
                                    echo '?page_no=' . ($page_no + 1);
                                } ?>" class="page-link">Next</a>
                </li>

            </ul>
        </nav>

    </div>
</section>


<!-- Footer -->
<?php include("layouts/footer.php") ?>