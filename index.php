<?php
session_start(); // Make sure to start the session
include('con.php');
include('functions.php');

$msg = "";
if (isset($_POST['login'])) {
    $username = get_safe_value($_POST['username']);
    $password = get_safe_value($_POST['password']);
    
    // Prepare the SQL statement to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        
        $verify = password_verify($password, $row['password']);
        
        if ($verify) {
            $_SESSION['UID'] = $row['id'];
            $_SESSION['UNAME'] = $row['username'];
            $_SESSION['UROLE'] = $row['role'];
            if ($_SESSION['UROLE'] == 'User') {
                redirect('dashboard.php');
            } else {
                redirect('category.php');
            }
        } else {
            $msg = "Please enter a valid password";
        }
    } else {
        $msg = "Please enter a valid username";
    }
    
    // Close the statement
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Login</title>

    <!-- Bootstrap CSS-->
    <link href="bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

</head>

<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="#">
                                <img src="images/icon/logo.png" alt="Finanse Manager">
                            </a>
                        </div>
                        <div class="login-form">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="au-input au-input--full" type="text" name="username" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="au-input au-input--full" type="password" name="password" placeholder="Password" required>
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit" name="login">Sign In</button>
                            </form>
                            <div id="msg"><?php echo $msg ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="bootstrap-4.1/popper.min.js"></script>
    <script src="bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Main JS-->
    <script src="js/main.js"></script>

</body>

</html>
