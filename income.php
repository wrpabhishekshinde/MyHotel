<?php
session_start();
include('header.php');
checkUser();
userArea();

// Include MySQLi connection with SSL
include('con.php');

if(isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['id']) && $_GET['id'] > 0){
    $id = get_safe_value($_GET['id']);

    // Prepared statement for deleting income record
    $stmt = $con->prepare("DELETE FROM income WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Close statement
    $stmt->close();

    // Redirect to income.php after deletion
    header('Location: income.php');
    exit;
}

// Prepared statement for fetching income data joined with category name
$stmt = $con->prepare("SELECT income.id, income.amount, income.details, income.income_date, category.name 
                       FROM income 
                       INNER JOIN category ON income.category_id = category.id 
                       WHERE income.added_by = ? 
                       ORDER BY income.income_date ASC");
$stmt->bind_param("i", $_SESSION['UID']);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Income</h2>
                    <a href="manage_income.php">Add Income</a>
                    <br/><br/>
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Details</th>
                                    <th>Income Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($result->num_rows > 0){
                                    while($row = $result->fetch_assoc()){
                                        ?>
                                        <tr>
                                            <td><?php echo $row['id'];?></td>
                                            <td><?php echo $row['name'];?></td>
                                            <td><?php echo $row['amount'];?></td>
                                            <td><?php echo $row['details'];?></td>                           
                                            <td><?php echo $row['income_date'];?></td>
                                            <td>
                                                <a href="manage_income.php?id=<?php echo $row['id'];?>">Edit</a>&nbsp;
                                                <a href="javascript:void(0)" onclick="delete_confir('<?php echo $row['id'];?>','income.php')">Delete</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No data found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Close statement and MySQLi connection
$stmt->close();
$con->close();

include('footer.php');
?>
