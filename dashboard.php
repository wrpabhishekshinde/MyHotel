<?php
session_start();
include('con.php');
include('header.php');
checkUser();
userArea();
?>

<script>
    setTitle("Dashboard");
    selectLink('dashboard_link');
</script>

<div class="main-content">
    <div class="section__content section__content--p30" style="padding: 20px;">
        <div class="container-fluid">
            <div class="row m-t-25">
                <?php
                $timeframes = ['DAY', 'DAY', 'WEEK', 'MONTH', 'YEAR', 'YEAR'];
                $labels = ["Today's Expense", "Yesterday's Expense", "This Week's Expense", "This Month's Expense", "This Year's Expense", "Total Expense"];
                
                // Initialize MySQLi connection with SSL
                include('con.php');

                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                for ($i = 0; $i < count($timeframes); $i++) {
                    $interval = $i == 1 ? 'DAY' : $timeframes[$i]; // Special case for yesterday
                    $intervalAmount = $i == 1 ? 1 : 1; // Special case for yesterday
                    $result = mysqli_query($con, "SELECT SUM(expense.price) AS total_price 
                                                  FROM expense 
                                                  JOIN category ON expense.category_id = category.id 
                                                  WHERE expense.added_by = '" . $_SESSION['UID'] . "' 
                                                  AND expense.expense_date >= DATE_SUB(CURDATE(), INTERVAL $intervalAmount $interval)");

                    if (!$result) {
                        echo "Error: " . mysqli_error($con);
                        continue;
                    }

                    $row = mysqli_fetch_assoc($result);
                    $total_expense = $row['total_price'] ?? 0;

                    echo '<div class="col-sm-6 col-lg-3">
                        <div class="overview-item overview-item--c1">
                            <div class="overview__inner">
                                <div class="overview-box clearfix">
                                    <div class="text">
                                        <h2>' . number_format($total_expense, 2) . '</h2>
                                        <span>' . $labels[$i] . '</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }

                // Close MySQLi connection
                mysqli_close($con);
                ?>
            </div>

            <!-- Slide Show of Images -->
            <main>
                <img src="slide/p1.png" class="slide" alt="image 1">
                <img src="slide/p2.png" class="slide" alt="image 2">
                <img src="slide/p3.png" class="slide" alt="image 3">
                <img src="slide/p4.png" class="slide" alt="image 4">
                <img src="slide/p5.png" class="slide" alt="image 5">

                <div class="nav">
                    <button onclick="goPrev()" class="nav-button prev">&#10094;</button>
                    <button onclick="goNext()" class="nav-button next">&#10095;</button>
                </div>
            </main>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>

<style>
    body {
        position: relative;
        min-height: 100vh;
        margin: 0;
    }

    * {
        box-sizing: border-box;
    }

    .section__content--p30 {
        padding: 20px;
    }

    .row.m-t-25 {
        margin-top: 0;
    }

    .col-sm-6.col-lg-3 {
        padding: 5px;
    }

    main {
        width: 80%;
        height: 500px;
        margin-left: 10%;
        margin-right: auto;
        margin-top: 120px;

        box-shadow: 0px 0px 3px grey;
        position: relative;
        overflow: hidden;
    }

    .slide {
        width: 100%;
        height: 100%;
        transition: transform 1s;
        position: absolute;
    }

    .nav {
        position: absolute;
        width: 100%;
        top: 50%;
        display: flex;
        justify-content: space-between;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .nav-button {
        font-size: 50px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px;
        pointer-events: all;
        transition: background-color 0.3s;
    }

    .nav-button:hover {
        background-color: rgba(0, 0, 0, 0.7);
    }

    .overview-item {
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 10px;
        transition: transform 0.3s ease-in-out;
    }

    .overview-item:hover {
        transform: scale(1.05);
    }

    .overview-item--c1 {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #333;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .overview__inner {
        padding: 20px;
    }

    .overview-box .text {
        text-align: center;
    }

    .overview-box .text h2 {
        font-size: 36px;
        font-weight: bold;
        margin: 0 0 10px;
        color: #495057;
    }

    .overview-box .text span {
        font-size: 18px;
        color: #6c757d;
    }

    @media (max-width: 767px) {
        .overview-item {
            margin-bottom: 10px;
        }

        .overview-box .text h2 {
            font-size: 28px;
        }

        .overview-box .text span {
            font-size: 16px;
        }
    }
</style>

<script>
    let currentIndex = 0;
    const slides = document.querySelectorAll('.slide');

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.style.transform = `translateX(${(i - index) * 100}%)`;
        });
    }

    function goNext() {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
    }

    function goPrev() {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        showSlide(currentIndex);
    }

    // Initialize the slideshow
    showSlide(currentIndex);

    // Automatically go to the next slide every 3 seconds
    setInterval(goNext, 3000);
</script>
