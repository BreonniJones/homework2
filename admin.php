<?php
session_start();
include('config/config.php');

if (!isset($_SESSION['userlogin']) || $_SESSION['userRole'] != 'Admin') {
    header('location: index.php');
    exit;
}

$Username = $_SESSION['userlogin'];

$query = $con->prepare("SELECT Email, Password FROM admin WHERE (Username=:username || Email=:username)");
$query->bindParam(':username', $Username, PDO::PARAM_STR);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
$Email = $row['Email'];
$hashedPassword = $row['Password']; // Get the hashed password
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet"> 
    <link href="css/mystyle.css" rel="stylesheet"> 
    <title>Admin Page</title>
</head>
<body>
    <div class="container"> 
        <div class="row">
            <h1>Welcome to Admin Page <font face="Tahoma" color="red"><?php echo $Username . "    " . $Email; ?></font></h1>
            <br>
            <p>Hashed Password: <?php echo $hashedPassword; ?></p> 
            <a href="logout.php" class="btn btn-danger">Log me out</a>
        </div>
    </div>
</body>
</html>
