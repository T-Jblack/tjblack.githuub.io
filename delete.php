<?php
require_once "pdo.php";
session_start();

// Guardian: Make sure that report_id is present in the URL
if (!isset($_GET['report_id'])) {
    $_SESSION['error'] = "Missing report_id";
    header('Location: index.php');
    return;
}

// Prepare statement to fetch report details
$stmt = $pdo->prepare("SELECT * FROM reports WHERE report_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['report_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// If report not found, redirect with error
if ($row === false) {
    $_SESSION['error'] = 'Bad value for report_id';
    header('Location: index.php');
    return;
}

// If form is submitted, delete the record from the reports table
if (isset($_POST['delete']) && isset($_POST['report_id'])) {
    $sql = "DELETE FROM reports WHERE report_id = :report_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':report_id' => $_POST['report_id']));
    $_SESSION['success'] = 'Record deleted successfully';
    header('Location: index.php');
    return;
}

// Retrieve record details to display
$description = htmlentities($row['description']);
$report_id = $row['report_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Report</title>
</head>
<body>
    <h2>Confirm Deletion</h2>
    <p>Are you sure you want to delete the report: <?= $description ?>?</p>
    <form method="post">
        <input type="hidden" name="report_id" value="<?= $report_id ?>">
        <input type="submit" name="delete" value="Delete">
        <a href="index.php">Cancel</a>
    </form>
</body>
</html>
