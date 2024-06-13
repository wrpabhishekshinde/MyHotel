<?php
// Footer content
echo '<div style="margin-top:55vh;">';
echo '&nbsp &nbsp &nbsp &nbsp &copy; ' . date('Y');
echo '&nbsp &nbsp &nbsp All rights reserved';
echo '&nbsp &nbsp &nbsp Developed by G.S.';
echo '</div>';
?>
</div>
</div>
<!-- Jquery JS-->
<script src="jquery-3.2.1.min.js"></script>
<!-- Bootstrap JS-->
<script src="bootstrap-4.1/popper.min.js"></script>
<script src="bootstrap-4.1/bootstrap.min.js"></script>
<!-- Vendor JS-->
<script src="animsition/animsition.min.js"></script>
<!-- Main JS-->
<script src="js/main.js"></script>
<!-- End document-->
<script>
    function change_cat(){
        var category_id = document.getElementById('category_id').value;
        window.location.href = '?category_id=' + category_id;
    }

    function delete_confir(id, page){
        var check = confirm("Are you sure?");
        if (check === true) {
            window.location.href = page + "?type=delete&id=" + id;
        }
    }

    function set_to_date(){
        var from_date = document.getElementById('from_date').value;
        document.getElementById('to_date').setAttribute("min", from_date);
    }
</script>
</body>
</html>
