<?php include("header.php") ?>

<?php

if (!isset($_SESSION['admin_logged_in'])) {
  header("location: login.php");
  exit;
}

?>

<?php

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
$stmt1 = $conn->prepare("SELECT COUNT(*) AS total_records FROM orders");
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

$stmt2 = $conn->prepare("SELECT * FROM orders LIMIT $offset,$total_records_per_page");
// $stmt2->bind_param("ii", $offset, $total_records_per_page);
$stmt2->execute();
$orders =  $stmt2->get_result(); // [] Array of products

?>

<div class="container-fluid">
  <div class="row">

    <?php include("sidemenu.php") ?>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
              Share
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
              Export
            </button>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
          </button>
        </div>
      </div>

      <h2>Orders</h2>

      <?php if (isset($_GET['order_updated'])) { ?>
        <p style="color: green;" class="text-center"><?php echo $_GET['order_updated']; ?></p>
      <?php } ?>

      <?php if (isset($_GET['order_failed'])) { ?>
        <p style="color: red;" class="text-center"><?php echo $_GET['order_failed']; ?></p>
      <?php } ?>

      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">Order Id</th>
              <th scope="col">Order Status</th>
              <th scope="col">Orders</th>
              <th scope="col">User Id</th>
              <th scope="col">Order Date</th>
              <th scope="col">User Phone</th>
              <th scope="col">User Address</th>
              <th scope="col">Edit</th>
              <th scope="col">Delete</th>

            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order) { ?>
              <tr>
                <td><?php echo $order['order_id']; ?></td>
                <td><?php echo $order['order_status']; ?></td>
                <td><?php echo $order['user_id']; ?></td>
                <td><?php echo $order['order_date']; ?></td>
                <td><?php echo $order['user_phone']; ?></td>
                <td><?php echo $order['user_address']; ?></td>
                <td><a class="btn btn-primary" href="edit_order.php?order_id=<?php echo $order['order_id']; ?>">Edit</a></td>
                <td><a class="btn btn-danger" href="delete_order.php?_id=<?php echo $order['order_id']; ?>">Delete</a></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>

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
    </main>
  </div>
</div>


<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
<script src="dashboard.js"></script>
</body>

</html>