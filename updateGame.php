<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $updateDesc = $_POST["updatedesc"];
    $versionNo = $_POST["version_no"];
    $gameName = $_SESSION['game_name'];
    $developerId = $_SESSION['developer_id'];

    //GETS GAME ID
    $queryGameId = "SELECT game_id FROM game WHERE game_name = '$gameName'";
    $result = mysqli_query($db, $queryGameId);
    $gameIdRow = mysqli_fetch_array($result);
    $gameId = $gameIdRow['game_id'];

    //GETS CURRENT VERSION NO
    $queryLatestNo = "SELECT latest_version_no FROM game WHERE game_name = '$gameName'";
    $result = mysqli_query($db, $queryLatestNo);
    $latestNoRow = mysqli_fetch_array($result);
    $latestVersionNo = $latestNoRow['latest_version_no'];
    $latestVersionNo = floatval($latestVersionNo);

    $is_ever_updated_query = "SELECT game_id from updateGame WHERE game_id = '$gameId'";
    $res = mysqli_prepare($db, $is_ever_updated_query);
    mysqli_stmt_execute($res);
    mysqli_stmt_store_result($res);
    $numberOfRows = mysqli_stmt_num_rows($res);

    $floatVersionNo = floatval($versionNo);

    

    if($updateDesc == "" || $versionNo == ""){

        echo "<script LANGUAGE='JavaScript'>
        window.alert('Fill Completely');
        window.location.href = 'updateGame.php'; 
        </script>";
    }
    else{
        if($floatVersionNo == 0){
            echo "<script LANGUAGE='JavaScript'>
            window.alert('Version No. should be positive float!');
            window.location.href = 'updateGame.php'; 
            </script>";
        }
        else if($floatVersionNo <= $latestVersionNo){
            echo "<script LANGUAGE='JavaScript'>
            window.alert('New Version No. shoud be higher than the old one!');
            window.location.href = 'updateGame.php'; 
            </script>";
        }

        else{
            //SUCCESS CASE

            //UPDATE UPDATEGAME TABLE
            $overwrite_update_query = "UPDATE updateGame
            SET update_desc = '$updateDesc', new_version_no = $versionNo
            WHERE game_id = '$gameId'";
            $result = mysqli_query($db,$overwrite_update_query);

            //UPDATE GAME TABLE
            $update_version_no_query = "UPDATE game
            SET latest_version_no = '$versionNo'
            WHERE game_name = '$gameName'";
            $result = mysqli_query($db,$update_version_no_query);

            echo "<script LANGUAGE='JavaScript'>
            window.alert('Game is updated!');
            window.location.href = 'publishedGames.php'; 
            </script>";
        }
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
                <h1>Update <?php echo htmlspecialchars($_SESSION['game_name']); ?> </h1>

                <form id="updateForm" action="" method="post">

                    <div class="form-group">
                        <label>Update Description</label>
                        <textarea class="form-control" name="updatedesc" id="updatedesc" rows="4"></textarea>

                    </div>
                    <div class="form-group">
                        <label>New Version No.</label>
                        <input type="text" name="version_no" class="form-control" id="version_no">

                    </div>
                    
                    <div class="form-group">
                        <input type = "submit" onclick="checkEmpty()" class="btn btn-primary" value="Update">
                    </div>
                </form>  
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function checkEmpty() {
            var gamenameVal = document.getElementById("updatedesc").value;
            var gamedescVal = document.getElementById("version_no").value;
            if (gamenameVal === "" || gamedescVal === "" || gamegenreVal === "") {
                //alert("FILL!");
            }
            else { 
                //var form = document.getElementById("updateForm").submit();
            }
        }
    </script>
</body>
</html>