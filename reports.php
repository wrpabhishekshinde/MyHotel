<?php
session_start();
include('header.php');
checkUser();
userArea();

$cat_id = '';
$sub_sql = '';
$from = '';
$to = '';

// Check if category_id is set and valid
if (isset($_GET['category_id']) && $_GET['category_id'] > 0) {
    $cat_id = get_safe_value($_GET['category_id']);
    $sub_sql .= " and category.id=$cat_id ";
}

// Check if from date is set and valid
if (isset($_GET['from']) && !empty($_GET['from'])) {
    $from = get_safe_value($_GET['from']);
}

// Check if to date is set and valid
if (isset($_GET['to']) && !empty($_GET['to'])) {
    $to = get_safe_value($_GET['to']);
}

// Add date filter to the SQL query if both dates are provided
if ($from !== '' && $to !== '') {
    $sub_sql .= " and expense.expense_date between '$from' and '$to' ";
}

// Establish MySQLi connection using con.php for SSL configuration
include('con.php'); // Ensure con.php has appropriate SSL configurations

// SQL query to get the filtered data
$sql = "SELECT sum(expense.price) as price, category.name 
        FROM expense, category 
        WHERE expense.category_id = category.id 
        AND expense.added_by='" . $_SESSION['UID'] . "' 
        $sub_sql 
        GROUP BY expense.category_id";
$res = mysqli_query($con, $sql);
?>
<script>
    setTitle("Reports");
    selectLink('reports_link');
</script>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="filter_form">
                        <form method="get">
                            From <input type="date" name="from" value="<?php echo $from ?>" max="<?php echo date('Y-m-d') ?>" id="from_date" class="form-control w250">
                            &nbsp;&nbsp;&nbsp;
                            To <input type="date" name="to" value="<?php echo $to ?>" max="<?php echo date('Y-m-d') ?>" id="to_date" class="form-control w250">
                            <?php echo getCategory($cat_id, 'reports'); ?>
                            <input type="submit" name="submit" value="Submit" class="btn btn-lg btn-info btn-block">
                            <a href="reports.php" class="btn btn-lg btn-warning btn-block">Reset</a>
                        </form>
                    </div>
                    <?php
                    if (mysqli_num_rows($res) > 0) {
                    ?>
                        <br /><br />
                        <div class="table-responsive table--no-card m-b-30">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $final_price = 0;
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $final_price += $row['price'];
                                    ?>
                                        <tr>
                                            <td><?php echo $row['name'] ?></td>
                                            <td><?php echo $row['price'] ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                        <th>Total</th>
                                        <th><?php echo $final_price ?></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php
                    } else {
                        echo "<b>No data found</b>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include('footer.php');
?>
