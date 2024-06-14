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
    <title>Custom_frt_bot Integration</title>
    <style>
        /* Global styles */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        /* Container for the bot */
        #customFrtBotContainer {
            width: 100%;
            height: 600px;
            max-width: 500px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Chatbot iframe styling */
        .webchat__basic-transcript {
            background-color: #f8f9fa; /* Light grey background */
            padding: 10px;
            overflow-y: auto; /* Enable scrolling */
            height: 100%; /* Full height */
        }

        /* Chatbot message bubbles */
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

        /* Input area styling */
        .webchat__input-box {
            border-top: 1px solid #ddd;
            padding: 10px;
            background-color: #f8f9fa; /* Light grey */
            display: flex;
            align-items: center;
        }

        .webchat__input {
            flex: 1;
            border: none;
            outline: none;
            padding: 8px;
            border-radius: 4px;
            margin-right: 10px;
        }

        .webchat__send-box-button {
            background-color: #007bff; /* Blue */
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Typing indicator */
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
    <div id="customFrtBotContainer"></div>
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

                    const customFrtBotContainer = document.getElementById('customFrtBotContainer');

                    window.WebChat.renderWebChat({
                        directLine: window.WebChat.createDirectLine({
                            secret: data.secret
                        }),
                        styleSet
                    }, customFrtBotContainer)
                    .then(() => {
                        const sendBox = customFrtBotContainer.querySelector('.webchat__send-box');
                        const sendInput = customFrtBotContainer.querySelector('.webchat__input');

                        // Enable send button functionality
                        sendBox.addEventListener('submit', event => {
                            event.preventDefault();
                            const userInput = sendInput.value.trim();
                            if (userInput) {
                                displayMessage(userInput, 'user');
                                sendMessage(userInput);
                                sendInput.value = '';
                            }
                        });

                        function displayMessage(message, type) {
                            const messages = customFrtBotContainer.querySelector('.webchat__basic-transcript');
                            const messageElem = document.createElement('div');
                            messageElem.textContent = message;
                            messageElem.className = `webchat__bubble webchat__bubble_${type}`;
                            messages.appendChild(messageElem);
                            messages.scrollTop = messages.scrollHeight;
                        }

                        function sendMessage(message) {
                            // Simulate bot response
                            const botResponse = "This is a response from the bot: " + message;
                            displayMessage(botResponse, 'bot');
                        }
                    })
                    .catch(error => {
                        console.error('Error rendering Web Chat:', error);
                    });
                })
                .catch(error => console.error('Error fetching the Direct Line secret:', error));
        });
    </script>
</body>
</html>

