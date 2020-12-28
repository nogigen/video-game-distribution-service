<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['createmod'])) {

        $gameName = $_SESSION['gameName_for_mod'];
        $person_id = $_SESSION["person_id"];

        $modName = mysqli_real_escape_string($db, $_POST['modname']);
        $modDesc = mysqli_real_escape_string($db, $_POST['moddesc']);

        // get id from the game name
        $queryGame = "SELECT game_id FROM game WHERE game_name = '$gameName'";
        $res = mysqli_query($db, $queryGame);

        if(!$res) {
            printf("Error: %s\n", mysqli_error($db));
            exit();
        }
        $gameIdRow = mysqli_fetch_array($res);
        $gameId = $gameIdRow['game_id'];

        // create mod
        $query = "INSERT INTO gamemod(mod_name, mod_desc) VALUES ('$modName', '$modDesc')";
        $res = mysqli_query($db, $query);
        if(!$res) {
            printf("Error: Inserting mod %s\n", mysqli_error($db));
            exit();
        }
        
        // get the id of this mod.
        $query = "SELECT MAX(mod_id) as mod_id FROM gamemod";
        $res = mysqli_query($db, $query);
        if(!$res) {
            printf("Error: Getting latest mod id %s\n", mysqli_error($db));
            exit();
        }
        $modRow = mysqli_fetch_array($res);
        $mod_id = $modRow['mod_id'];

        // make connection to develop mod.
        $query = "INSERT INTO develop VALUES ('$mod_id', '$person_id', '$gameId')";
        $res = mysqli_query($db, $query);
        if(!$res) {
            printf("Error: Inserting develop(mod) %s\n", mysqli_error($db));
            exit();
        }

        echo "<script LANGUAGE='JavaScript'>
        window.alert('Mod has been created.');
        window.location.href = 'userCheckMods.php'; 
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
                <a href="userCheckUpdates.php">Update</a>
                <a href="userCheckMods.php">Mods</a>
                <a href="userFollowCurators.php">Curator</a>
                <a href="userRefund.php">Refund</a>
                <a href="userRefundHistory.php">Refund History</a>
                <a href="userShopHistory.php">Shop History</a>
                <a href="userReview.php">Review Games</a>
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
                <?php
                $gameName = $_SESSION['gameName_for_mod'];

                echo "<h2>$gameName</h2>";

                echo "<form id=\"modForm\" action=\"\" method=\"post\">

                    <div class=\"form-group\">
                        <label>Mod Name</label>
                        <input type=\"text\" name=\"modname\" class=\"form-control\" id=\"modname\">

                    </div>
                    <div class=\"form-group\">
                        <label>Mod Description</label>
                        <textarea class=\"form-control\" name=\"moddesc\" id=\"moddesc\" rows=\"8\"></textarea>

                    </div>

                    <button type=\"submit\" onclick=\"checkEmpty()\" name = \"createmod\"class=\"btn btn-success btn-sm\">CREATE MOD</button>
                </form>";
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