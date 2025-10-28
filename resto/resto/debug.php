<?php
// debug.php - untuk melihat error detail
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

echo "<h2>Debug Info</h2>";

// Test koneksi database
$host = "localhost";
$username = "root"; 
$password = "";
$database = "toko_encang";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo "❌ Database Error: " . $conn->connect_error;
} else {
    echo "✅ Database Connected<br>";
}

// Test session
echo "Session Status: " . (isset($_SESSION['cart']) ? 'Cart exists' : 'No cart') . "<br>";

// Test POST data
if ($_POST) {
    echo "POST Data: ";
    print_r($_POST);
}
?>