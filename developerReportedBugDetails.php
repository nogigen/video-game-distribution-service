<?php

//config inclusion session starts
include("config.php");
session_start();

//defining necessary variables
$username = "";
$password = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {

    header("location: developerReportedBugs.php");
    
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
                    <h4 class="navbar-text">Developer <?php echo htmlspecialchars($_SESSION['developer_login_name']); ?></h4>

                </div>
                <a href="developerWelcome.php">Home</a>
                <a href="developerDevelopGame.php">Develop Game</a>
                <a href="developerPublishedGames.php">Published Games</a>
                <a href="developerCheckApproval.php">Check Approval</a>
                <a href="developerReportedBugs.php">Reported Bugs</a>
                <div class="navbar-right">
                    <a href="logout.php">Log Out</a>
                </div>
    </div>
            </div>
        </nav>
        <div id="centerwrapper">
            <div id="centerdiv">
            <br><br>
                <?php
                $gameName = $_SESSION['game_name'];

                $report_desc = $_SESSION['written_bug_report'];
                
                echo "<h2>Bug Report of $gameName</h2>";

                echo "<form id=\"reviewForm\" action=\"\" method=\"post\">

                <div class=\"form-group\">
                    <label>Review Description</label>
                    <textarea class=\"form-control\" name=\"reviewdesc\" id=\"reviewdesc\" rows=\"8\" value=\"$report_desc\" readonly=\"readonly\">$report_desc</textarea>
                    

                 </div>

                    <button type=\"submit\" onclick=\"checkEmpty()\" name = \"delete_report\"class=\"btn btn-primary btn-sm\">GO BACK</button>
                </form>";
                ?>
                
            </div>
        </div>
    </div>


    <script type="text/javascript">
        
    </script>
</body>
</html>