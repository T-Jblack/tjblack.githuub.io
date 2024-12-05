<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost and Found</title>
    <style>
        /* General body and layout styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        /* Header styles */
        header {
            background-image: url('https://images.pexels.com/photos/6969962/pexels-photo-6969962.jpeg');
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 3em;
        }

        header p {
            margin: 5px 0;
            font-size: 1.2em;
        }

        /* Navigation bar styles */
        nav {
            background-color: #333;
            overflow: hidden;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
            display: block;
        }

        nav ul li a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Media query for responsiveness */
        @media (max-width: 600px) {
            header h1 {
                font-size: 2em;
            }

            header p {
                font-size: 1em;
            }

            nav ul {
                flex-direction: column;
            }

            nav ul li a {
                padding: 12px 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <header>
        <h1> X Recovery System</h1>
        <p>Your community for finding lost items and reuniting with what matters most</p>
        <p>Be the change you want to see</p>
    </header>

    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="signin.php">Sign In</a></li>
            <li><a href="logout.php">Log Out</a></li>
            <li><a href="#contact">Contact Us</a></li>
        </ul>
    </nav>

</body>
</html>
