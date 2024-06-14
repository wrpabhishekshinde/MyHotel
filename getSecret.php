<?php
// getSecret.php
header('Content-Type: application/json');

// Securely store your Direct Line secret. In a real application, consider using environment variables or a secure vault.
$secret = 'ZrJiLUNGHdg.EDLMWz8bODXlw9KxXkMO3yKOx2dnlcaMK_w3AuBWdAE';

echo json_encode(['secret' => $secret]);
?>
