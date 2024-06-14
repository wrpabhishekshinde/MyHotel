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
    <style>
        /* Add any custom styles here */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
        }
        #azurebotContainer {
            width: 100%;
            height: 600px;
            max-width: 500px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
    </style>
</head>
<body>
    <div id="azurebotContainer"></div>
    <script src="https://cdn.botframework.com/botframework-webchat/latest/webchat.js"></script>
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
                })
                .catch(error => console.error('Error fetching the Direct Line secret:', error));
        });
    </script>
</body>
</html>

<?php
include('footer.php');
?>
