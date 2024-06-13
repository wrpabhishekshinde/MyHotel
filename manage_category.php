<?php  
session_start();
include('header.php');
checkUser();
adminArea();
$msg = "";
$category = "";
$label = "Add";

// Include MySQLi connection with SSL
include('con.php');

if(isset($_GET['id']) && $_GET['id'] > 0){
    $label = "Edit";
    $id = get_safe_value($_GET['id']);

    $stmt = $con->prepare("SELECT * FROM category WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0){
        redirect('category.php');
        die();
    }

    $row = $result->fetch_assoc();
    $category = $row['name'];

    // Close statement
    $stmt->close();
}

if(isset($_POST['submit'])){
    $name = get_safe_value($_POST['name']);
    $type = "add";

    if(isset($_GET['id']) && $_GET['id'] > 0){
        $type = "edit";
        $id = get_safe_value($_GET['id']);
        $sub_sql = " AND id != ?";
    }

    $stmt = $con->prepare("SELECT * FROM category WHERE name = ? $sub_sql");
    $stmt->bind_param("s", $name);

    if(isset($id)){
        $stmt->bind_param("i", $id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $msg = "Category already exists";
    } else {
        if($type == "edit"){
            $stmt = $con->prepare("UPDATE category SET name = ? WHERE id = ?");
            $stmt->bind_param("si", $name, $id);
            $stmt->execute();
            redirect('category.php');
        } else {
            $stmt = $con->prepare("INSERT INTO category (name) VALUES (?)");
            $stmt->bind_param("s", $name);
            $stmt->execute();
            redirect('category.php');
        }
    }

    // Close statement
    $stmt->close();
}

// Close MySQLi connection
$con->close();
?>
<script>
   setTitle("Manage Category");
   selectLink('category_link');
</script>
<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h2><?php echo $label?> Category</h2>
               <a href="expense.php">Back</a>
               <div class="card">
                  <div class="card-body card-block">
                     <form method="post" class="form-horizontal">
                        <div class="form-group">
                           <label class="control-label mb-1">Category</label>
                           <input type="text" name="name" required value="<?php echo htmlspecialchars($category) ?>" class="form-control" required>
                        </div>
                        <div class="form-group">												
                           <input type="submit" name="submit" value="Submit" class="btn btn-lg btn-info btn-block">                          
                        </div>
                        <div id="msg"><?php echo $msg ?></div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php
include('footer.php');
?>
