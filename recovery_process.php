<?php
require_once "pdo.php";
session_start();

if (!isset($_SESSION['regis_id']) || !$_SESSION['regis_id']) {
    $_SESSION['error'] = "Unauthorized access. Please log in first.";
    header("Location: signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? null;
    $category = $_POST['category'] ?? null;
    $description = $_POST['description'] ?? null;
    $date = $_POST['date'] ?? null;
    $location = $_POST['location'] ?? null;

    if ($type && $category && $description && $date && $location) {
        try {
            $uploadDir = 'uploads/'; 
            $uploadedFilePath = null;

            $allowedTypes = ['image/jpeg', 'image/png'];


            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imageName = $_FILES['image']['name'];
                $imageTmpName = $_FILES['image']['tmp_name'];
                $uploadedFilePath = $uploadDir . basename($imageName);

                if (!move_uploaded_file($imageTmpName, $uploadedFilePath)) {
                    $_SESSION['error'] = "Failed to upload the image.";
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                }
            }

            $stmt = $pdo->prepare(
                "INSERT INTO reports (regis_id, type, category, description, date, location, image_path) 
                 VALUES (:regis_id, :type, :category, :description, :date, :location, :image_path)"
            );

            $stmt->bindParam(':regis_id', $_SESSION['regis_id']);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':image_path', $uploadedFilePath);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Report submitted successfully.";
            } else {
                $_SESSION['error'] = "Failed to submit the report.";
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "All fields are required.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recovery Process</title>
   <link rel="stylesheet" href="recovery_style.css"> 
   <script src="script1.js"></script>
</head>
<body>
    <?php require_once "header.php"; ?>
        
<main>
    <section id="report-lost">
        <h2>Report Lost or Found Items</h2>
        <?php
        if (isset($_SESSION['success'])) {
            echo "<p class='success-message'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<p class='error-message'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>
        <form id="report-form" action="" method="POST" enctype="multipart/form-data">
            <label for="type">Type:</label>
            <select name="type" id="type" required>
                <option value="lost">Lost</option>
                <option value="found">Found</option>
            </select>
            <label for="category">Category:</label>
            <input type="text" name="category" id="category" required>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
            <label for="date">Date:</label>
            <input type="date" name="date" id="date" required>
            <label for="location">Location:</label>
            <input type="text" name="location" id="location" required>
            <label for="image">Upload Image:</label>
            <input type="file" name="image" id="image">
            <button type="submit">Submit Report</button>
        </form>
    </section>
</main>

<?php require_once "footer.php"; ?>
</body>
</html>
