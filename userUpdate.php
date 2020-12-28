<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {
    $gameName = $_SESSION['selected_gameName_to_update'];
    $person_id = $_SESSION['person_id'];

    if(isset($_POST['update'])) {
        
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
        
        $query = "UPDATE has SET personVersion = '$latestVersionNo' WHERE person_id = '$person_id' and game_id = '$gameId'";
        $res = mysqli_query($db, $query);

        if(!$res) {
            printf("Error: Update %s\n", mysqli_error($db));
            exit();
        }
        
        header("location: userCheckUpdates.php");
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
                <h1>Update</h1>

                <?php
                    $gameName = $_SESSION['selected_gameName_to_update'];
                    // get the game_id from game_name
                    $queryGame = "SELECT game_id, latest_version_no FROM game WHERE game_name = '$gameName'";
                    $res = mysqli_query($db, $queryGame);

                    if(!$res) {
                        printf("Error: %s\n", mysqli_error($db));
                        exit();
                    }
                    $gameIdRow = mysqli_fetch_array($res);
                    $gameId = $gameIdRow['game_id'];
                    
                    // get the developer name of this game
                    $queryDeveloperId = "SELECT developer_id FROM updategame WHERE game_id = '$gameId' " ;
                    $result5 = mysqli_query($db, $queryDeveloperId);

                    if(!$result5) {
                        printf("Error4: %s\n", mysqli_error($db));
                        exit();
                    }
                    $developerIdRow = mysqli_fetch_array($result5);
                    $developer_id = $developerIdRow['developer_id'];

                
                    $queryDeveloperName = "SELECT developer_name FROM developer WHERE developer_id = '$developer_id'" ;
                    $result6 = mysqli_query($db, $queryDeveloperName);
                    
                    if(!$result6) {
                        printf("Error5: %s\n", mysqli_error($db));
                        exit();
                    }
                    $developerNameRow = mysqli_fetch_array($result6);
                    $developer_name = $developerNameRow['developer_name'];

                    // get the latest update to this game.
                    
                    $query = "SELECT update_desc, new_version_no FROM updategame WHERE game_id = '$gameId' and developer_id = '$developer_id'";
                    $res = mysqli_query($db, $query);
                    if(!$result6) {
                        printf("Error: %s\n", mysqli_error($db));
                        exit();
                    }
                    $updateRow = mysqli_fetch_array($res);
                    $update_desc = $updateRow['update_desc'];
                    $updateNo = $updateRow['new_version_no'];


                    echo "<form id=\"gameForm\" action=\"\" method=\"post\">";

                    echo "<div class=\"form-group\">
                            <label>Game Name</label>
                            <input type=\"text\" name=\"gamename\" class=\"form-control\" id=\"gamename\" value=\"$gameName\" readonly=\"readonly\">
                        </div>";

                    echo "<div class=\"form-group\">
                            <label>Developer Name</label>
                            <input type=\"text\" name=\"developername\" class=\"form-control\" id=\"developername\" value=\"$developer_name\" readonly=\"readonly\">

                        </div>";

                    echo "<div class=\"form-group\">
                            <label>Update Description</label>
                            <textarea class=\"form-control\" name=\"updatedesc\" id=\"gamedesc\" rows=\"8\" value=\"$update_desc\" readonly=\"readonly\" >$update_desc</textarea>

                        </div>";
                    echo "<div class=\"form-group\">
                            <label>New Version No</label>
                            <input type=\"text\" name=\"updateno\" class=\"form-control\" id=\"gamegenre\" value=\"$updateNo\" readonly=\"readonly\">

                        </div>";

                    echo "<button type=\"submit\"  name =\"update\" class=\"btn btn-primary\">UPDATE</button>";
                        
                        
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