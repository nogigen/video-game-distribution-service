<?php

//config inclusion session starts
include("config.php");
session_start();

//defining necessary variables
$username = "";
$password = "";


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
                <h1>Top Rated Games</h1>

                <form id="gameForm" action="" method="post">

                    
                    <?php
                        // Prepare a select statement
                        $query = "SELECT  game_id, game_name, game_genre, publisher_name, developer_name, game_price, AVG(review_score) as avg_review_score FROM game NATURAL JOIN updategame NATURAL JOIN developer NATURAL JOIN publishgame NATURAL JOIN publisher NATURAL JOIN review NATURAL JOIN personreview WHERE review.game_id = game.game_id GROUP BY game_id ORDER BY avg_review_score DESC";

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
                            <option value = \"filterStatus\">Approval Status</option>
                        </select>

                        </div>";

                        echo "<table class=\"table table-lg table-striped\" id=\"myTable\">
                            <tr>
                            <th>Game Name</th>
                            <th>Game Genre</th>
                            <th>Publisher Name</th>
                            <th>Developer Name</th>
                            <th>Price</th>
                            <th>Avg. Rating</th>
                            </tr>";

                        while($row = mysqli_fetch_array($result)) {

                            $avgRating = $row['avg_review_score'];

                            $avgRating = ($avgRating*100)/100;

                            if($avgRating == 0){
                                $avgRating = "Not Rated Yet";
                            }

                            echo "<tr>";
                            echo "<td>" . $row['game_name'] . "</td>";
                            echo "<td>" . $row['game_genre'] . "</td>";
                            echo "<td>" . $row['publisher_name'] . "</td>";
                            echo "<td>" . $row['developer_name'] . "</td>";
                            echo "<td>" . $row['game_price'] . "</td>";
                            echo "<td>" . $avgRating . "</td>";
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