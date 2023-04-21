<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP & MariaDB Interaction</title>
</head>
<body>
    <h1>Interact with MariaDB Database</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br>
        <label for="age">Age:</label>
        <input type="number" name="age" id="age" required><br>
        <label for="birthday">Birthday:</label>
        <input type="date" name="birthday" id="birthday" required><br>
        <button type="submit" name="insert">Insert Data</button>
    </form>
    <br>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="search">Search by Name:</label>
        <input type="text" name="search" id="search" required>
        <button type="submit" name="display">Search</button>
    </form>

<?php
// Database configuration
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to insert data into the database
function insertData($conn, $name, $age, $birthday) {
    $sql = "INSERT INTO people (name, age, birthday) VALUES ('$name', $age, '$birthday')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Function to read data from the database based on the search query
function searchData($conn, $search) {
    $sql = "SELECT id, name, age, birthday FROM people WHERE name LIKE '%$search%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"]. " - Name: " . $row["name"]. " - Age: " . $row["age"] . " - Birthday: " . $row["birthday"] . "<br>";
        }
    } else {
        echo "0 results";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['insert'])) {
        $name = $_POST['name'];
        $age = $_POST['age'];
        $birthday = $_POST['birthday'];
        if (!empty($name) && !empty($age) && !empty($birthday)) {
            // Test inserting data into the database
            insertData($conn, $name, $age, $birthday);
        } else {
            echo "Please fill in all fields.<br>";
        }
    } elseif (isset($_POST['display'])) {
        $search = $_POST['search'];
        if (!empty($search)) {
            // Test searching data from the database
            searchData($conn, $search);
        } else {
            echo "Please enter a name to search.<br>";
        }
    }
}

// Close the database connection
$conn->close();
?>
</body>
</html>
