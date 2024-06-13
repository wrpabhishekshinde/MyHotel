<?php  
session_start();
include('header.php');
checkUser();
adminArea();
$msg = "";
$username = "";
$password = "";
$label = "Add";
$new_pass = "";

// Include MySQLi connection with SSL
include('con.php');

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $label = "Edit";
    $id = get_safe_value($_GET['id']);

    $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        redirect('users.php');
        die();
    }

    $row = $result->fetch_assoc();
    $username = $row['username'];
    $password = $row['password'];

    // Close statement
    $stmt->close();
}

if (isset($_POST['submit'])) {
    $username = get_safe_value($_POST['username']);
    $password = get_safe_value($_POST['password']);

    $type = "add";
    $sub_sql = "";
    if (isset($_GET['id']) && $_GET['id'] > 0) {
        $type = "edit";
        $sub_sql = " AND id != ?";
    }

    // Prepare the SQL query to check if username exists
    $query = "SELECT * FROM users WHERE username = ?" . $sub_sql;
    $stmt = $con->prepare($query);

    if ($type == "edit") {
        $stmt->bind_param("si", $username, $id);
    } else {
        $stmt->bind_param("s", $username);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $msg = "Username already exists";
    } else {
        if ($type == "edit") {
            // Only hash the password if it has changed
            if ($password != $row['password']) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            } else {
                $hashed_password = $password;
            }
            $stmt = $con->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssi", $username, $hashed_password, $id);
            $stmt->execute();
            redirect('users.php');
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $con->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'User')");
            $stmt->bind_param("ss", $username, $hashed_password);
            $stmt->execute();
            redirect('users.php');
        }
    }

    // Close statement
    $stmt->close();
}

// Close MySQLi connection
$con->close();
?>
<script>
   setTitle("Manage Users");
   selectLink('users_link');
</script>
<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h2><?php echo $label ?> User</h2>
               <a href="users.php">Back</a>
               <div class="card">
                  <div class="card-body card-block">
                     <form method="post" class="form-horizontal">
                        <div class="form-group">
                           <label class="control-label mb-1">Username</label>
                           <input type="text" name="username" required value="<?php echo htmlspecialchars($username); ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                           <label class="control-label mb-1">Password</label>
                           <input type="text" name="password" required value="<?php echo htmlspecialchars($password) ?: htmlspecialchars($new_pass); ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                           <input type="submit" name="submit" value="Submit" class="btn btn-lg btn-info btn-block">
                        </div>
                        <div id="msg"><?php echo $msg; ?></div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php include('footer.php'); ?>
