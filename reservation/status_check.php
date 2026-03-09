<?php
include 'db.php';

$conn->query("UPDATE bookings SET status = 'Cancelled' WHERE status = 'Pending' AND created_at < NOW() - INTERVAL 1 DAY");

$conn->query("UPDATE bookings SET status = 'Staying' WHERE status = 'Confirmed' AND DATE(check_in) <= CURDATE() AND DATE(check_out) > CURDATE()");

$conn->query("UPDATE bookings SET status = 'Done', checked_out = 1 WHERE (status = 'Staying' OR status = 'Confirmed') AND DATE(check_out) <= CURDATE()");

$histQ = $conn->query("SELECT room_name, status, price, check_in, check_out, created_at FROM bookings ORDER BY id DESC");
$history = [];
while($r = $histQ->fetch_assoc()) { $history[] = $r; }

$res = $conn->query("SELECT * FROM bookings WHERE checked_out = 0 AND (status='Confirmed' OR status='Staying') ORDER BY id DESC LIMIT 1");
$current = $res->fetch_assoc();

header('Content-Type: application/json');
echo json_encode([
    "currentStatus" => $current ? $current['status'] : "None",
    "currentReservation" => $current,
    "history" => $history
]);
?>