<?php  
include('header.php');
checkUser();
adminArea();
$msg = "";
$question = "";
$answer = "";
$label = "Add";

if(isset($_GET['id']) && $_GET['id'] > 0){
    $label = "Edit";
    $id = get_safe_value($_GET['id']);
    
    // Initialize MySQLi connection with SSL
    include('con.php');

    $stmt = $con->prepare("SELECT * FROM qa WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0){
        redirect('add_qa.php');
        die();
    }

    $row = $result->fetch_assoc();
    $question = $row['question'];
    $answer = $row['answer'];

    // Close statement
    $stmt->close();
    // Close MySQLi connection
    $con->close();
}

if(isset($_POST['submit'])){
    $question = get_safe_value($_POST['question']);
    $answer = get_safe_value($_POST['answer']);
    $type = "add";

    // Initialize MySQLi connection with SSL
    include('con.php');

    if(isset($_GET['id']) && $_GET['id'] > 0){
        $type = "edit";
        $id = get_safe_value($_GET['id']);
        $sub_sql = " AND id != ?";
    }

    $stmt = $con->prepare("SELECT * FROM qa WHERE question = ? AND answer = ? $sub_sql");
    $stmt->bind_param("ss", $question, $answer);
    
    if(isset($id)){
        $stmt->bind_param("i", $id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $msg = "Question already exists";
    } else {
        if($type == "edit"){
            $stmt = $con->prepare("UPDATE qa SET question = ?, answer = ? WHERE id = ?");
            $stmt->bind_param("ssi", $question, $answer, $id);
            $stmt->execute();
            redirect('add_qa.php');
        } else {
            $stmt = $con->prepare("INSERT INTO qa (question, answer) VALUES (?, ?)");
            $stmt->bind_param("ss", $question, $answer);
            $stmt->execute();
            redirect('add_qa.php');
        }
    }

    // Close statement
    $stmt->close();
    // Close MySQLi connection
    $con->close();
}
?>
<script>
   setTitle("Manage Add Question");
   selectLink('chatbot_link');
</script>
<div class="main-content">
   <div class="section__content section__content--p30">
      <div class="container-fluid">
         <div class="row">
            <div class="col-lg-12">
               <h2><?php echo $label?> Question & Answer</h2>
               <a href="expense.php">Back</a>
               <div class="card">
                  <div class="card-body card-block">
                     <form method="post" class="form-horizontal">
                        <div class="form-group">												<label class="control-label mb-1">Question</label>
                           <input type="text" name="question" required value="<?php echo $question?>" class="form-control" required>
                        </div>
                        <div class="form-group">												<label class="control-label mb-1">Answer</label>
                           <input type="text" name="answer" required value="<?php echo $answer?>" class="form-control" required>
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
<?php
   include('footer.php');
?>
