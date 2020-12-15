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


    $gameName = $_POST['gamename'];
    $gameGenre = $_POST['gamegenre'];
    $gameDesc = $_POST['gamedesc'];

    
    $sql = "INSERT INTO ask(publisher_id, developer_id, ask_game_name, ask_game_genre, ask_game_desc) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "iisss", $publisherId, $developerId, $gameName, $gameGenre, $gameDesc);
    mysqli_stmt_execute($stmt);
    //header("location: developerWelcome.php");
    
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
                    <h4 class="navbar-text">User <?php echo htmlspecialchars($_SESSION['person_id']); ?></h4>

                </div>
                <a href="userWelcome.php">Home</a>
                <a href="userLibrary.php">Library</a>
                <?php
                    $query = "SELECT credits FROM person WHERE person_id = " .$_SESSION['person_id'];
                    $res = mysqli_query($db, $query);
                    $row = mysqli_fetch_array($res);
                    $credit = $row['credits'];
                    echo "<a href='userCredits.php'>Credit : $credit TL </a>";
                ?>
                <div class="navbar-right">
                    <a href="logout.php">Log Out</a>
                </div>
    </div>
            </div>
            
        </nav>

        
        <div id="centerwrapper">
            <div id="centerdiv">
                <br><br>
                <h1>Bought Games</h1>


                    
                    <?php
                        // Prepare a select statement
                        $query = "SELECT person_id, game_id, isInstalled, personVersion FROM has WHERE person_id = " .$_SESSION['person_id'];


                        echo "<p><b>Your Games : </b></p>";

                        $result = mysqli_query($db, $query);

                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($db));
                            exit();
                        }

                        echo "<table class=\"table table-lg table-striped\">
                            <tr>
                            <th>Game Name</th>
                            <th>Game Genre</th>
                            <th>Publisher Name</th>
                            <th>Developer Name</th>
                            </tr>";

                        while($hasRow = mysqli_fetch_array($result)) {
                            $gameId = $hasRow['game_id'];
                            $isInstalled = $hasRow['isInstalled'];
                            $personVersion = $hasRow['personVersion'];


                            $queryGame = "SELECT game_name, game_genre FROM game WHERE game_id = " .$gameId;
                            $result2 = mysqli_query($db, $queryGame);
                            if(!$result2) {
                                printf("Error1: %s\n", mysqli_error($db));
                                exit();
                            }
                            $gameRow =  mysqli_fetch_array($result2);
                            $game_name = $gameRow['game_name'];
                            $game_genre = $gameRow['game_genre'];
                            
                            $queryPublisherId = "SELECT publisher_id FROM publishgame WHERE game_id = " .$gameId;
                            $result3 = mysqli_query($db, $queryPublisherId);
                            if(!$result3) {
                                printf("Error2: %s\n", mysqli_error($db));
                                exit();
                            }
                            $publisherIdRow = mysqli_fetch_array($result3);
                            $publisher_id = $publisherIdRow['publisher_id'];

                            $queryPublisherName = "SELECT publisher_name FROM publisher WHERE publisher_id = " .$publisher_id;
                            $result4 = mysqli_query($db, $queryPublisherName);
                            if(!$result4) {
                                printf("Error3: %s\n", mysqli_error($db));
                                exit();
                            }
                            $publisherNameRow = mysqli_fetch_array($result4);
                            $publisher_name = $publisherNameRow['publisher_name'];


                            $queryDeveloperId = "SELECT developer_id FROM updateGame WHERE game_id = " .$gameId;
                            $result5 = mysqli_query($db, $queryDeveloperId);
                            if(!$result5) {
                                printf("Error4: %s\n", mysqli_error($db));
                                exit();
                            }
                            $developerIdRow = mysqli_fetch_array($result5);
                            $developer_id = $developerIdRow['developer_id'];

                            $queryDeveloperName = "SELECT developer_name FROM developer WHERE developer_id = " .$developer_id;
                            $result6 = mysqli_query($db, $queryDeveloperName);
                            if(!$result6) {
                                printf("Error5: %s\n", mysqli_error($db));
                                exit();
                            }
                            $developerNameRow = mysqli_fetch_array($result6);
                            //$developer_name = $developerNameRow['developer_name'];


                            echo "<tr>";
                            echo "<td>" . $game_name . "</td>";
                            echo "<td>" . $game_genre . "</td>";
                            echo "<td>" . $publisher_name . "</td>";
                            echo "<td>" . $developer_name . "</td>";

                            /*echo "<td> 
                                    <button type=\"submit\" onclick=\"checkEmpty()\" name = \"selected_publisher_id\"class=\"btn btn-success btn-sm\"  value =".$row['publisher_id'].">SELECT</button>
                                </td>";*/
                            echo "</tr>";
                        }

                        echo "</table>";
                        ?>
          
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