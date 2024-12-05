<?php 
   require_once "pdo.php";
    session_start();

   if(isset($_POST["cancel"])) {
        header('Location: index.php');
        return;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (strlen($_POST["password"]) < 8 || strlen($_POST["confirm-password"]) < 8) {
        $_SESSION['error'] = 'Password must be at least 8 characters long';
        header("Location: signup.php");
        return;

    }
    if ($_POST["password"] !== $_POST["confirm-password"]) {
        $_SESSION['fail'] = 'Passwords do not match';
        header("Location: signup.php");
        return;
    }

    $password = $_POST["password"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if (empty($_POST["name"]) || empty($_POST["email"]) 
        || empty($_POST["number"]) || empty($_POST["dob"]) 
      || empty($_POST["gender"]) || empty($_POST["password"])
        || empty($_POST["confirm-password"])) {
            

      $_SESSION["error"] =  "All fields are required"; 
      header('Location: signup.php');
      return; 

    } elseif (preg_match('/\d/', $_POST["name"])) { 
        $_SESSION["fail"] = "Name should not contain numbers"; 
        header('Location: signup.php');
        return; 

    } elseif (!preg_match('/^\d+$/', $_POST["number"])) { 
        $_SESSION["fail"] = "Phone number must contain only numeric digits"; 
        header('Location: signup.php');
        return; 
    
    } elseif (!preg_match('/^\d{11}$/', $_POST["number"])) {
        $_SESSION["fail"] = "Phone number must contain exactly 11 numeric digits.";
        header('Location: signup.php');
        return;

    } elseif (strpos($_POST["email"], '@') === false) {
        $_SESSION['fail'] = 'Email must have an at-sign (@)';
        header("Location: signup.php");
        return;  

    } elseif ($_POST["gender"] == "select") {
        $_SESSION["fail"] = "Please select a valid gender.";
        header('Location: signup.php');
        return;
    }
       
    $checkEmail = 'SELECT COUNT(*) FROM regis WHERE email = :email';
    $checkEmailStmt = $pdo->prepare($checkEmail);
    $checkEmailStmt->execute(array(':email' => $_POST['email']));
    $count = $checkEmailStmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['fail'] = 'Email already exists. Please choose a different email.';
            header("Location: signup.php");
            return;
        }        

       $enter = 'INSERT INTO regis (name, email, number, dob, gender, password) 
       VALUES (:name, :email, :number, :dob, :gender, :password)';
        $enterstmt = $pdo->prepare($enter);
        $enterstmt -> execute(array(
            ':name' => $_POST["name"],
            ':email' => $_POST["email"],
            ':number' => $_POST["number"],
            ':dob' => $_POST["dob"],
           ':gender' => $_POST["gender"],
            ':password' => $hashedPassword,

        ));
        $_SESSION['regis_id'] = $pdo->lastInsertId();
        $_SESSION['success'] = "Sign Up Successful, Proceed to Sign In";
        header('Location: signup.php');
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
        
            <legend>Personal Information</legend>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter Your Full Name">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email">

            <label for="number">Phone Number:</label>
            <input type="text" id="numb" name="number">    
            
            <label for="date of birth">Date of Birth</label>
            <input type="date" id="dob" name="dob">

            <label for="gender">Gender</label>
            <select id="gender" name="gender">
                <option value="select">Select...</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Others</option>
                <option value="prefer_not_to_say">Prefer not to say</option>
            </select>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter Strong Password">

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Enter Password Again to Confirm">

            <button type="submit">Submit</button> &nbsp;&nbsp;&nbsp; <a href="signup.php" style="text-decoration: none; color: gray;">Clear Field</a><br><br>
            <input type="submit" value="Cancel" name="cancel">
        </fieldset>
    </form>

</body>
</html>

<?php
    include('footer.php');
?>