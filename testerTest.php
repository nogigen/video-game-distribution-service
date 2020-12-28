<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['test_button'])) {

        $_SESSION['game_name'] = $_POST['test_button'];

        header("location: testerTestGame.php");

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
                <h1>List of Games</h1>

                <form id="gameForm" action="" method="post">

                    <?php
                        // Prepare a select statement
                        $query = "SELECT  game_name, game_genre, game_desc, publisher_name, developer_id, developer_name FROM game NATURAL JOIN publishGame NATURAL JOIN publisher NATURAL JOIN updateGame NATURAL JOIN developer";

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
                            <th>Developer Name</th>
                            <th>        </th>
                            </tr>";

                        while($row = mysqli_fetch_array($result)) {

                            $testerId = $_SESSION['tester_id'];
                            $_SESSION['developer_id'] = $row['developer_id'];


                            $is_ever_tested = "SELECT game_id FROM game NATURAL JOIN debug NATURAL JOIN BugReport";
                            $res = mysqli_prepare($db, $is_ever_tested);
                            mysqli_stmt_execute($res);
                            mysqli_stmt_store_result($res);
                            $numberOfRows = mysqli_stmt_num_rows($res);

                            $isTested = TRUE;

                            if($numberOfRows == 0){
                                $isTested = FALSE;
                            }


                            echo "<tr>";
                            echo "<td>" . $row['game_name'] . "</td>";
                            echo "<td>" . $row['game_genre'] . "</td>";
                            echo "<td>" . $row['publisher_name'] . "</td>";
                            echo "<td>" . $row['developer_name'] . "</td>";

                            
                            echo "<td>
                            <button onclick=\"cancelled()\" name = \"test_button\"class=\"btn btn-success btn-sm\" value ='".$row['game_name'] ."'>WRITE BUG REPORT</button>
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