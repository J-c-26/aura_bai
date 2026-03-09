<?php
// ============================================
// FILE: reserve.php - HANDLES NEW BOOKINGS AND EXTENSIONS
// ============================================
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include 'db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    
    // Check if this is an extension request
    $action = isset($_POST['action']) ? $_POST['action'] : 'create';
    
    if ($action === 'extend') {
        handleExtension($conn);
    } else {
        handleNewBooking($conn);
    }
}

function handleExtension($conn) {
    // Get extension parameters
    $reservation_id = isset($_POST['reservation_id']) ? intval($_POST['reservation_id']) : 0;
    $room_name = isset($_POST['room_name']) ? $conn->real_escape_string($_POST['room_name']) : '';
    $new_check_out = isset($_POST['new_check_out']) ? $conn->real_escape_string($_POST['new_check_out']) : null;
    $current_check_out = isset($_POST['current_check_out']) ? $conn->real_escape_string($_POST['current_check_out']) : null;
    
    // FIX: If current_check_out not provided but reservation_id is, fetch it from database
    if (empty($current_check_out) && $reservation_id > 0) {
        $sql = "SELECT check_out, room_name, price, nights, email 
                FROM bookings 
                WHERE id = $reservation_id 
                LIMIT 1";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $current_check_out = $row['check_out'];
            // Also populate room_name if not provided
            if (empty($room_name)) {
                $room_name = $row['room_name'];
            }
        }
    }
    
    // Find the active reservation if ID not provided
    if ($reservation_id === 0) {
        // Find the most recent active reservation for this room
        $sql = "SELECT id, room_name, price, check_in, check_out, guests, requests, nights, email 
                FROM bookings 
                WHERE room_name = '$room_name' 
                AND status IN ('Confirmed', 'Staying', 'Pending')
                AND checked_out = 0
                ORDER BY created_at DESC 
                LIMIT 1";
        
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $reservation_id = $row['id'];
            // Use existing data if not provided
            if (empty($room_name)) $room_name = $row['room_name'];
            if (empty($current_check_out)) $current_check_out = $row['check_out'];
        } else {
            echo json_encode(["status" => "Error", "message" => "No active reservation found to extend"]);
            return;
        }
    }
    
    // Validate new check-out time is after current check-out
    if ($new_check_out && $current_check_out) {
        $new_time = strtotime($new_check_out);
        $current_time = strtotime($current_check_out);
        
        if ($new_time <= $current_time) {
            echo json_encode(["status" => "Error", "message" => "New check-out must be after current check-out"]);
            return;
        }
        
        // Calculate additional nights and price
        $additional_nights = ceil(($new_time - $current_time) / (60 * 60 * 24));
        
        // Get room price per night from existing booking
        $price_query = "SELECT price, nights FROM bookings WHERE id = $reservation_id";
        $price_result = $conn->query($price_query);
        $price_per_night = 0;
        $current_nights = 0;
        
        if ($price_result && $price_result->num_rows > 0) {
            $price_row = $price_result->fetch_assoc();
            $current_nights = intval($price_row['nights']);
            // Extract numeric price from string like "$450"
            $price_str = $price_row['price'];
            preg_match('/\$(\d+)/', $price_str, $matches);
            if ($matches) {
                $total_price = intval($matches[1]);
                $price_per_night = $total_price / max(1, $current_nights);
            }
        }
        
        // Calculate new total price
        $new_total_nights = $current_nights + $additional_nights;
        $new_total_price = $price_per_night * $new_total_nights;
        $new_price_str = '$' . $new_total_price;
        
        // Update the reservation
        $update_sql = "UPDATE bookings 
                       SET check_out = '$new_check_out', 
                           nights = $new_total_nights,
                           price = '$new_price_str',
                           status = 'Confirmed'
                       WHERE id = $reservation_id";
        
        if ($conn->query($update_sql) === TRUE) {
            // Send extension notification email
            sendExtensionEmail($reservation_id, $room_name, $new_check_out, $new_price_str, $conn);
            
            echo json_encode([
                "status" => "extended", 
                "message" => "Reservation extended successfully",
                "new_check_out" => $new_check_out,
                "new_price" => $new_price_str,
                "additional_nights" => $additional_nights
            ]);
        } else {
            echo json_encode(["status" => "Error", "message" => "Failed to extend: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "Error", "message" => "Missing check-out dates"]);
    }
}

function handleNewBooking($conn) {
    // ALWAYS send to this pre-coded email only - remove user email entirely
    $email = 'carlobalderama9@gmail.com'; // Your Gmail - the only recipient
    
    $token = bin2hex(random_bytes(16));
    $room = isset($_POST['room_name']) ? $conn->real_escape_string($_POST['room_name']) : "Luxury Suite";
    $price = isset($_POST['price']) ? $conn->real_escape_string($_POST['price']) : "$0";
    $check_in = isset($_POST['check_in']) ? $conn->real_escape_string($_POST['check_in']) : null;
    $check_out = isset($_POST['check_out']) ? $conn->real_escape_string($_POST['check_out']) : null;
    $guests = isset($_POST['guests']) ? intval($_POST['guests']) : 2;
    $requests = isset($_POST['requests']) ? $conn->real_escape_string($_POST['requests']) : '';
    $nights = isset($_POST['nights']) ? intval($_POST['nights']) : 1;

    // Insert into database - store the logged-in user's email for reference
    $userEmail = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : 'guest@aurabay.com';
    
    $columns = "email, token, status, room_name, price, check_in, check_out, guests, requests, nights, checked_out";
    $values = "'$userEmail', '$token', 'Pending', '$room', '$price', " . 
              ($check_in ? "'$check_in'" : "NULL") . ", " . 
              ($check_out ? "'$check_out'" : "NULL") . ", " .
              "$guests, '$requests', $nights, 0";

    $sql = "INSERT INTO bookings ($columns) VALUES ($values)";
    
    if ($conn->query($sql) === TRUE) {
        $mail = new PHPMailer(true);
        
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            
            // Your Gmail credentials
            $mail->Username   = 'carlobalderama9@gmail.com';
            $mail->Password   = 'hlhx ifnf gdzk bdfn';
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->Timeout    = 30;

            $mail->setFrom('carlobalderama9@gmail.com', 'Aura Bay Resort');
            $mail->addAddress('carlobalderama9@gmail.com', 'Aura Bay Admin'); 
            $mail->addReplyTo('info@aurabay.com', 'Aura Bay Support');

            // Content
            $mail->isHTML(true);
            $mail->Subject = "NEW BOOKING: $room - Action Required";
            
            $confirm_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/confirm.php?token=$token&action=confirm";
            $cancel_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/confirm.php?token=$token&action=cancel";
            
            // Include who made the booking in the email
            $mail->Body = buildEmailTemplate($room, $price, $check_in, $check_out, $guests, $requests, $nights, $confirm_url, $cancel_url, $userEmail);
            
            $mail->AltBody = "New booking for $room by $userEmail. Confirm: $confirm_url or Cancel: $cancel_url";

            $mail->send();
            
            error_log("Email sent successfully to admin for booking by: $userEmail");
            
            echo json_encode(["status" => "Pending", "message" => "Booking created and notification sent"]);
            
        } catch (Exception $e) {
            error_log("Email failed: " . $mail->ErrorInfo);
            
            echo json_encode([
                "status" => "Pending", 
                "message" => "Booking saved but notification failed",
                "error" => $mail->ErrorInfo
            ]);
        }
    } else {
        error_log("Database error: " . $conn->error);
        echo json_encode(["status" => "Error", "message" => "Database error: " . $conn->error]);
    }
}

function sendExtensionEmail($reservation_id, $room_name, $new_check_out, $new_price, $conn) {
    try {
        $mail = new PHPMailer(true);
        
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'carlobalderama9@gmail.com';
        $mail->Password   = 'hlhx ifnf gdzk bdfn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->Timeout    = 30;

        $mail->setFrom('carlobalderama9@gmail.com', 'Aura Bay Resort');
        $mail->addAddress('carlobalderama9@gmail.com', 'Aura Bay Admin');
        $mail->addReplyTo('info@aurabay.com', 'Aura Bay Support');

        $mail->isHTML(true);
        $mail->Subject = "RESERVATION EXTENDED: $room_name";
        
        $check_out_display = date('F j, Y g:i A', strtotime($new_check_out));
        
        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Reservation Extended</title>
        </head>
        <body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;'>
            <table style='width: 100%;'>
                <tr>
                    <td align='center' style='padding: 20px 0;'>
                        <table style='max-width: 600px; background: white; border-radius: 10px; overflow: hidden;'>
                            <tr>
                                <td style='background: linear-gradient(135deg, #2D5A4A, #1A3A2F); padding: 40px 30px; text-align: center;'>
                                    <h1 style='color: #E8B86D; margin: 0;'>Aura Bay Resort</h1>
                                    <p style='color: white; margin: 10px 0 0 0;'>Reservation Extended</p>
                                </td>
                            </tr>
                            <tr>
                                <td style='padding: 40px 30px;'>
                                    <h2 style='color: #1A3A2F;'>Stay Extended</h2>
                                    <p style='color: #666;'>A reservation has been extended:</p>
                                    <table style='width: 100%; background: #f9f9f9; border-radius: 8px; margin: 20px 0;'>
                                        <tr>
                                            <td style='padding: 20px;'>
                                                <p><strong>Room:</strong> $room_name</p>
                                                <p><strong>New Check-out:</strong> $check_out_display</p>
                                                <p><strong>New Total Price:</strong> $new_price</p>
                                                <p><strong>Reservation ID:</strong> #$reservation_id</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>";
        
        $mail->send();
        error_log("Extension email sent for reservation #$reservation_id");
    } catch (Exception $e) {
        error_log("Extension email failed: " . $e->getMessage());
    }
}

function buildEmailTemplate($room, $price, $check_in, $check_out, $guests, $requests, $nights, $confirm_url, $cancel_url, $bookedBy) {
    $check_in_display = $check_in ? date('F j, Y', strtotime($check_in)) : 'To be confirmed';
    $check_out_display = $check_out ? date('F j, Y', strtotime($check_out)) : 'To be confirmed';
    
    $requests_html = !empty($requests) ? "
        <div style='background: #fff8e1; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #E8B86D;'>
            <strong style='color: #1A3A2F;'>Special Requests:</strong><br>
            <span style='color: #666;'>" . nl2br(htmlspecialchars($requests)) . "</span>
        </div>" : '';
    
    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>New Reservation - Action Required</title>
    </head>
    <body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5;'>
        <table role='presentation' style='width: 100%; border-collapse: collapse;'>
            <tr>
                <td align='center' style='padding: 20px 0;'>
                    <table role='presentation' style='max-width: 600px; border-collapse: collapse; border: 1px solid #ddd; border-radius: 10px; overflow: hidden; background: white;'>
                        <!-- Header -->
                        <tr>
                            <td style='background: linear-gradient(135deg, #2D5A4A, #1A3A2F); padding: 40px 30px; text-align: center;'>
                                <h1 style='color: #E8B86D; margin: 0; font-family: Georgia, serif; font-size: 28px;'>Aura Bay Resort</h1>
                                <p style='color: white; margin: 10px 0 0 0; font-size: 14px;'>New Booking Received</p>
                            </td>
                        </tr>
                        
                        <!-- Content -->
                        <tr>
                            <td style='padding: 40px 30px;'>
                                <h2 style='color: #1A3A2F; margin-top: 0; font-size: 22px;'>Action Required: Confirm Reservation</h2>
                                
                                <!-- Who booked -->
                                <div style='background: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #2196F3;'>
                                    <strong style='color: #1A3A2F;'>Booked By:</strong>
                                    <span style='color: #666;'> " . htmlspecialchars($bookedBy) . "</span>
                                </div>
                                
                                <p style='color: #666; line-height: 1.6; font-size: 14px;'>A new reservation has been made. Please review the details and confirm or cancel below.</p>
                                
                                <!-- Booking Details Box -->
                                <table role='presentation' style='width: 100%; background: #f9f9f9; border-radius: 8px; margin: 20px 0;'>
                                    <tr>
                                        <td style='padding: 20px;'>
                                            <h3 style='color: #2D5A4A; margin-top: 0; font-size: 16px;'>Booking Details</h3>
                                            <table role='presentation' style='width: 100%;'>
                                                <tr>
                                                    <td style='padding: 8px 0; color: #666; border-bottom: 1px solid #eee; font-size: 14px;'><strong>Room:</strong></td>
                                                    <td style='padding: 8px 0; color: #333; border-bottom: 1px solid #eee; text-align: right; font-size: 14px;'>$room</td>
                                                </tr>
                                                <tr>
                                                    <td style='padding: 8px 0; color: #666; border-bottom: 1px solid #eee; font-size: 14px;'><strong>Check-in:</strong></td>
                                                    <td style='padding: 8px 0; color: #333; border-bottom: 1px solid #eee; text-align: right; font-size: 14px;'>$check_in_display</td>
                                                </tr>
                                                <tr>
                                                    <td style='padding: 8px 0; color: #666; border-bottom: 1px solid #eee; font-size: 14px;'><strong>Check-out:</strong></td>
                                                    <td style='padding: 8px 0; color: #333; border-bottom: 1px solid #eee; text-align: right; font-size: 14px;'>$check_out_display</td>
                                                </tr>
                                                <tr>
                                                    <td style='padding: 8px 0; color: #666; border-bottom: 1px solid #eee; font-size: 14px;'><strong>Nights:</strong></td>
                                                    <td style='padding: 8px 0; color: #333; border-bottom: 1px solid #eee; text-align: right; font-size: 14px;'>$nights</td>
                                                </tr>
                                                <tr>
                                                    <td style='padding: 8px 0; color: #666; border-bottom: 1px solid #eee; font-size: 14px;'><strong>Guests:</strong></td>
                                                    <td style='padding: 8px 0; color: #333; border-bottom: 1px solid #eee; text-align: right; font-size: 14px;'>$guests</td>
                                                </tr>
                                                <tr>
                                                    <td style='padding: 8px 0; color: #666; font-size: 14px;'><strong>Total Price:</strong></td>
                                                    <td style='padding: 8px 0; color: #2D5A4A; font-weight: bold; font-size: 18px; text-align: right;'>$price</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                
                                $requests_html
                                
                                <p style='color: #666; line-height: 1.6; font-size: 14px;'>Click the buttons below to confirm or cancel this reservation:</p>
                                
                                <!-- Action Buttons -->
                                <table role='presentation' style='width: 100%; margin: 30px 0;'>
                                    <tr>
                                        <td align='center'>
                                            <a href='$confirm_url' style='background: #2D5A4A; color: white; padding: 15px 40px; text-decoration: none; border-radius: 30px; font-weight: bold; display: inline-block; margin: 5px; box-shadow: 0 4px 15px rgba(45, 90, 74, 0.3); font-size: 14px;'>✓ Confirm Reservation</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align='center' style='padding-top: 10px;'>
                                            <a href='$cancel_url' style='background: #e74c3c; color: white; padding: 12px 30px; text-decoration: none; border-radius: 30px; font-weight: bold; display: inline-block; margin: 5px; font-size: 13px;'>✕ Cancel Reservation</a>
                                        </td>
                                    </tr>
                                </table>
                                
                                <!-- Footer -->
                                <table role='presentation' style='width: 100%; border-top: 1px solid #eee; padding-top: 20px; margin-top: 30px;'>
                                    <tr>
                                        <td style='text-align: center; color: #999; font-size: 12px;'>
                                            <p style='margin: 0;'>This link will expire in 24 hours.</p>
                                            <p style='margin: 10px 0 0 0;'>© 2024 Aura Bay Resort. All rights reserved.</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>";
}
?>