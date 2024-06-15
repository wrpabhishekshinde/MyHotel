<?php  
session_start();
include('header.php');
checkUser();
userArea();
$msg = "";
$category_id = "";
$item = "";
$price = "";
$details = "";
$expense_date = date("Y-m-d");
$added_on = "";
$label = "Add";

// Include MySQLi connection
include('con.php');

if(isset($_GET['id']) && $_GET['id'] > 0){
    $label = "Edit";
    $id = get_safe_value($_GET['id']);

    $stmt = $con->prepare("SELECT * FROM expense WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0){
        redirect('expense.php');
        die();
    }

    $row = $result->fetch_assoc();
    $category_id = $row['category_id'];
    $item = $row['item'];
    $price = $row['price'];
    $details = $row['details'];
    $expense_date = $row['expense_date'];

    // Check if the expense belongs to the current user
    if($row['added_by'] != $_SESSION['UID']){
        redirect('expense.php');
    }
}

if(isset($_POST['submit'])){
    $category_id = get_safe_value($_POST['category_id']);
    $item = get_safe_value($_POST['item']);
    $price = get_safe_value($_POST['price']);
    $details = get_safe_value($_POST['details']);
    $expense_date = get_safe_value($_POST['expense_date']);
    $added_on = date('Y-m-d h:i:s');
    $added_by = $_SESSION['UID'];

    $type = "add";
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $type = "edit";
        $id = get_safe_value($_GET['id']);
    }

    if($type == "edit"){
        $stmt = $con->prepare("UPDATE expense SET category_id = ?, price = ?, item = ?, details = ?, expense_date = ? WHERE id = ? AND added_by = ?");
        $stmt->bind_param("isssiii", $category_id, $price, $item, $details, $expense_date, $id, $added_by);
        $stmt->execute();
        redirect('expense.php');
    } else {
        $stmt = $con->prepare("INSERT INTO expense (category_id, price, item, details, expense_date, added_on, added_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $category_id, $price, $item, $details, $expense_date, $added_on, $added_by);
        $stmt->execute();
        redirect('expense.php');
    }
}
?>
<script>
   setTitle("Manage Expense");
   selectLink('expense_link');
</script>
<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h2><?php echo $label?> Expense</h2>
               <a href="expense.php">Back</a>
               <div class="card">
                  <div class="card-body card-block">
                     <form method="post" class="form-horizontal">
                        <div class="form-group">
                           <label class="control-label mb-1">Category</label>
                           <?php echo getCategory($category_id); ?>                               
                        </div>
                        <div class="form-group">
                           <label class="control-label mb-1">Item/Type</label>
                           <input type="text" name="item" required value="<?php echo $item?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                           <label class="control-label mb-1">Price</label>
                           <input type="text" name="price" required value="<?php echo $price?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                           <label class="control-label mb-1">Details</label>
                           <input type="text" name="details" required value="<?php echo $details?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                           <label class="control-label mb-1">Expense Date</label>
                           <input type="date" name="expense_date" required value="<?php echo $expense_date?>" class="form-control" required max="<?php echo date('Y-m-d')?>">
                        </div>
                        <div class="form-group">
                           <input type="submit" name="submit" value="Submit" class="btn btn-lg btn-info btn-block">
                        </div>
                        <div id="msg"><?php echo $msg?></div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php include('footer.php'); ?>
