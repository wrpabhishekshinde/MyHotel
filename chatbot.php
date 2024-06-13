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
    <title>Chatbot</title>
    <style>
        body {
            position: relative;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .chat-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 30px;
            position: fixed;
            bottom: 400px;
            right: 450px;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s;
        }

        .chat-button span.fas {
            margin-right: 10px;
        }

        .chat-button:hover {
            background-color: #62f0f0;
        }

        .chat-dialog {
            display: none;
            position: fixed;
            bottom: 80px;
            right: 500px;
            width: 500px;
            height: 600px;
            background-color: white;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            overflow: hidden;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-header span {
            font-size: 18px;
            font-weight: bold;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        .chat-body {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
        }

        .messages {
            display: flex;
            flex-direction: column;
        }

        .message {
            max-width: 70%;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
        }

        .message.user {
            align-self: flex-end;
            background-color: #dcf8c6;
        }

        .message.bot {
            align-self: flex-start;
            background-color: #f1f0f0;
        }

        .chat-footer {
            display: flex;
            padding: 10px;
            border-top: 1px solid #e0e0e0;
        }

        .chat-footer input {
            flex: 1;
            padding: 10px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }

        .chat-footer button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            margin-left: 5px;
            border-radius: 4px;
            cursor: pointer;
        }

        .chat-footer button:hover {
            background-color: #0067cc;
        }

        .typing-animation {
            display: inline-block;
            overflow: hidden;
            position: relative;
            vertical-align: bottom;
            margin-left: 5px;
            width: 0;
            animation: typing 1s steps(10, end) infinite;
        }

        @keyframes typing {
            from {
                width: 0;
            }

            to {
                width: auto;
            }
        }
    </style>
</head>

<body>
    <!-- Chat Bot Button -->
    <button type="button" class="chat-button"><span class="fas fa-comments"></span> Chat with Us</button>

    <!-- Chat Bot Dialog -->
    <div id="chatDialog" class="chat-dialog">
        <div class="chat-header">
            <span>Chat with Us</span>
            <button class="close-btn" onclick="closeChat()">×</button>
        </div>
        <div class="chat-body">
            <div class="messages" id="chatMessages"></div>
        </div>
        <div class="chat-footer">
            <input type="text" id="chatInput" placeholder="Type a message...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chatButton = document.querySelector('.chat-button');
            const chatDialog = document.getElementById('chatDialog');

            chatButton.addEventListener('click', () => {
                chatDialog.style.display = 'flex';
            });

            window.closeChat = function () {
                const chatMessages = document.getElementById('chatMessages');
                const chatInput = document.getElementById('chatInput');
                chatMessages.innerHTML = '';
                chatInput.value = '';
                chatDialog.style.display = 'none';
            }

            window.sendMessage = function () {
                const input = document.getElementById('chatInput');
                const message = input.value.trim();

                if (message) {
                    displayMessage(message, 'user');
                    fetchResponse(message);
                    input.value = '';
                }
            }

            function displayMessage(message, type) {
                const messages = document.getElementById('chatMessages');
                const messageElem = document.createElement('div');
                messageElem.textContent = message;
                messageElem.className = 'message ' + type;
                messages.appendChild(messageElem);
                messages.scrollTop = messages.scrollHeight;
            }

            async function fetchResponse(question) {
                try {
                    // Simulate a delay of 1-3 seconds
                    const delay = Math.floor(Math.random() * 2000) + 500;
                    await new Promise(resolve => setTimeout(resolve, delay));

                    const response = await fetch('fetch_answer.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ question }),
                    });

                    const data = await response.json();
                    if (data.answer) {
                        displayBotTypingAnimation();
                        setTimeout(() => {
                            hideBotTypingAnimation();
                            displayMessage(data.answer, 'bot');
                        }, 1000); // Delay for typing animation
                    } else {
                        displayMessage("I'm sorry, I don't have an answer for that.", 'bot');
                    }
                } catch (error) {
                    console.error('Error fetching answer:', error);
                    displayMessage("An error occurred while fetching the answer.", 'bot');
                }
            }

            function displayBotTypingAnimation() {
                const messages = document.getElementById('chatMessages');
                const typingAnimation = document.createElement('div');
                typingAnimation.textContent = 'Typing...';
                typingAnimation.className = 'typing-animation';
                messages.appendChild(typingAnimation);
                messages.scrollTop = messages.scrollHeight;
            }

            function hideBotTypingAnimation() {
                const messages = document.getElementById('chatMessages');
                const typingAnimation = messages.querySelector('.typing-animation');
                if (typingAnimation) {
                    messages.removeChild(typingAnimation);
                }
            }
        });
    </script>
</body>

</html>

<?php
include('footer.php');
?>
