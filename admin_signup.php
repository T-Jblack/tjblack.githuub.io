<?php 
   require_once "pdo.php";
    session_start();

   if(isset($_POST["cancel"])) {
        header('Location: admin_signin.php');
        return;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["aname"]) || empty($_POST["apassword"])) {
        $_SESSION["error"] = "All fields are required"; 
        header('Location: admin_signup.php');
        return;  
    }

    if (strlen($_POST["apassword"]) < 8) {
        $_SESSION['error'] = 'Password must be at least 8 characters long';
        header("Location: admin_signup.php");
        return;
    } 

    $passwordadmin = $_POST["apassword"];
    $hashedPasswordadmin = password_hash($passwordadmin, PASSWORD_DEFAULT);

    
       
    $checkEmailadmin = 'SELECT COUNT(*) FROM adminactual WHERE aname = :aname';
    $checkEmailStmtadmin = $pdo->prepare($checkEmailadmin);
    $checkEmailStmtadmin->execute(array(':aname' => $_POST['aname']));
    $countadmin = $checkEmailStmtadmin->fetchColumn();

        if ($countadmin > 0) {
            $_SESSION['fail'] = 'Admin Username already exists. Please choose a different username.';
            header("Location: admin_signup.php");
            return;
        }        

       $enteradmin = 'INSERT INTO adminactual (aname, apassword) 
       VALUES (:aname, :apassword)';
        $enterstmtadmin = $pdo->prepare($enteradmin);
        $enterstmtadmin -> execute(array(
            ':aname' => $_POST["aname"],
            ':apassword' => $hashedPasswordadmin,

        ));
        $_SESSION['success'] = "Sign Up Successful, Proceed to Sign In";
        header('Location: admin_signin.php');
        return;

}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account</title>
    <link rel="stylesheet" href="style1.css">
    <script src="script.js"></script>
</head>
<body>
<?php
       include('header.php'); 
?>

   
    <form method="POST">
                    <div>
                            <p> <?php
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
   
        <fieldset>
        
            <legend>Admin Information</legend>
            <label for="name">Admin Username:</label>
            <input type="text" id="name" name="aname" placeholder="Enter Username">


            <label for="password">Password:</label>
            <input type="password" id="password" name="apassword" placeholder="Enter Strong Password">

            <button type="submit">Submit</button> &nbsp;&nbsp;&nbsp; <a href="admin_signup.php" style="text-decoration: none; color: gray;">Clear Field</a><br><br>
            <input type="submit" value="Cancel" name="cancel">
        </fieldset>
    </form>

</body>
</html>

<?php
    include('footer.php');
?>