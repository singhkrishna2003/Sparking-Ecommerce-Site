<!-- Header  -->
<?php include("layouts/header.php"); ?>

<?php


if (isset($_SESSION['logged_in'])){ //if user has already registered, then take user account page 
    header('location: account.php');
    exit();
}

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // if password is dont match
    if ($password !== $confirmPassword) {

        header("location: register.php?error=passwords does'nt match");

    } else if (strlen($password) < 6) { // if password length is less than 6 characters

        header("location: register.php?error=password must be at least 6 characters");
    } else { //If there is no error

        // check whether there is a user this email or not
        $stmt1 = $conn->prepare("SELECT count(*) FROM users WHERE user_email=?");
        $stmt1->bind_param("s", $email);
        $stmt1->execute();
        $stmt1->bind_result($num_rows);
        $stmt1->store_result();
        $stmt1->fetch();

        // if user with this email already exists
        if ($num_rows != 0) {
            header("location: register.php?error= user with this email already exists !");
            

        } else {//if  no user exists then create a new user
            //crate a new user
            $stmt = $conn->prepare("INSERT INTO users (user_name,user_email,user_password) VALUES (?,?,?)");
            $stmt->bind_param("sss", $name, $email, md5($password));
           
            //if account is created successfully
           if($stmt->execute()){
            $user_id = $stmt->insert_id;
            $_SESSION["user_id"] = $user_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $name;
            $_SESSION['logged_in'] = true;
            header("location: account.php?register_success=You registered Successfully !");
            
           }else{//account could not be created 
             header('location: register.php?error=could not create an account at the moment !');
            
           }
        }
    }
}

?>


    <!-- Register -->
    <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Register</h2>
            <hr class="mx-auto">
        </div>
        <div class="mx-auto container">
            <form id="register-form" method="POST" action="register.php ">
                <p style="color: red;"><?php if (isset($_GET['error'])) {
                                            echo $_GET['error'];
                                        } ?></p>
                <div class="form-group">
                    <label for="register-name">Name</label>
                    <input type="text" class="form-control" id="register-name" name="name" placeholder="Name" required />
                </div>
                <div class="form-group">
                    <label for="register-email">Email</label>
                    <input type="email" class="form-control" id="register-email" name="email" placeholder="Email" required />
                </div>
                <div class="form-group">
                    <label for="register-password">Password</label>
                    <input type="password" class="form-control" id="register-password" name="password" placeholder="Password" required />
                </div>
                <div class="form-group">
                    <label for="register-confirm-password">Confirm Password</label>
                    <input type="password" class="form-control" id="register-confirm-password" name="confirmPassword" placeholder="Confirm Password" required />
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" id="register-btn" name="register" value="Register" />
                </div>
                <div class="form-group">
                    <a id="login-url" class="btn" href="login.php">Do you have an account? <span>Login</span></a>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <?php include("layouts/footer.php") ?>

    