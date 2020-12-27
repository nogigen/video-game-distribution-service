<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $gameName = $_SESSION['game_name'];
    $testerId = $_SESSION['tester_id'];
    $developerId =  $_SESSION['developer_id'];

    $bugReport = mysqli_real_escape_string($db, $_POST['bugreport']);

    if($bugReport == ""){ 

        echo "<script LANGUAGE='JavaScript'>
        window.alert('Please fill the Bug Report');
        window.location.href = 'testerTestGame.php'; 
        </script>";

    }

    else{ //SUCCESS CASE

        //GET GAME ID
        $queryGameId = "SELECT game_id FROM game WHERE game_name = '$gameName'";
        $result = mysqli_query($db, $queryGameId);
        $gameIdRow = mysqli_fetch_array($result);
        $gameId = $gameIdRow['game_id'];

        //INSERT INTO BUGREPORT TABLE
        $insert_report = "INSERT INTO bugreport(report_description) VALUES ('$bugReport')";
        $result = mysqli_query($db,$insert_report);

        //GET REPORT ID
        $queryReportId = "SELECT MAX(report_id) as report_id FROM bugreport";
        $result = mysqli_query($db, $queryReportId);
        $reportIdRow = mysqli_fetch_array($result);
        $reportId = $reportIdRow['report_id'];

        //INSERT INTO DEBUG TABLE
        $insert_debug = "INSERT INTO debug VALUES ('$reportId', '$testerId', '$gameId', '$developerId')";
        $result = mysqli_query($db,$insert_debug);


        $_SESSION['bug_report'] = $bugReport;
        $_SESSION['the_report_id'] = $reportId;

        
        echo "<script LANGUAGE='JavaScript'>
        window.alert('Report has been sent to the Publisher');
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
                <h1>Bug Report of <?php echo htmlspecialchars($_SESSION['game_name']); ?> </h1>

                <form id="updateForm" action="" method="post">

                    <div class="form-group">
                        <label>Bug Report</label>
                        <textarea class="form-control" name="bugreport" id="bugreport" rows="4"></textarea>

                    </div>
                    
                    <div class="form-group">
                        <input type = "submit" onclick="checkEmpty()" class="btn btn-primary" value="Send Bug Report">
                    </div>
                </form>  
            </div>
        </div>
    </div>


    <script type="text/javascript">
        
    </script>
</body>
</html>