<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $person_id = $_SESSION['person_id'];
    $gameName = $_POST['gamename'];
    $gamePrice = $_POST['gameprice'];

    if(isset($_POST['buy'])) {
        // get the game_id from game_name
        $queryGame = "SELECT game_id, latest_version_no FROM game WHERE game_name = '$gameName'";
        $res = mysqli_query($db, $queryGame);

        if(!$res) {
            printf("Error: %s\n", mysqli_error($db));
            exit();
        }
        $gameIdRow = mysqli_fetch_array($res);
        $gameId = $gameIdRow['game_id'];
        $latestVersionNo = $gameIdRow['latest_version_no'];

        // user's current credit
        $query = "SELECT credits FROM person WHERE person_id = " .$_SESSION['person_id'];
        $res = mysqli_query($db, $query);
        $row = mysqli_fetch_array($res);
        $credit = $row['credits'];

        if($credit >= $gamePrice) {
            // update the balance
            $newBalance = $credit - $gamePrice;
            $query = "UPDATE person SET credits = '$newBalance' WHERE person_id = '$person_id'";
            $res = mysqli_query($db, $query);

            if(!$res) {
                printf("Error: Updating balance %s\n", mysqli_error($db));
                exit();
            }
            // add game to user's library
            $query = "INSERT INTO has VALUES ('$person_id', '$gameId', 0, '$latestVersionNo')";
            $res = mysqli_query($db, $query);
            if(!$res) {
                printf("Error: Adding game to user's library. %s\n", mysqli_error($db));
                exit();
            }


        }
        else {
            echo "<script LANGUAGE='JavaScript'>
            window.alert('Not enough credit to buy the game.');
            </script>";
        }
        
        
    }
    else {
        echo "<script LANGUAGE='JavaScript'>
        window.alert('it should never come here :D.');
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
                    <h4 class="navbar-text">User <?php echo htmlspecialchars($_SESSION['nick_name']); ?></h4>

                </div>
                <a href="userWelcome.php">Home</a>
                <a href="userLibrary.php">Library</a>
                <a href="store.php">Store</a>
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
                <h1>All Games</h1>


                    
                    <?php
                        // Prepare a select statement
                        $query = "SELECT game_id, game_name, game_genre, game_price FROM game";

                        $result = mysqli_query($db, $query);

                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($db));
                            exit();
                        }

                        echo "<p><b>Current Games : </b></p>";
                       

                        echo "<table class=\"table table-lg table-striped\">
                            <tr>
                            <th>Game Name</th>
                            <th>Game Genre</th>
                            <th>Publisher Name</th>
                            <th>Developer Name</th>
                            <th>Credits</th>
                            </tr>";

                        while($hasRow = mysqli_fetch_array($result)) {
                            $gameId = $hasRow['game_id'];
                            $game_price = $hasRow['game_price'];
                            $game_name = $hasRow['game_name'];
                            $game_genre = $hasRow['game_genre'];

                            $queryPublisherId = "SELECT publisher_id FROM publishgame WHERE game_id = '$gameId'";
                            $result3 = mysqli_query($db, $queryPublisherId);
                            if(!$result3) {
                                printf("Error2: %s\n", mysqli_error($db));
                                exit();
                            }
                            $publisherIdRow = mysqli_fetch_array($result3);
                            $publisher_id = $publisherIdRow['publisher_id'];

                            $queryPublisherName = "SELECT publisher_name FROM publisher WHERE publisher_id = '$publisher_id'";
                            $result4 = mysqli_query($db, $queryPublisherName);
                            if(!$result4) {
                                printf("Error3: %s\n", mysqli_error($db));
                                exit();
                            }
                            $publisherNameRow = mysqli_fetch_array($result4);
                            $publisher_name = $publisherNameRow['publisher_name'];

                            
                            $queryDeveloperId = "SELECT developer_id FROM updategame WHERE game_id = '$gameId'";
                            $result5 = mysqli_query($db, $queryDeveloperId);

                            if(!$result5) {
                                printf("Error4: %s\n", mysqli_error($db));
                                exit();
                            }
                            $developerIdRow = mysqli_fetch_array($result5);
                            $developer_id = $developerIdRow['developer_id'];

                        
                            $queryDeveloperName = "SELECT developer_name FROM developer WHERE developer_id = '$developer_id'";
                            $result6 = mysqli_query($db, $queryDeveloperName);
                            
                            if(!$result6) {
                                printf("Error5: %s\n", mysqli_error($db));
                                exit();
                            }
                            $developerNameRow = mysqli_fetch_array($result6);
                            $developer_name = $developerNameRow['developer_name'];
                            
                            $person_id = $_SESSION['person_id'];
                            $query = "SELECT isInstalled FROM has WHERE person_id = '$person_id' and game_id = '$gameId'";
                            $res = mysqli_prepare($db, $query);
                            if(!$res) {
                                printf("Error: %s\n", mysqli_error($db));
                                exit();
                            }
                            mysqli_stmt_execute($res);
                            mysqli_stmt_store_result($res);
                            $numberOfRows = mysqli_stmt_num_rows($res);


                            echo "<form action=\"\" METHOD=\"POST\">";
                            echo "<tr>";
                            echo "<td><input type=\"hidden\" name=\"gamename\" value=". $game_name .">" . $game_name . "</td>";
                            echo "<td>" . $game_genre . "</td>";
                            echo "<td>" . $publisher_name . "</td>";
                            echo "<td>" . $developer_name . "</td>";
                            echo "<td><input type=\"hidden\" name=\"gameprice\" value=". $game_price .">" . $game_price . "</td>";
                            
                            if($numberOfRows == 0) {
                                echo "<td>
                                <button onclick=\"checkEmpty()\" name = \"buy\"class=\"btn btn-danger btn-sm\">BUY</button>
                                </td>";
                            }                   

                            echo "</tr>";
                            echo "</form>";
                        }

                        echo "</table>";
                        
                        ?>
          
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function checkEmpty() {

        }
    </script>
</body>
</html>