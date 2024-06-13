<?php  
include('header.php');
checkUser();
userArea();
$msg = "";
$category_id = "";
$amount = "";
$details = "";
$income_date = date("Y-m-d");
$added_on = "";
$label = "Add";

// Include MySQLi connection with SSL
include('con.php');

if(isset($_GET['id']) && $_GET['id'] > 0){
    $label = "Edit";
    $id = get_safe_value($_GET['id']);

    $stmt = $con->prepare("SELECT * FROM income WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0){
        redirect('income.php');
        die();
    }

    $row = $result->fetch_assoc();
    $category_id = $row['category_id'];
    $amount = $row['amount'];
    $details = $row['details'];
    $income_date = $row['income_date'];

    // Check if the income belongs to the current user
    if($row['added_by'] != $_SESSION['UID']){
        redirect('income.php');
    }

    // Close statement
    $stmt->close();
}

if(isset($_POST['submit'])){
    $category_id = get_safe_value($_POST['category_id']);
    $amount = get_safe_value($_POST['amount']);
    $details = get_safe_value($_POST['details']);
    $income_date = get_safe_value($_POST['income_date']);
    $added_on = date('Y-m-d h:i:s');
    $added_by = $_SESSION['UID'];

    $type = "add";
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $type = "edit";
        $id = get_safe_value($_GET['id']);
        $sub_sql = " AND id != ?";
    }

    if($type == "edit"){
        $stmt = $con->prepare("UPDATE income SET category_id = ?, amount = ?, details = ?, income_date = ? WHERE id = ?");
        $stmt->bind_param("idsii", $category_id, $amount, $details, $income_date, $id);
        $stmt->execute();
        redirect('income.php');
    } else {
        $stmt = $con->prepare("INSERT INTO income (category_id, amount, details, income_date, added_on, added_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idsiss", $category_id, $amount, $details, $income_date, $added_on, $added_by);
        $stmt->execute();
        redirect('income.php');
    }

    // Close statement
    $stmt->close();
}

// Close MySQLi connection
$con->close();
?>
<script>
   setTitle("Manage Income");
   selectLink('income_link');
</script>
<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h2><?php echo $label?>  Income</h2>
               <a href="income.php">Back</a>
               <div class="card">
                  <div class="card-body card-block">
                     <form method="post" class="form-horizontal">
                        <div class="form-group">
                           <label class="control-label mb-1">Category</label>
                           <?php echo getCategory($category_id); ?>                               
                        </div>
                        <div class="form-group">
                           <label class="control-label mb-1">Amount</label>
                           <input type="text" name="amount" required value="<?php echo htmlspecialchars($amount); ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                           <label class="control-label mb-1">Details</label>
                           <input type="text" name="details" required value="<?php echo htmlspecialchars($details); ?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                           <label class="control-label mb-1">Income Date</label>
                           <input type="date" name="income_date" required value="<?php echo $income_date; ?>" class="form-control" required max="<?php echo date('Y-m-d'); ?>">
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
