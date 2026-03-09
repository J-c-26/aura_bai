<?php

include 'db.php';

// Update current active booking to Done status and mark as checked out
$conn->query("UPDATE bookings SET checked_out = 1, status = 'Done' WHERE checked_out = 0");

header('Content-Type: application/json');
echo json_encode(["status" => "success", "message" => "Checked out successfully"]);
?> 