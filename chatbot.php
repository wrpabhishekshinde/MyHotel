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
        /* Existing styles for your chat button and dialog */
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

        /* Additional styles for Azure Web Chat */
        #healthBotContainer {
            width: 100%;
            height: 600px;
            max-width: 500px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px; /* Adjust spacing */
        }

        .webchat__basic-transcript {
            background-color: #f8f9fa; /* Light grey background */
            padding: 10px;
            overflow-y: auto; /* Enable scrolling */
            height: 100%; /* Full height */
        }

        .webchat__bubble {
            border-radius: 8px;
            padding: 8px 12px;
            margin: 6px 0;
            max-width: 70%;
            word-wrap: break-word;
        }

        .webchat__bubble_from_user {
            background-color: #007bff; /* Blue */
            color: white;
            align-self: flex-end;
        }

        .webchat__bubble_from_bot {
            background-color: #f8f9fa; /* Light grey */
            color: black;
            align-self: flex-start;
        }

        .webchat__input-box {
            border-top: 1px solid #ddd;
            padding: 10px;
            background-color: #f8f9fa; /* Light grey */
        }

        .webchat__send-box-button {
            background-color: #007bff; /* Blue */
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .webchat__typing-indicator {
            background-color: #f8f9fa; /* Light grey */
            padding: 6px;
            display: flex;
            align-items: center;
        }

        .webchat__typing-indicator__ellipsis {
            font-size: 24px;
            line-height: 0.8;
            margin-right: 6px;
            animation: ellipsis-animation 1s infinite;
        }

        @keyframes ellipsis-animation {
            0% {
                opacity: 0.1;
            }
            20% {
                opacity: 1;
            }
            100% {
                opacity: 0.1;
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

    <!-- Azure Web Chat Container -->
    <div id="healthBotContainer"></div>

    <!-- Azure Web Chat Script -->
    <script src="https://cdn.botframework.com/botframework-webchat/latest/webchat.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            fetch('getSecret.php')
                .then(response => response.json())
                .then(data => {
                    const styleSet = window.WebChat.createStyleSet({
                        bubbleBackground: '#f8f9fa', // Light grey background for bubbles
                        bubbleFromUserBackground: '#007bff', // Blue background for user messages
                        sendBoxButtonBackground: '#007bff', // Blue background for send button
                        sendBoxButtonColor: 'white', // White text for send button
                        typingIndicatorColor: '#007bff' // Blue color for typing indicator
                    });

                    window.WebChat.renderWebChat({
                        directLine: window.WebChat.createDirectLine({
                            secret: data.secret
                        }),
                        styleSet
                    }, document.getElementById('healthBotContainer'));
                })
                .catch(error => console.error('Error fetching the Direct Line secret:', error));
        });

        // Your existing JavaScript for handling the chat button and dialog
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
                messages.appendChild(messageElem
