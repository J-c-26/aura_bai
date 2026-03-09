<?php
// ============================================
// FILE: confirm.php 
// ============================================
if (isset($_GET['token'])) {
    include 'db.php';
    $token = $conn->real_escape_string($_GET['token']);
    $action = isset($_GET['action']) ? $conn->real_escape_string($_GET['action']) : 'confirm';
    
    if ($action === 'cancel') {
        $sql = "UPDATE bookings SET status = 'Cancelled' 
                WHERE token = '$token' AND status = 'Pending'";
        
        if ($conn->query($sql) === TRUE && $conn->affected_rows > 0) {
            echo "
            <!DOCTYPE html>
            <html>
            <head>
                <title>Reservation Cancelled</title>
                <style>
                    body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                    .container { background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); text-align: center; max-width: 500px; }
                    .icon { width: 100px; height: 100px; background: #ffebee; color: #c62828; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 50px; margin: 0 auto 30px; }
                    h1 { color: #c62828; margin-bottom: 20px; }
                    p { color: #666; line-height: 1.6; margin-bottom: 30px; }
                    button { background: #c62828; color: white; border: none; padding: 12px 30px; border-radius: 25px; cursor: pointer; font-size: 16px; transition: all 0.3s; }
                    button:hover { background: #b71c1c; transform: translateY(-2px); }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='icon'>✕</div>
                    <h1>Reservation Cancelled</h1>
                    <p>Your reservation has been cancelled successfully. We hope to see you at Aura Bay Resort in the future.</p>
                    <button onclick='window.close()'>Close This Window</button>
                </div>
            </body>
            </html>";
        } else {
            echo "
            <!DOCTYPE html>
            <html>
            <head>
                <title>Error</title>
                <style>
                    body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                    .container { background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); text-align: center; max-width: 500px; }
                    .icon { width: 100px; height: 100px; background: #fff3e0; color: #ef6c00; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 50px; margin: 0 auto 30px; }
                    h1 { color: #ef6c00; margin-bottom: 20px; }
                    p { color: #666; line-height: 1.6; margin-bottom: 30px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='icon'>!</div>
                    <h1>Link Invalid or Expired</h1>
                    <p>This reservation might have already been processed or the link is broken.</p>
                </div>
            </body>
            </html>";
        }
    } else {
        $sql = "UPDATE bookings SET status = 'Staying' 
                WHERE token = '$token' AND status = 'Pending'";
        
        if ($conn->query($sql) === TRUE && $conn->affected_rows > 0) {
            echo "
            <!DOCTYPE html>
            <html>
            <head>
                <title>Reservation Confirmed</title>
                <style>
                    body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                    .container { background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); text-align: center; max-width: 500px; }
                    .icon { width: 100px; height: 100px; background: #e8f5e9; color: #2e7d32; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 50px; margin: 0 auto 30px; }
                    h1 { color: #2e7d32; margin-bottom: 20px; }
                    p { color: #666; line-height: 1.6; margin-bottom: 30px; }
                    button { background: #2e7d32; color: white; border: none; padding: 12px 30px; border-radius: 25px; cursor: pointer; font-size: 16px; transition: all 0.3s; }
                    button:hover { background: #1b5e20; transform: translateY(-2px); }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='icon'>✓</div>
                    <h1>Reservation Confirmed!</h1>
                    <p>Your stay at Aura Bay Resort is now officially booked. We look forward to welcoming you to paradise!</p>
                    <button onclick='window.close()'>Close This Window</button>
                </div>
            </body>
            </html>";
        } else {
            echo "
            <!DOCTYPE html>
            <html>
            <head>
                <title>Error</title>
                <style>
                    body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                    .container { background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); text-align: center; max-width: 500px; }
                    .icon { width: 100px; height: 100px; background: #fff3e0; color: #ef6c00; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 50px; margin: 0 auto 30px; }
                    h1 { color: #ef6c00; margin-bottom: 20px; }
                    p { color: #666; line-height: 1.6; margin-bottom: 30px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='icon'>!</div>
                    <h1>Link Invalid or Expired</h1>
                    <p>This reservation might have already been confirmed or the link is broken.</p>
                </div>
            </body>
            </html>";
        }
    }
    exit;
}
?>

<?php
// ============================================
// FILE: db.php 
// ============================================
$host = "127.0.0.1";
$port = 3307; 
$user = "root";
$pass = "";
$dbname = "reservation_system";

$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
}
?>

<?php
// ============================================
// FILE: status_check.php 
// ============================================
include 'db.php';

// Auto-cancel logic - Cancel pending bookings older than 1 day
$conn->query("UPDATE bookings SET status = 'Cancelled' WHERE status = 'Pending' AND created_at < NOW() - INTERVAL 1 DAY");

// Get History 
$histQ = $conn->query("SELECT room_name, status, price, check_in, check_out, created_at FROM bookings ORDER BY id DESC LIMIT 10");
$history = [];
while($row = $histQ->fetch_assoc()) { 
    $history[] = $row; 
}

// Get the one current active booking (not checked out)
$res = $conn->query("SELECT status, room_name FROM bookings WHERE checked_out = 0 ORDER BY id DESC LIMIT 1");
$row = $res->fetch_assoc();

header('Content-Type: application/json');
echo json_encode([
    "currentStatus" => $row ? $row['status'] : "No Reservation",
    "roomName" => $row ? $row['room_name'] : "",
    "history" => $history
]);
?>

<?php
// ============================================
// FILE: checkout.php 
// ============================================
include 'db.php';

// Update current active booking to Done status and mark as checked out
$conn->query("UPDATE bookings SET checked_out = 1, status = 'Done' WHERE checked_out = 0");

header('Content-Type: application/json');
echo json_encode(["status" => "success", "message" => "Checked out successfully"]);
?> 

<?php
// ============================================
// FILE: reserve.php - FIXED EMAIL SENDING
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
    
    // Get email from session or POST
    $email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : (isset($_POST['email']) ? $_POST['email'] : 'guest@aurabay.com');
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "Error", "message" => "Invalid email address"]);
        exit;
    }
    
    $token = bin2hex(random_bytes(16));
    $room = isset($_POST['room_name']) ? $conn->real_escape_string($_POST['room_name']) : "Luxury Suite";
    $price = isset($_POST['price']) ? $conn->real_escape_string($_POST['price']) : "$0";
    $check_in = isset($_POST['check_in']) ? $conn->real_escape_string($_POST['check_in']) : null;
    $check_out = isset($_POST['check_out']) ? $conn->real_escape_string($_POST['check_out']) : null;
    $guests = isset($_POST['guests']) ? intval($_POST['guests']) : 2;
    $requests = isset($_POST['requests']) ? $conn->real_escape_string($_POST['requests']) : '';
    $nights = isset($_POST['nights']) ? intval($_POST['nights']) : 1;

    // Insert into database first
    $columns = "email, token, status, room_name, price, check_in, check_out, guests, requests, nights, checked_out";
    $values = "'$email', '$token', 'Pending', '$room', '$price', " . 
              ($check_in ? "'$check_in'" : "NULL") . ", " . 
              ($check_out ? "'$check_out'" : "NULL") . ", " .
              "$guests, '$requests', $nights, 0";

    $sql = "INSERT INTO bookings ($columns) VALUES ($values)";
    
    if ($conn->query($sql) === TRUE) {
        // Send email
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->SMTPDebug = 0; // Set to 2 for debugging, 0 for production
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            
            // IMPORTANT: Update these credentials
            $mail->Username   = 'carlobalderama9@gmail.com'; // Your Gmail address
            $mail->Password   = 'hlhx ifnf gdzk bdfn'; // Your 16-character App Password
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            
            // Timeout settings
            $mail->Timeout    = 30;
            $mail->SMTPKeepAlive = true;

            // Recipients
            $mail->setFrom('carlobalderama9@gmail.com', 'Aura Bay Resort');
            $mail->addAddress($email);
            $mail->addReplyTo('info@aurabay.com', 'Aura Bay Support');

            // Content
            $mail->isHTML(true);
            $mail->Subject = "ACTION REQUIRED: Confirm Your Stay at Aura Bay Resort - " . $room;
            
            $confirm_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/confirm.php?token=$token&action=confirm";
            $cancel_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/confirm.php?token=$token&action=cancel";
            
            $mail->Body = buildEmailTemplate($room, $price, $check_in, $check_out, $guests, $requests, $nights, $confirm_url, $cancel_url);
            
            // Plain text alternative
            $mail->AltBody = "Please confirm your reservation for $room at $price. Visit: $confirm_url to confirm or $cancel_url to cancel.";

            $mail->send();
            
            // Log success
            error_log("Email sent successfully to: $email for room: $room");
            
            echo json_encode(["status" => "Pending", "message" => "Booking created and email sent"]);
            
        } catch (Exception $e) {
            // Log error
            error_log("Email failed for $email: " . $mail->ErrorInfo);
            
            // Still return success since booking was saved, but notify about email issue
            echo json_encode([
                "status" => "Pending", 
                "message" => "Booking saved but email failed. Please contact support.",
                "error" => $mail->ErrorInfo
            ]);
        }
    } else {
        error_log("Database error: " . $conn->error);
        echo json_encode(["status" => "Error", "message" => "Database error: " . $conn->error]);
    }
}

function buildEmailTemplate($room, $price, $check_in, $check_out, $guests, $requests, $nights, $confirm_url, $cancel_url) {
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
        <title>Reservation Confirmation</title>
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
                                <p style='color: white; margin: 10px 0 0 0; font-size: 14px;'>Your Tropical Paradise Awaits</p>
                            </td>
                        </tr>
                        
                        <!-- Content -->
                        <tr>
                            <td style='padding: 40px 30px;'>
                                <h2 style='color: #1A3A2F; margin-top: 0; font-size: 22px;'>Reservation Pending Confirmation</h2>
                                <p style='color: #666; line-height: 1.6; font-size: 14px;'>Thank you for choosing Aura Bay Resort! Please review your booking details and confirm your reservation within 24 hours.</p>
                                
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
                                
                                <p style='color: #666; line-height: 1.6; font-size: 14px;'>Please confirm your reservation by clicking the button below. If you did not make this request or wish to cancel, click the cancel button.</p>
                                
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
                                            <p style='margin: 10px 0 0 0;'>If you have any questions, please contact us at <a href='mailto:info@aurabay.com' style='color: #2D5A4A;'>info@aurabay.com</a></p>
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

<?php
// ============================================
// FILE: login.php 
// ============================================
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if ($email === 'admin@gmail.com' && $password === 'admin123') {
        $_SESSION['user_email'] = $email;
        echo json_encode(["status" => "LoggedIn", "email" => $email]);
    } else {
        echo json_encode(["status" => "Error", "message" => "Invalid credentials"]);
    }
}
?> 

<?php
// ============================================
// FILE: logout.php 
// ============================================
session_start();
session_destroy();
echo json_encode(["status" => "LoggedOut"]);
?>