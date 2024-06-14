<?php
session_start();
include('con.php');
include('header.php');
checkUser();
adminArea();

if (isset($_GET['type']) && $_GET['type'] == 'delete' && isset($_GET['id']) && $_GET['id'] > 0) {
    $id = get_safe_value($_GET['id']);
    mysqli_query($con, "delete from qa where id=$id");
    echo "<br/>Data deleted<br/>";
}

$res = mysqli_query($con, "select * from qa order by id desc");
?>
<script>
    setTitle("ChatBot");
    selectLink('chatbot_link');
</script>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h2>ChatBot</h2>
                    <a href="manage_add_qa.php">Add Question and Answers</a>
                    <br/><br/>
                    <div class="table-responsive table--no-card m-b-30">
                        <table class="table table-borderless table-striped table-earning">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($res) > 0) {
                                    while ($row = mysqli_fetch_assoc($res)) {
                                ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['question']; ?></td>
                                            <td><?php echo $row['answer']; ?></td>
                                            <td>
                                                <a href="manage_add_qa.php?id=<?php echo $row['id']; ?>">Edit</a>&nbsp;
                                                <a href="javascript:void(0)" onclick="delete_confir('<?php echo $row['id']; ?>','add_qa.php')">Delete</a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No data found</td></tr>";
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
include('footer.php');
?>
