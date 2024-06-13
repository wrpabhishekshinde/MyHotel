<?php
session_start();
include('header.php');
include('con.php'); // Include the SSL connection setup
checkUser();
adminArea();

$msg = "";

if (isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['id']) && $_GET['id'] > 0) {
    $id = get_safe_value($_GET['id']);
    
    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("DELETE FROM category WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo "<br/>Data deleted<br/>";
}

// Use prepared statements for fetching category data
$stmt = $con->prepare("SELECT * FROM category ORDER BY id DESC");
$stmt->execute();
$res = $stmt->get_result();
?>
<script>
   setTitle("Category");
   selectLink('category_link');
</script>
<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h2>Category</h2>
               <a href="manage_category.php">Add category</a>
               <br/><br/>
               <div class="table-responsive table--no-card m-b-30">
                  <table class="table table-borderless table-striped table-earning">
                     <thead>
                        <tr>
                           <th>ID</th>
                           <th>Name</th>
                           <th></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if ($res->num_rows > 0) { ?>
                           <?php while ($row = $res->fetch_assoc()) { ?>
                           <tr>
                              <td><?php echo $row['id']; ?></td>
                              <td><?php echo $row['name']; ?></td>
                              <td>
                                 <a href="manage_category.php?id=<?php echo $row['id']; ?>">Edit</a>&nbsp;
                                 <a href="javascript:void(0)" onclick="delete_confir('<?php echo $row['id']; ?>','category.php')">Delete</a>
                              </td>
                           </tr>
                           <?php } ?>
                        <?php } else { ?>
                           <tr>
                              <td colspan="3">No data found</td>
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
