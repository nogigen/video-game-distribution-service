<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $person_id = $_SESSION['person_id'];
    $gameName = $_SESSION['gameName_for_mod'];
    $modId = $_POST['mod_id'];

    if(isset($_POST['install_mod'])) {
        $_SESSION['selected_mod_id'] = $modId;
        header("location: userModFeatures.php");
    }
    elseif(isset($_POST['uninstall_mod'])) {
        // delete from download table
        $query = "DELETE FROM download WHERE person_id = '$person_id' and mod_id = '$modId'";
        $res = mysqli_query($db, $query);

        if(!$res) {
            printf("Error: %s\n", mysqli_error($db));
            exit();
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
                <a href="userStore.php">Store</a>
                <a href="userCheckUpdates.php">Check Updates</a>
                <a href="userCheckMods.php">Mods</a>
                <a href="followCurators.php">Follow Curators</a>
                <a href="userRefund.php">Refund</a>
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
                <?php
                    $personId = $_SESSION['person_id'];
                    $gameName = $_SESSION['gameName_for_mod'];
                    echo "<h2>Available Mods for Game : $gameName </h2>";              
                    
                    // get the game_id from game_name
                    $queryGame = "SELECT game_id FROM game WHERE game_name = '$gameName'";
                    $res = mysqli_query($db, $queryGame);

                    if(!$res) {
                        printf("Error: %s\n", mysqli_error($db));
                        exit();
                    }
                    $gameIdRow = mysqli_fetch_array($res);
                    $gameId = $gameIdRow['game_id'];

                    // get game name and game genre
                    $queryGame = "SELECT game_name, game_genre FROM game WHERE game_id = '$gameId'";
                    $result2 = mysqli_query($db, $queryGame);
                    if(!$result2) {
                        printf("Error1: %s\n", mysqli_error($db));
                        exit();
                    }
                    $gameRow =  mysqli_fetch_array($result2);
                    $game_name = $gameRow['game_name'];
                    $game_genre = $gameRow['game_genre'];



                    $query = "SELECT mod_id, person_id FROM develop WHERE game_id = '$gameId'";

                    echo "<p><b>Mods : </b></p>";

                    $result = mysqli_query($db, $query);

                    if (!$result) {
                        printf("Error: %s\n", mysqli_error($db));
                        exit();
                    }

                        echo "<table class=\"table table-lg table-striped\">
                            <tr>
                            <th>Game Name</th>
                            <th>Game Genre</th>
                            <th>Mod Name</th>
                            <th>Mod's Creator</th>
                            </tr>";

                        while($hasRow = mysqli_fetch_array($result)) {
                            $modId = $hasRow['mod_id'];
                            $modCreatorId = $hasRow['person_id'];
                            
                            // get mod name
                            $queryGame = "SELECT mod_name FROM gamemod WHERE mod_id = '$modId'";
                            $result2 = mysqli_query($db, $queryGame);
                            if(!$result2) {
                                printf("Error1: %s\n", mysqli_error($db));
                                exit();
                            }
                            $modRow =  mysqli_fetch_array($result2);
                            $mod_name = $modRow['mod_name'];

                            // get creater's name (person)
                            $query = "SELECT nick_name FROM person WHERE person_id = '$modCreatorId'";
                            $res = mysqli_query($db, $query);
                            if(!$res) {
                                printf("Error: %s\n", mysqli_error($db));
                                exit();
                            }
                            $personRow =  mysqli_fetch_array($res);
                            $creator_name = $personRow['nick_name'];

                            // check to see if the user already has the mod or not.
                            $query = "SELECT person_id FROM download WHERE person_id = '$personId' and mod_id = '$modId'";
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
                            echo "<td>" . $game_name . "</td>";
                            echo "<td>" . $game_genre . "</td>";
                            echo "<td><input type=\"hidden\" name=\"mod_id\" value=". $modId .">" . $mod_name . "</td>";
                            echo "<td>" . $creator_name. "</td>";

                            if($numberOfRows == 0) {
                                echo "<td> 
                                    <button type=\"submit\" onclick=\"checkEmpty()\" name = \"install_mod\"class=\"btn btn-success btn-sm\">INSTALL MOD</button>
                                </td>";
                            }
                            else {
                                echo "<td> 
                                    <button type=\"submit\" onclick=\"checkEmpty()\" name = \"uninstall_mod\"class=\"btn btn-success btn-sm\">UNINSTALL MOD</button>
                                </td>";
                            }


                            /*
                            if($isInstalled) {
                                echo "<td>" . $personVersion . "</td>";
                            }
                            else {
                                echo "<td>" . "-" . "</td>";
                            }                         

                            if(($personVersion != $latestVersionNo) && $isInstalled) {
                                echo "<td> 
                                <button type=\"submit\" onclick=\"checkEmpty()\" name = \"update\"class=\"btn btn-success btn-sm\">UPDATE</button>
                                </td>";
                            }
                            */
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