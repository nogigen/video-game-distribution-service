<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {
    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        #centerwrapper { text-align: center; margin-bottom: 10px; }
        #centerdiv { display: inline-block; }
        /* Navbar container */
        .navbar {
        overflow: hidden;
        background-color: #333;
        font-family: Arial;
        }

        /* Links inside the navbar */
        .navbar a {
        float: left;
        font-size: 16px;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        }
        
        /* Add a red background color to navbar links on hover */
        .navbar a:hover, .dropdown:hover .dropbtn {
        background-color: black;
        }

    </style>
</head>
<body>
    
    <div class="container">
        
        <nav class="navbar navbar-inverse bg-primary navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <h4 class="navbar-text">Curator <?php echo htmlspecialchars($_SESSION['curator_login_name']); ?></h4>
                </div>
                <a href="curatorWelcome.php">Home</a>
                <?php
                    $query = "SELECT no_of_followers FROM curator WHERE curator_id = " .$_SESSION['curator_id'];
                    $res = mysqli_query($db, $query);
                    $row = mysqli_fetch_array($res);
                    $followers = $row['no_of_followers'];
                    echo "<a href='showFollowers.php'>Followers : $followers</a>";
                ?>

                
                <div class="navbar-right">

                    <a href="logout.php">Log Out</a>
                </div>
    </div>
            </div>
        </nav>
        <div id="centerwrapper">
            <div id="centerdiv">
                
            </div>
        </div>
    </div>


    <script type="text/javascript">
        
    </script>
</body>
</html>