<?php

//config inclusion session starts
include("config.php");
session_start();

//defining necessary variables
$gameName = "";
$gameGenre = "";
$gameDesc = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {

    header("location: publishedGames.php");
 
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

        h1 { 
        display: block;
        font-size: 3em;
        margin-top: 0.67em;
        margin-bottom: 0.67em;
        margin-left: 0;
        margin-right: 0;
        font-weight: bold;
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
                    <h4 class="navbar-text">Developer <?php echo htmlspecialchars($_SESSION['developer_login_name']); ?></h4>

                </div>
                <a href="developerWelcome.php">Home</a>
                <a href="developGame.php">Develop Game</a>
                <a href="publishedGames.php">Published Games</a>
                <div class="navbar-right">
                    <a href="logout.php">Log Out</a>
                </div>
    </div>
            </div>
            
        </nav>

        
        <div id="centerwrapper">
            <div id="centerdiv">
                <br><br>
                <h1>Update <?php echo htmlspecialchars($_SESSION['game_name']); ?> </h1>

                <form id="gameForm" action="" method="post">

                    <div class="form-group">
                        <label>Update Description</label>
                        <textarea class="form-control" name="gamedesc" id="gamedesc" rows="4"></textarea>

                    </div>
                    <div class="form-group">
                        <label>New Version No.</label>
                        <input type="text" name="gamegenre" class="form-control" id="gamegenre">

                    </div>
                    
                    <div class="form-group">
                        <input onclick="checkEmptyAndLogin()" class="btn btn-primary" value="Update">
                    </div>
                </form>  
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function checkEmpty() {
            var gamenameVal = document.getElementById("gamename").value;
            var gamedescVal = document.getElementById("gamedesc").value;
            var gamegenreVal = document.getElementById("gamegenre").value;
            if (gamenameVal === "" || gamedescVal === "" || gamegenreVal === "") {
                alert("FILL!");
            }
            else {

                var form = document.getElementById("gameForm").submit();
            }
        }
    </script>
</body>
</html>