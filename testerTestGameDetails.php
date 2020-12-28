<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $reportId = $_SESSION['the_report_id'];

    if(isset($_POST['delete_report'])) {

        //DELETE FROM DEBUG
        $follow_insertion = "DELETE FROM debug WHERE report_id = '$reportId'";
        $result = mysqli_query($db,$follow_insertion);

        //DELETE FROM BUGREPORT
        $follow_insertion = "DELETE FROM bugreport WHERE report_id = '$reportId'";
        $result = mysqli_query($db,$follow_insertion);

        echo "<script LANGUAGE='JavaScript'>
                window.alert('Bug Report is successfully deleted.');
                window.location.href = 'testerTest.php'; 
                </script>";

    }
    
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
                    <h4 class="navbar-text">Tester <?php echo htmlspecialchars($_SESSION['tester_login_name']); ?></h4>
                </div>
                <a href="testerWelcome.php">Home</a>
                <a href="testerTest.php">Test Game</a>
                <a href="testerTestedGameHistory.php">Tested Games History</a>
                
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
                $testerId = $_SESSION['tester_id'];

                $report_desc = $_SESSION['written_bug_report'];
                
                echo "<h2>Bug Report of $gameName</h2>";

                echo "<form id=\"reviewForm\" action=\"\" method=\"post\">

                <div class=\"form-group\">
                    <label>Review Description</label>
                    <textarea class=\"form-control\" name=\"reviewdesc\" id=\"reviewdesc\" rows=\"8\" value=\"$report_desc\" readonly=\"readonly\">$report_desc</textarea>

                 </div>

                    <button type=\"submit\" onclick=\"checkEmpty()\" name = \"delete_report\"class=\"btn btn-danger btn-sm\">DELETE REPORT</button>
                </form>";
                ?>
                
            </div>
        </div>
    </div>


    <script type="text/javascript">
        
    </script>
</body>
</html>