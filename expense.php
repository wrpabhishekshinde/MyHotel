<?php
session_start();
include('header.php');
include('con.php'); // Include the SSL connection setup
checkUser();
userArea();

$msg = "";

if (isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['id']) && $_GET['id'] > 0) {
    $id = get_safe_value($_GET['id']);
    
    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("DELETE FROM expense WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo "<br/>Data deleted<br/>";
}

// Use prepared statements for fetching expense data
$stmt = $con->prepare("SELECT expense.*, category.name 
                       FROM expense 
                       JOIN category ON expense.category_id = category.id 
                       WHERE expense.added_by = ? 
                       ORDER BY expense.expense_date ASC");
$stmt->bind_param("i", $_SESSION['UID']);
$stmt->execute();
$res = $stmt->get_result();
?>
<script>
   setTitle("Expense");
   selectLink('expense_link');
</script>
<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h2>Expense</h2>
               <a href="manage_expense.php">Add Expense</a>
               <br/><br/>
               <div class="table-responsive table--no-card m-b-30">
                  <table class="table table-borderless table-striped table-earning">
                     <thead>
                        <tr>
                           <td>ID</td>
                           <td>Category</td>
                           <td>Price</td>
                           <td>Item/Type</td>
                           <td>Expense Date</td>
                           <td></td>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if ($res->num_rows > 0) { ?>
                           <?php while ($row = $res->fetch_assoc()) { ?>
                           <tr>
                              <td><?php echo $row['id']; ?></td>
                              <td><?php echo $row['name']; ?></td>
                              <td><?php echo $row['price']; ?></td>
                              <td><?php echo $row['item']; ?></td>                           
                              <td><?php echo $row['expense_date']; ?></td>
                              <td>
                                 <a href="manage_expense.php?id=<?php echo $row['id']; ?>">Edit</a>&nbsp;
                                 <a href="javascript:void(0)" onclick="delete_confir('<?php echo $row['id']; ?>','expense.php')">Delete</a>
                              </td>
                           </tr>
                           <?php } ?>
                        <?php } else { ?>
                           <tr>
                              <td colspan="6">No data found</td>
                           </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php
include('footer.php');
?>
<?php
// Close the prepared statement and the database connection
$stmt->close();
$con->close();
?>
