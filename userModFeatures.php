<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {
    $gameName = $_SESSION['gameName_for_mod'];
    $person_id = $_SESSION['person_id'];
    $mod_id = $_SESSION['selected_mod_id'];

    if(isset($_POST['install'])) {
        
        // get the game_id from game_name
        $queryGame = "SELECT game_id FROM game WHERE game_name = '$gameName'";
        $res = mysqli_query($db, $queryGame);

        if(!$res) {
            printf("Error: %s\n", mysqli_error($db));
            exit();
        }
        $gameIdRow = mysqli_fetch_array($res);
        $gameId = $gameIdRow['game_id'];
        
        $query = "INSERT INTO download VALUES ('$person_id' , '$gameId', '$mod_id')";
        $res = mysqli_query($db, $query);

        if(!$res) {
            printf("Error: Download mod. %s\n", mysqli_error($db));
            exit();
        }
        
        header("location: userSeeMod.php");
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
                <a href="userFollowCurators.php">Follow Curators</a>
                <a href="userRefund.php">Refund</a>
                <a href="userRefundHistory.php">Refund History</a>
                <a href="userShopHistory.php">Shop History</a>
                <a href="userReview.php">Review Games</a>
                <a href="userReceivedFriendRequests.php">Received Friend Requests</a>
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
                <h1>Mod</h1>

                <?php
                    $modId = $_SESSION['selected_mod_id'];
                    // get the game_id from game_name
                    $query = "SELECT mod_name, mod_desc FROM gamemod WHERE mod_id = '$modId'";
                    $res = mysqli_query($db, $query);

                    if(!$res) {
                        printf("Error: %s\n", mysqli_error($db));
                        exit();
                    }
                    $modIdRow = mysqli_fetch_array($res);
                    $mod_name = $modIdRow['mod_name'];
                    $mod_desc = $modIdRow['mod_desc'];
                    
                    // get the game name
                    // get the creator of this mod
                    $query = "SELECT game_id, person_id FROM develop WHERE mod_id = '$modId'";
                    $res = mysqli_query($db, $query);

                    if(!$res) {
                        printf("Error: %s\n", mysqli_error($db));
                        exit();
                    }
                    $developRow = mysqli_fetch_array($res);
                    $gameId = $developRow['game_id'];
                    $creatorId = $developRow['person_id'];

                    // get the creater name from creator id
                    $query = "SELECT nick_name FROM person WHERE person_id = '$creatorId'";
                    $res = mysqli_query($db, $query);

                    if(!$res) {
                        printf("Error: %s\n", mysqli_error($db));
                        exit();
                    }
                    $creatorRow = mysqli_fetch_array($res);
                    $creatorName = $creatorRow['nick_name'];
                

                    // get the game name from game id
                    $queryGame = "SELECT game_name FROM game WHERE game_id = '$gameId'";
                    $res = mysqli_query($db, $queryGame);

                    if(!$res) {
                        printf("Error: %s\n", mysqli_error($db));
                        exit();
                    }
                    $gameRow = mysqli_fetch_array($res);
                    $gameName = $gameRow['game_name'];

                    echo "<form id=\"gameForm\" action=\"\" method=\"post\">";

                    echo "<div class=\"form-group\">
                            <label>Game Name</label>
                            <input type=\"text\" name=\"gamename\" class=\"form-control\" id=\"gamename\" value=\"$gameName\" readonly=\"readonly\">
                        </div>";

                    echo "<div class=\"form-group\">
                        <label>Mod Name</label>
                        <input type=\"text\" name=\"modname\" class=\"form-control\" id=\"modname\" value=\"$mod_name\" readonly=\"readonly\">

                    </div>";

                    echo "<div class=\"form-group\">
                            <label>Creator Name</label>
                            <input type=\"text\" name=\"developername\" class=\"form-control\" id=\"creatorname\" value=\"$creatorName\" readonly=\"readonly\">

                        </div>";

                    echo "<div class=\"form-group\">
                            <label>Mod Description</label>
                            <textarea class=\"form-control\" name=\"moddesc\" id=\"moddesc\" rows=\"8\" value=\"$mod_desc\" readonly=\"readonly\">$mod_desc</textarea>

                        </div>";

                    echo "<button type=\"submit\"  name =\"install\" class=\"btn btn-primary\">INSALL MOD</button>";
                        
                        
                    echo "</form>";
                ?>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function checkEmpty() {

            }
        }
    </script>
</body>
</html>