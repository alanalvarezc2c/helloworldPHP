<?php
header('Content-Type: text/plain; charset=utf-8');

$servername = "localhost";
$username = "aal";
$password = "063c2582b4cb827726d9018259156dd239a0deb06fce19cf757dd7ba0a898bcb";
$dbname = "alandb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$qTotalRows = "SELECT COUNT(name) as total_rows FROM people";
$totalRowsResult = $conn->query($qTotalRows);

if ($totalRowsResult->num_rows > 0) {
    $row = $totalRowsResult->fetch_assoc();
    $total_rows = $row["total_rows"];
} else {
    $total_rows = 0;
}

$qMaxHeight = "SELECT MAX(height) as max_height FROM people";
$maxHeightResult = $conn->query($qMaxHeight);

if ($maxHeightResult->num_rows > 0) {
    $row = $maxHeightResult->fetch_assoc();
    $max_height = $row["max_height"];
} else {
    $max_height = 0;
}

$conn->close();

echo "# HELP total_rows_metric Total rows number of 'names'\n";
echo "# TYPE total_rows_metric gauge\n";
echo 'total_rows_metric{table="people", column="name"} ' . $total_rows . "\n";

echo "# HELP max_height_metric Maximum height value of 'people'\n";
echo "# TYPE max_height_metric gauge\n";
echo 'max_height_metric{table="people", column="height"} ' . $max_height . "\n";
?>
