<?php
session_start();
include('config/config.php');

if (isset($_SESSION['userlogin'])) {
    header('location: admin.php');
    exit;
}

if (isset($_POST['login'])) {
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];

    // Retrieve the hashed password from your database based on the username or email
    $query = "SELECT Username, Email, Password, Role FROM admin WHERE (Username=:uname || Email=:uname)";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':uname', $Username, PDO::PARAM_STR);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($stmt->rowCount() > 0) {
        foreach ($results as $row) {
            $hashpass = $row->Password;
            $Role = $row->Role;
        }

        if (password_verify($Password, $hashpass) && ($Role == 'User' || $Role == 'admin')) {
            $_SESSION['userlogin'] = $_POST['Username'];
            $_SESSION['userRole'] = $Role;

            if ($Role == 'admin') {
                header('location: admin.php');
            } elseif ($Role == 'User') {
                header('location: user.php');
            }
            exit;
        } else {
            $errorMessage = "Incorrect Username/Password";
        }
    } else {
        $errorMessage = "User not found";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mystyle.css" rel="stylesheet">
    <title>Login Page</title>
</head>

<body>
    <div class="wrapper">
        <div class="logo">
            <img src="image/adduser.jpeg">
        </div>
        <div class="text-center mt-4 name">
            LOGIN
        </div>
        <form class="p-3 mt-3" method="post">
            <?php if (isset($errorMessage)) : ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <div class="form-field d-flex align-items-center">
                <span class="fa fa-user"></span>
                <input type="text" name="Username" id="Username" placeholder="Username">
            </div>
            <div class="form-field d-flex align-items-center">
                <span class="fa fa-key"></span>
                <input type="Password" name="Password" id="pwd" placeholder="Password">
            </div>
            <button type="submit" class="btn mt-3" name="login">Login</button>
        </form>
        <div class="text-center fs-6">
            <a href="#">Forget Password?</a> or <a href="Signup.php">Sign up</a>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>