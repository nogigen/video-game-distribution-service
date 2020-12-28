<?php

//config inclusion session starts
include("config.php");
session_start();


if(isset($_POST['submit'])) {


    $valueToSearch = mysqli_real_escape_string($db,$_POST['valueToSearch']);
    $filterType = mysqli_real_escape_string($db,$_POST['filter']);

    $query = "SELECT game_id, game_name, game_genre, game_price, developer_name, publisher_name
              FROM game NATURAL JOIN publishgame NATURAL JOIN publisher NATURAL JOIN updategame NATURAL JOIN developer
              WHERE $filterType LIKE '%$valueToSearch%'";

    $resulted_query = mysqli_query($db, $query);

    

    if(!$resulted_query) {
        printf("Error: filtering table. %s\n", mysqli_error($db));
        exit();
    }
}

else if(isset($_POST['creditFilter'])) {
    $lowerThan =  mysqli_real_escape_string($db,$_POST['lowerThan']);
    $greaterThan =  mysqli_real_escape_string($db,$_POST['greaterThan']);


    if($lowerThan == "" || $greaterThan == "") {

        $query = "SELECT game_id, game_name, game_genre, game_price, developer_name, publisher_name
        FROM game NATURAL JOIN publishgame NATURAL JOIN publisher NATURAL JOIN updategame NATURAL JOIN developer";

        $resulted_query = mysqli_query($db, $query);
        echo "<script LANGUAGE='JavaScript'>
        window.alert('You need to give inputs.');
        </script>";
    }
    else {
        $query = "SELECT game_id, game_name, game_genre, game_price, developer_name, publisher_name
        FROM game NATURAL JOIN publishgame NATURAL JOIN publisher NATURAL JOIN updategame NATURAL JOIN developer
        WHERE game_price > '$greaterThan' and game_price < '$lowerThan'";

        $resulted_query = mysqli_query($db, $query);
    }
}

else {

    $query = "SELECT game_id, game_name, game_genre, game_price, developer_name, publisher_name
              FROM game NATURAL JOIN publishgame NATURAL JOIN publisher NATURAL JOIN updategame NATURAL JOIN developer";

    $resulted_query = mysqli_query($db, $query);

}

if($_SERVER["REQUEST_METHOD"] == "POST") {


    if(isset($_POST['buy'])) {
        $gameName = $_POST['gamename'];

        // get the game_id from game_name
        $queryGame = "SELECT game_id, latest_version_no FROM game WHERE game_name = '$gameName'";
        $res = mysqli_query($db, $queryGame);

        if(!$res) {
            printf("Error: %s\n", mysqli_error($db));
            exit();
        }
        $gameIdRow = mysqli_fetch_array($res);
        $gameId = $gameIdRow['game_id'];

        $_SESSION['selected_game_id'] = $gameId;

        header("location: userStoreDisplay.php");

    }
    else if(isset($_POST['gift'])) {
        $gameName = $_POST['gamename'];
        
        $queryGame = "SELECT game_id, latest_version_no FROM game WHERE game_name = '$gameName'";
        $res = mysqli_query($db, $queryGame);

        if(!$res) {
            printf("Error: %s\n", mysqli_error($db));
            exit();
        }
        $gameIdRow = mysqli_fetch_array($res);
        $gameId = $gameIdRow['game_id'];

        $_SESSION['selected_game_id'] = $gameId;

        header("location: userGiftGame.php");

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
                <a href="userStore.php">Store</a>
                <a href="userCheckUpdates.php">Update</a>
                <a href="userCheckMods.php">Mods</a>
                <a href="userFollowCurators.php">Curator</a>
                <a href="userRefund.php">Refund</a>
                <a href="userRefundHistory.php">Refund History</a>
                <a href="userShopHistory.php">Shop History</a>
                <a href="userReview.php">Review</a>
                <a href="userReceivedFriendRequests.php">Received Friend Requests</a>
                <a href="userSendFriendRequests.php">Add Friend</a>
                <a href="userSentFriendRequests.php">Sent Friend Requests</a>
                <a href="userFriends.php">Friends</a>


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
        
                        echo "<p><b>Current Games : </b></p>";


                       echo "<form method=\"post\">
                            <div class=\"form-group\">
                            <input type=\"text\" name=\"valueToSearch\" placeholder=\"Search for value & col type..\">
                            <select id = \"filterType\" name=\"filter\">
                                <option value =\"game_name\" selected=\"selected\">Game Name</option>
                                <option value = \"game_genre\">Game Genre</option>
                                <option value = \"publisher_name\">Publisher Name</option>
                                <option value = \"developer_name\">Developer Name</option>
                                <option value = \"game_price\">Credits</option>
                            </select>
                            <input type = \"submit\"  name=\"submit\" value=\"Filter\">
                            </div>";

                        
                        echo "<div class=\"form-group\">
                            
                             <input type=\"text\" name=\"greaterThan\" placeholder=\"Price greater than..\">
                             <input type=\"text\" name=\"lowerThan\" placeholder=\"Price lower than..\">
                             <input type = \"submit\" name=\"creditFilter\" value=\"Filter\">
                            
                            </div>";
                       

                       
                        echo "<table class=\"table table-lg table-striped\" id=\"myTable\">
                            <tr>
                            <th>Game Name</th>
                            <th>Game Genre</th>
                            <th>Publisher Name</th>
                            <th>Developer Name</th>
                            <th>Price</th>
                            <th></th>
                            <th></th>
                            </tr>";

                        while($hasRow = mysqli_fetch_array($resulted_query)) {
                            $gameId = $hasRow['game_id'];
                            $game_price = $hasRow['game_price'];
                            $game_name = $hasRow['game_name'];
                            $game_genre = $hasRow['game_genre'];
                            $developer_name = $hasRow['developer_name'];
                            $publisher_name = $hasRow['publisher_name'];

                            
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
                            echo "<td><input type=\"hidden\" name=\"gamename\" value='". $game_name ."'>" . $game_name . "</td>";
                            echo "<td>" . $game_genre . "</td>";
                            echo "<td>" . $publisher_name . "</td>";
                            echo "<td>" . $developer_name . "</td>";
                            echo "<td>" . $game_price . " TL"."</td>";
                            
                            if($numberOfRows == 0) {
                                echo "<td>
                                <button name = \"buy\"class=\"btn btn-success btn-sm\">BUY</button>
                                </td>";
                            }                   
                            echo "<td>
                            <button name = \"gift\"class=\"btn btn-primary btn-sm\">GIFT</button>
                            </td>";

                            echo "</tr>";
                            echo "</form>";
                        }

                        echo "</table>";
                        echo "</form>";
                        
                        ?>
          
            </div>
        </div>
    </div>


    <script type="text/javascript">

    </script>
</body>
</html>