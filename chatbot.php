<?php
session_start();
include('header.php');
checkUser();
userArea();
?>

<script>
    setTitle("Dashboard");
    selectLink('dashboard_link');
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Azure FRT Bot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/perfect-scrollbar/1.5.5/css/perfect-scrollbar.min.css">
    <style>
        /* Add any custom styles here */
         body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
            margin-top: 600px;
            padding-right: 250px;
        }
        #azurebotContainer {
            width: 100%;
            height: 600px;
            max-width: 500px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="azurebotContainer"></div>
    </div>
    <script src="https://cdn.botframework.com/botframework-webchat/latest/webchat.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/perfect-scrollbar/1.5.5/perfect-scrollbar.min.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            fetch('getSecret.php')
                .then(response => response.json())
                .then(data => {
                    const styleSet = window.WebChat.createStyleSet({
                        backgroundColor: '#F8F8F8'
                    });

                    window.WebChat.renderWebChat({
                        directLine: window.WebChat.createDirectLine({
                            secret: data.secret
                        }),
                        styleSet
                    }, document.getElementById('azurebotContainer'));

                    new PerfectScrollbar('#azurebotContainer'); // Initialize PerfectScrollbar
                })
                .catch(error => console.error('Error fetching the Direct Line secret:', error));
        });
    </script>
</body>
</html>
<?php
include('footer.php');
?>
