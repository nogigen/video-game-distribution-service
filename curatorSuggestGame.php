<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION['game_name'] = $_POST['review_button'];
    header("location: curatorReview.php");
    
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
                <a href="curatorSuggestGame.php">Suggest Game</a>

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
                        $query = "SELECT  game_name, game_genre, game_desc, publisher_name, developer_name FROM game NATURAL JOIN publishGame NATURAL JOIN publisher NATURAL JOIN updateGame NATURAL JOIN developer";

                        $result = mysqli_query($db, $query);

                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($db));
                            exit();
                        }

                        echo "<table class=\"table table-lg table-striped\">
                            <tr>
                            <th>Game Name</th>
                            <th>Genre</th>
                            <th>Game Description</th>
                            <th>Publisher Name</th>
                            <th>Developer Name</th>
                            <th>        </th>
                            </tr>";

                        while($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['game_name'] . "</td>";
                            echo "<td>" . $row['game_genre'] . "</td>";
                            echo "<td>" . $row['game_desc'] . "</td>";
                            echo "<td>" . $row['publisher_name'] . "</td>";
                            echo "<td>" . $row['developer_name'] . "</td>";
                            echo "<td>
                                <button onclick=\"cancelled()\" name = \"review_button\"class=\"btn btn-success btn-sm\" value =".$row['game_name'] .">REVIEW</button>
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