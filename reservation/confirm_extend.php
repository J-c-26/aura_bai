<?php
include 'db.php';

if(isset($_POST['action']) && $_POST['action'] == 'extend') {
    $new_check_out = $_POST['new_check_out'];
    
    // Find the latest active booking and update ONLY the check_out date.
    $sql = "UPDATE bookings SET check_out = '$new_check_out', status = 'Staying' 
            WHERE checked_out = 0 AND (status = 'Confirmed' OR status = 'Staying') 
            ORDER BY id DESC LIMIT 1";
            
    if($conn->query($sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
?>