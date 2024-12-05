<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    $_SESSION['error'] = "Unauthorized access. Please log in first.";
    header("Location: admin_signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        #table {
            border: 7px double; 
            border-collapse: collapse;
            width: 100%;
        }

        #table th, #table td {
            border: 4px double; 
            padding: 8px;
            text-align: left;
        }

        #table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php
        require_once "header.php";
    ?>
<div class="container">
    <h2>Welcome <?php echo htmlentities($_SESSION['uname']); ?> to X-Recovery Database</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green">' . $_SESSION['success'] . "</p>\n";
        unset($_SESSION['success']);
    }

    $stmt = $pdo->query("
        SELECT 
            regis.name, 
            regis.number, 
            reports.type, 
            reports.category, 
            reports.description, 
            reports.date, 
            reports.location, 
            reports.image_path
        FROM reports
        JOIN regis ON reports.regis_id = regis.regis_id
        ORDER BY reports.date DESC
    ");

    echo '<table id="table">' . "\n";
    echo '<tr>
            <th>Name</th>
            <th>Number</th>
            <th>Type</th>
            <th>Category</th>
            <th>Description</th>
            <th>Date</th>
            <th>Location</th>
            <th>Image Path</th>
          </tr>';

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlentities($row['name']) . "</td>";
        echo "<td>" . htmlentities($row['number']) . "</td>";
        echo "<td>" . htmlentities($row['type']) . "</td>";
        echo "<td>" . htmlentities($row['category']) . "</td>";
        echo "<td>" . htmlentities($row['description']) . "</td>";
        echo "<td>" . htmlentities($row['date']) . "</td>";
        echo "<td>" . htmlentities($row['location']) . "</td>";
        echo "<td>" . htmlentities($row['image_path']) . "</td>";
        echo "<td><a href='delete.php?report_id=" . $row['report_id'] . "'>Delete</a></td>";
        echo "</tr>\n";
    }
    echo '</table>';
    ?>

</div>
<?php
        require_once "footer.php";
    ?>
</body>
</html>
