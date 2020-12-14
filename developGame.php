<?php

//config inclusion session starts
include("config.php");
session_start();

//defining necessary variables
$gameName = "";
$gameGenre = "";
$gameDesc = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {


    $publisherId = $_POST['selected_publisher_id'];

    $developerId = $_SESSION["developer_id"];


    $gameName = $_POST["gamename"];
    $gameGenre = $_POST["gamegenre"];
    $gameDesc = $_POST["gamedesc"];

    echo "<script LANGUAGE='JavaScript'>
        window.alert('$gameName');
        </script>";

    
    $sql = "INSERT INTO ask(publisher_id, developer_id, ask_game_name, ask_game_genre, ask_game_desc) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "iisss", $publisherId, $developerId, $gameName, $gameGenre, $gameDesc);
    mysqli_stmt_execute($stmt);
    header("location: developerWelcome.php");
    
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
                <h1>Game Specification</h1>

                <form id="gameForm" action="" method="post">

                    <div class="form-group">
                        <label>Game Name</label>
                        <input type="text" name="gamename" class="form-control" id="gamename">

                    </div>
                    <div class="form-group">
                        <label>Game Description</label>
                        <textarea class="form-control" name="gamedesc" id="gamedesc" rows="4"></textarea>

                    </div>
                    <div class="form-group">
                        <label>Game Genre</label>
                        <input type="text" name="gamegenre" class="form-control" id="gamegenre">

                    </div>
                    
                    <?php
                        // Prepare a select statement
                        $query = "SELECT publisher_login_name, publisher_id FROM publisher";

                        echo "<p><b>Available Publishers:</b></p>";

                        $result = mysqli_query($db, $query);

                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($db));
                            exit();
                        }

                        echo "<table class=\"table table-lg table-striped\">
                            <tr>
                            <th>Publisher Name</th>
                            <th>Publisher ID</th>
                            <th>Option</th>
                            </tr>";

                        while($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['publisher_login_name'] . "</td>";
                            echo "<td>" . $row['publisher_id'] . "</td>";
                            echo "<td> <form action=\"\" METHOD=\"POST\">
                                    <button onclick=\"checkEmpty()\" name = \"selected_publisher_id\"class=\"btn btn-success btn-sm\"  value =".$row['publisher_id'].">SELECT</button>
                                    </form>
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