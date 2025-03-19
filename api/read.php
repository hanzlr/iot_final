<?php
ob_start();
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');


include_once "../config/database.php";

if (!$conn) {
    error_log("Database connection failed.");
    echo "data: " . json_encode(["error" => "Database connection failed"]) . "\n\n";
    flush();
    exit();
}

$query = "SELECT * FROM sensor_data ORDER BY nomor DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result) {
    if ($row = mysqli_fetch_assoc($result)) {
        echo "data: " . json_encode($row) . "\n\n";
    } else {
        echo "data: " . json_encode(["error" => "No data found"]) . "\n\n";
    }
} else {
    echo "data: " . json_encode(["error" => "Query failed"]) . "\n\n";
}
flush();
