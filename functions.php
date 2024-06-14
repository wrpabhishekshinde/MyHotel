<?php
session_start();
function prx($data){
	echo '<pre>';
	print_r($data);
	die();
}

function get_safe_value($data){
	global $con;
	if($data){
		return mysqli_real_escape_string($con, htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
	}
}

function redirect($link){
	?>
	<script>
	window.location.href="<?php echo $link ?>";
	</script>
	<?php
}

function checkUser(){
	if(isset($_SESSION['UID']) && $_SESSION['UID'] != ''){
		// User is logged in, no action needed
	} else {
		redirect('index.php');
	}
}

function getCategory($category_id = '', $page = ''){
	global $con;
	$res = mysqli_query($con, "SELECT * FROM category ORDER BY name ASC");
	$fun = "required";
	if($page == 'reports'){
		$fun = "";
	}
	$html = '<select name="category_id" id="category_id" class="form-control" '.$fun.'>';
		$html .= '<option value="">Select Category</option>';
		while($row = mysqli_fetch_assoc($res)){
			if($category_id > 0 && $category_id == $row['id']){
				$html .= '<option value="'.$row['id'].'" selected>'.$row['name'].'</option>';
			}else{
				$html .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';	
			}
		}
	$html .= '</select>';
	return $html;
}

function getDashboardExpense($type){
	global $con;
	$today = date('Y-m-d');
	if($type == 'today'){
		$sub_sql = " AND expense_date='$today'";
		$from = $today;
		$to = $today;
	} elseif($type == 'yesterday'){
		$yesterday = date('Y-m-d', strtotime('yesterday'));
		$sub_sql = " AND expense_date='$yesterday'";
		$from = $yesterday;
		$to = $yesterday;
	} elseif($type == 'week' || $type == 'month' || $type == 'year'){
		$from = date('Y-m-d', strtotime("-1 $type"));
		$sub_sql = " AND expense_date BETWEEN '$from' AND '$today'";
		$to = $today;
	} else {
		$sub_sql = " ";
		$from = '';
		$to = '';
	}
	
	$res = mysqli_query($con, "SELECT SUM(price) AS price FROM expense WHERE added_by='".$_SESSION['UID']."' $sub_sql");
	
	$row = mysqli_fetch_assoc($res);
	$p = 0;
	$link = "";
	if($row['price'] > 0){
		$p = $row['price'];
		$link = "&nbsp;<a href='dashboard_report.php?from=".$from."&to=".$to."' target='_blank' class='detail_link'>Details</a>";
	}
	
	return $p.$link;	
}

function adminArea(){
	if($_SESSION['UROLE'] != 'Admin'){
		redirect('dashboard.php');
	}
}

function userArea(){
	if($_SESSION['UROLE'] != 'User'){
		redirect('category.php');
	}
}

// Ensure the connection file is included
include_once('con.php');

/**
 * Function to get the user's name by ID
 *
 * @param int $id User ID
 * @return string|false User's name or false if not found
 */
function getUserNameById($id) {
    global $con;
    $id = intval($id);
    $stmt = $con->prepare("SELECT name FROM users WHERE id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $con->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($name);
    $result = $stmt->fetch() ? $name : false;
    $stmt->close();
    return $result;
}

/**
 * Function to safely get a value from GET request
 *
 * @param string $key The key to look for in the GET request
 * @return string|null The sanitized value or null if not set
 */
function get_safe_value_get($key) {
    return isset($_GET[$key]) ? htmlspecialchars($_GET[$key], ENT_QUOTES, 'UTF-8') : null;
}

/**
 * Function to get the user's name from session
 *
 * @return string|null The user's name or null if not logged in
 */
function getUserNameBySession() {
    if (isset($_SESSION['UID'])) {
        return getUserNameById($_SESSION['UID']);
    }
    return null;
}
?>
