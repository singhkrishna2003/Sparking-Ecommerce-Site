<!-- Header  -->
<?php include("layouts/header.php"); ?>

<?php


if (isset($_SESSION['logged_in'])) {
    header('location: account.php');
    exit;
}

if (isset($_POST['login_btn'])) {

    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT user_id,user_name,user_email,user_password FROM users WHERE user_email = ? AND user_password = ? LIMIT 1");

    $stmt->bind_param("ss", $email, $password);

    if ($stmt->execute()) {
        $stmt->bind_result($user_id, $user_name, $user_email, $user_password);
        $stmt->store_result();

        if ($stmt->num_rows() == 1) {
            $stmt->fetch();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_email'] = $user_email;
            $_SESSION['logged_in'] = true;
            header('location: account.php?login_success=logged in successfully !');
        } else {
            header("location: login.php?error=could'nt verify your account !");
        }
    } else {
        //error message
        header('location: login.php?error=something went wrong !');
    }
}
?>


<!-- Login -->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold">Login</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container">
        <form id="login-form" method="POST" action="login.php">
            <p style="color: red;" class="text-center"><?php if (isset($_GET['error'])) {
                                                            echo $_GET['error'];
                                                        }  ?></p>
            <p style="color: green;" class="text-center"><?php if (isset($_GET['add_to_cart_msg'])) {
                                                            echo $_GET['add_to_cart_msg'];
                                                        }  ?></p>
            <div class="form-group">
                <label for="login-email">Email</label>
                <input type="email" class="form-control" id="login-email" name="email" placeholder="Email" required />
            </div>
            <div class="form-group">
                <label for="login-password">Password</label>
                <input type="password" class="form-control" id="login-password" name="password" placeholder="Password" required />
            </div>
            <div class="form-group">
                <input type="submit" class="btn" id="login-btn" name="login_btn" value="Login" />
            </div>
            <div class="form-group">
                <a id="register-url" class="btn" href="register.php">Don't have account? <span>Register</span></a>
            </div>
        </form>
    </div>
</section>

<!-- Footer -->
<?php include("layouts/footer.php") ?>

