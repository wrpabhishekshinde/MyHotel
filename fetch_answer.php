<?php
session_start();
// Include the database connection
include('con.php');

// Get the question from the request
$data = json_decode(file_get_contents('php://input'), true);
$question = $data['question'];

try {
    // Prepare a SQL statement to select the answer based on the question
    $stmt = $con->prepare("SELECT answer FROM qa WHERE question LIKE ?");
    $searchTerm = "%{$question}%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the answer from the result set
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $answer = $row['answer'];
    } else {
        // If no matching answer is found, return a default response
        $answer = "I'm sorry, I don't have an answer for that.";
    }

    // Return the answer as JSON
    echo json_encode(['answer' => $answer]);
} catch (Exception $e) {
    // If an error occurs, return an error message
    echo json_encode(['error' => 'An error occurred while fetching the answer.']);
}
?>
