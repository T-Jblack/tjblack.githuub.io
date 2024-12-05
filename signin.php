<?php
 require_once "pdo.php";
 session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = strtolower(filter_var($_POST["email1"], FILTER_SANITIZE_EMAIL));
    $password = $_POST["pass"];
    $_SESSION["email"] = $_POST["email1"];

    if (empty($email) || empty($password)) {
        $_SESSION['fail'] = 'Email and password are required';
        header("Location: signin.php");
        return;
    } elseif (strpos($_POST['email1'], '@') === false) {
        $_SESSION['fail'] = 'Email must have an at-sign (@)';
        header("Location: signin.php");
        return;
    } 

    $check = $pdo->prepare('SELECT regis_id, password FROM regis WHERE email = :email');
    $check->execute(array(':email' => $email));
    $row = $check->fetch(PDO::FETCH_ASSOC);
    
    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['success'] = "Login Successful!";
        $_SESSION['regis_id'] = true;
        $_SESSION['regis_id'] = $row['regis_id']; 
        header("Location: recovery_process.php");
        return;
    } else {
        $_SESSION['fail'] = 'Incorrect email or password';
        header("Location: signin.php");
        return;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - X Recovery System</title>
    <link rel="stylesheet" href="style2.css"> 
</head>
<body>

    <?php
        include('header.php');
    ?>

    <div class="form-container">
        <h1>Sign In</h1>
        <form method="POST"> 
            <div>
                <p> 
                    <?php
                                        if (isset($_SESSION['error'])) {
                                            echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
                                            unset($_SESSION['error']);
                                        }
                                        if (isset($_SESSION['fail'])) {
                                            echo ('<p style="color: red;">' . htmlentities($_SESSION['fail']) . "</p>\n");
                                            unset($_SESSION['fail']);
                                        }
                                        if (isset($_SESSION['success'])) {
                                            echo ('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");
                                            unset($_SESSION['success']);
                                        }
                    ?>

                </p>
            </div>    
           
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email1" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="pass" placeholder="Enter your password" required>
            </div>

            
            <button type="submit" class="btn">Sign In</button>

            
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </form>
    </div>
    <?php
        include('footer.php')
    ?>
</body>
</html>


