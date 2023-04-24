<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP & MariaDB Interaction</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        h1 {
            background-color: #2b2b2b;
            color: white;
            padding: 20px;
            margin-bottom: 40px;
        }

        form {
            background-color: white;
            padding: 20px;
            margin: 10px auto;
            max-width: 600px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
        }

        button {
            padding: 10px 20px;
            background-color: #2b2b2b;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4b4b4b;
        }
    </style>
</head>
<body>
    <h1>Interact with MariaDB Database</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br>
        <label for="height">How tall (in cm):</label>
        <input type="number" name="height" id="height" step="0.1" required><br>
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
$username = "aal";
$password = "063c2582b4cb827726d9018259156dd239a0deb06fce19cf757dd7ba0a898bcb";
$dbname = "alandb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to insert data into the database
function insertData($conn, $name, $height, $birthday) {
    $sql = "INSERT INTO people (name, height, birthday) VALUES ('$name', $height, '$birthday')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Function to read data from the database based on the search query
function searchData($conn, $search) {
    $sql = "SELECT id, name, height, birthday FROM people WHERE name LIKE '%$search%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"]. " - Name: " . $row["name"]. " - Height: " . $row["height"] . "cm - Birthday: " . $row["birthday"] . "<br>";
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
