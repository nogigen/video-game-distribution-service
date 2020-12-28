<?php

//config inclusion session starts
include("config.php");
session_start();

//defining necessary variables
$username = "";
$password = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['read_bug_report_button'])) {

        $reportId = $_POST['read_bug_report_button'];

        // GET GAME ID
        $queryGame = "SELECT game_id FROM debug WHERE report_id = '$reportId'";
        $res = mysqli_query($db, $queryGame);
        $gameRow = mysqli_fetch_array($res);
        $gameId = $gameRow['game_id'];

        //GET GAME NAME
        $queryGame = "SELECT game_name FROM game WHERE game_id = '$gameId'";
        $res = mysqli_query($db, $queryGame);
        $gameRow = mysqli_fetch_array($res);
        $gameName = $gameRow['game_name'];

        //GET SPECIFIC BUG REPORT DESCRIPTION
        $queryGame = "SELECT report_description FROM bugreport WHERE report_id = '$reportId'";
        $res = mysqli_query($db, $queryGame);
        $gameRow = mysqli_fetch_array($res);
        $reportDescription = $gameRow['report_description'];

        $_SESSION['game_name'] = $gameName;

        $_SESSION['written_bug_report'] = $reportDescription;

        $_SESSION['the_report_id'] = $reportId;

        header("location: developerReportedBugDetails.php");

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
                <h1>List of Reported Bugs</h1>

                <form id="gameForm" action="" method="post">

                    <?php
                        // Prepare a select statement
                        $developerId = $_SESSION['developer_id'];
                        $query = "SELECT  game_name, game_genre, game_desc, publisher_name, tester_login_name, report_id, report_description FROM game NATURAL JOIN publishGame NATURAL JOIN publisher NATURAL JOIN developer NATURAL JOIN debug NATURAL JOIN bugreport NATURAL JOIN tester WHERE developer_id = '$developerId'";

                        $result = mysqli_query($db, $query);

                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($db));
                            exit();
                        }

                        echo "<div class=\"form-group\">
                        <input type=\"text\" id=\"myInput\" onkeyup=\"myFunction()\" placeholder=\"Search for value & col type..\">
                        <select id = \"filterType\">
                            <option value =\"filterGameName\" selected=\"selected\">Game Name</option>
                            <option value = \"filterGameGenre\">Game Genre</option>
                            <option value = \"filterPublisherName\">Publisher Name</option>
                            <option value = \"filterDeveloperName\">Developer Name</option>
                        </select>
    
                        </div>";

                        echo "<table class=\"table table-lg table-striped\" id=\"myTable\">
                            <tr>
                            <th>Game Name</th>
                            <th>Game Genre</th>
                            <th>Publisher Name</th>
                            <th>Tester Name</th>
                            <th>Bug Report ID</th>
                            <th>        </th>
                            </tr>";

                        while($row = mysqli_fetch_array($result)) {
                            
                            echo "<tr>";
                            echo "<td>" . $row['game_name'] . "</td>";
                            echo "<td>" . $row['game_genre'] . "</td>";
                            echo "<td>" . $row['publisher_name'] . "</td>";
                            echo "<td>" . $row['tester_login_name'] . "</td>";
                            echo "<td>" . $row['report_id'] . "</td>";
                            
                            echo "<td>
                            <button onclick=\"cancelled()\" name = \"read_bug_report_button\"class=\"btn btn-primary btn-sm\" value =".$row['report_id'] .">READ BUG REPORT</button>
                            </td>";
                                    
  
                            echo "</tr>";
                        }

                        echo "</table>";
                        ?>
                </form>  
                
            </div>
        </div>
    </div>


    <script type="text/javascript">
        
    </script>
</body>
</html>