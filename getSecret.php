<?php
// getSecret.php
header('Content-Type: application/json');

// Securely store your Direct Line secret. In a real application, consider using environment variables or a secure vault.
$secret = 'ZrJiLUNGHdg.llNmtZyDthZtmz0BH7AiLeYM3cOOIciIniA9LzSoBJU';

echo json_encode(['secret' => $secret]);
?>
