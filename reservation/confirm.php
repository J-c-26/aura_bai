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