<?php
 require_once "pdo.php";
 session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = filter_var($_POST["uname"], FILTER_SANITIZE_STRING);
    $password = $_POST["upass"];
    $_SESSION["uname"] = $uname;

    if (empty($uname) || empty($password)) {
        $_SESSION['fail'] = 'Username and password are required';
        header("Location: admin_signin.php");
        return;
    } 

    $check = $pdo->prepare('SELECT apassword FROM adminactual WHERE aname = :uname');
    $check->execute(array(':uname' => $uname));
    $hashedPasswordadmin = $check->fetchColumn();

    if ($hashedPasswordadmin && password_verify($password, $hashedPasswordadmin)){
        $_SESSION['success'] = "Login Successful!";
        $_SESSION['loggedin'] = true;
        header("Location: admin_view.php");
        return;

    } else{
        $_SESSION['fail'] = 'Incorrect username or password';
        header("Location: admin_signin.php");
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
                <label for="aname">Admin Username</label>
                <input type="text" id="email" name="uname" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="upass" placeholder="Enter your password" required>
            </div>

            
            <button type="submit" class="btn">Sign In</button>

        </form>
    </div>
    <?php
        include('footer.php')
    ?>
</body>
</html>


