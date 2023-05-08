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

        .centered-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .centered-text {
            text-align: center;
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
    <br>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <button type="submit" name="test_connection">Test Database Connection</button>
    </form>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <button type="submit" name="retrieve_all">Retrieve All Data</button>
    </form>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="delete_name">Delete by Name:</label>
        <select name="delete_name" id="delete_name">
            <?php
            $allData = getAllData($conn);
            foreach ($allData as $row) {
                echo "<option value=\"" . htmlspecialchars($row['name']) . "\">" . htmlspecialchars($row['name']) . "</option>";
            }
            ?>
        </select>
        <button type="submit" name="remove_data">Remove Data</button>
    </form>

    <?php
    // Database configuration
    $servername = "localhost";
    $username = "aal";
    $password = "063c2582b4cb827726d9018259156dd239a0deb06fce19cf757dd7ba0a898bcb";
    $dbname = "alandb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Function to insert data into the database
    function insertData($conn, $name, $height, $birthday)
    {
        $sql = "INSERT INTO people (name, height, birthday) VALUES ('$name', $height, '$birthday')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='centered-container'><div class='centered-text'>New record created successfully<br></div></div>";
        } else {
            echo "<div class='centered-container'><div class='centered-text'>Error: </div></div>" . $sql . "<br>" . $conn->error;
        }
    }

    // Function to test connection to database
    function testDbConnection($servername, $username, $password, $dbname)
    {
        $test_conn = new mysqli($servername, $username, $password, $dbname);
        if ($test_conn->connect_error) {
            echo "<div class='centered-container'><div class='centered-text'>Connection failed: " . $test_conn->connect_error . "</div></div>";
        } else {
            echo "<div class='centered-container'><div class='centered-text'>Connection to database was successful!</div></div>";
        }
        $test_conn->close();
    }

    // Function to read data from the database based on the search query
    function searchData($conn, $search)
    {
        $sql = "SELECT id, name, height, birthday FROM people WHERE name LIKE '%$search%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='centered-container'><div class='centered-text'>id: " . $row["id"] . " - Name: " . $row["name"] . " - Height: " . $row["height"] . "cm - Birthday: " . $row["birthday"] . "</div></div><br>";
            }
        } else {
            echo "<div class='centered-container'><div class='centered-text'>0 results</div></div>";
        }
    }

    // Function to read all data from the table people in the database
    function getAllData($conn)
    {
        global $conn;
        $sql = "SELECT id, name, height, birthday FROM people";
        $result = $conn->query($sql);

        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // Function to remove data from the table people in the database
    function removeDataByName($conn, $name)
    {
        $sql = "DELETE FROM people WHERE name = '$name'";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='centered-container'><div class='centered-text'>Record deleted successfully<br></div></div>";
        } else {
            echo "<div class='centered-container'><div class='centered-text'>Error: " . $sql . "<br>" . $conn->error . "</div></div>";
        }
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['insert'])) {
            $name = $_POST['name'];
            $height = $_POST['height'];
            $birthday = $_POST['birthday'];
            if (!empty($name) && !empty($height) && !empty($birthday)) {
                // Test inserting data into the database
                insertData($conn, $name, $height, $birthday);
            } else {
                echo "<div class='centered-container'><div class='centered-text'>Please fill in all fields.<br></div></div>";
            }
        } elseif (isset($_POST['display'])) {
            $search = $_POST['search'];
            if (!empty($search)) {
                // Test searching data from the database
                searchData($conn, $search);
            } else {
                echo "<div class='centered-container'><div class='centered-text'>Please enter a name to search.<br></div></div>";
            }
        } elseif (isset($_POST['retrieve_all'])) {
            $allData = getAllData($conn);
            foreach ($allData as $row) {
                echo "<div class='centered-container'><div class='centered-text'>id: " . $row["id"] . " - Name: " . htmlspecialchars($row["name"]) . " - Height: " . $row["height"] . "cm - Birthday: " . $row["birthday"] . "</div></div><br>";
            }
        } elseif (isset($_POST['remove_data'])) {
            $delete_name = $_POST['delete_name'];
            if (!empty($delete_name)) {
                removeDataByName($conn, $delete_name);
            } else {
                echo "<div class='centered-container'><div class='centered-text'>Please select a name to delete.<br></div></div>";
            }
        }

        // Test DB connection
        if (isset($_POST['test_connection'])) {
            testDbConnection($servername, $username, $password, $dbname);
        }
    }


    // Close the database connection
    $conn->close();
    ?>
</body>

</html>
