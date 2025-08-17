<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, class, roll, email FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students List</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 90%;
            max-width: 900px;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #a600ffff;
            color: white;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            color: #333;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #777;
        }
    </style>
</head>
<body>

<h2>Students List from Database</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Class</th>
        <th>Roll</th>
        <th>Email</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['class']}</td>
                    <td>{$row['roll']}</td>
                    <td>{$row['email']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5' class='no-data'>No data found</td></tr>";
    }

    $conn->close();
    ?>
</table>

</body>
</html>