<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $gameGenre = $_POST['gamegenre'];
    $gameDesc = $_POST['game_desc'];
    $developer_id = $_SESSION['selected_developer_id'];
    $req_id = $_SESSION['selected_req_id'];
    $publisherId = $_SESSION['publisher_id'];
    $gameName = $_SESSION['selected_ask_game_name'];
    $gamePrice = (float)$_POST['gameprice'];

    if(isset($_POST['publish'])) {

        // check game name
        $query = "SELECT game_id FROM game WHERE game_name = '$gameName'";
        $res = mysqli_prepare($db, $query);
        mysqli_stmt_execute($res);
        mysqli_stmt_store_result($res);
        $numberOfRows = mysqli_stmt_num_rows($res);

        if($numberOfRows != 0) {
            echo "<script LANGUAGE='JavaScript'>
            window.alert('A game with the same name got approved. You should come up with a new game name and make a publish request again.');
            window.location.href = 'developGame.php'; 
            </script>";
        }

        else {


            $accepted_query = "UPDATE ask
            SET approval = 'Accepted'
            WHERE ask_game_name = '$gameName' AND publisher_id = '$publisherId' and developer_id = '$developer_id'";
            $result = mysqli_query($db,$accepted_query);

            $publish_game_query = "INSERT INTO game(game_name,game_price,req_id, game_genre, game_desc) VALUES ('$gameName','$gamePrice', '$req_id', '$gameGenre', '$gameDesc')";
            $result = mysqli_query($db,$publish_game_query);

            if (!$result) {
                printf("Error: Inserting game %s\n", mysqli_error($db));
                exit();
            }

            //GET GAME ID
            $queryGameId = "SELECT game_id FROM game WHERE game_name = '$gameName'";
            $result3 = mysqli_query($db, $queryGameId);
            $gameIdRow = mysqli_fetch_array($result3);
            $gameId = $gameIdRow['game_id'];

            //ADD TO PUBLISH GAME
            $publish_game_query_2 = "INSERT INTO publishgame(publisher_id, game_id, discount) VALUES ('$publisherId', '$gameId', 0)";
            $result = mysqli_query($db,$publish_game_query_2);

            if (!$result) {
                printf("Error: Inserting publishgame %s\n", mysqli_error($db));
                exit();
            }

            //ADD TO UPDATEGAME INITIAL
            $msg = "initial game";
            $insert_to_update_query = "INSERT INTO updateGame(game_id, developer_id, update_desc, new_version_no) VALUES ('$gameId','$developer_id', '$msg' , 1)";
            $result = mysqli_query($db,$insert_to_update_query);

            if (!$result) {
                printf("Error: Inserting updategame %s\n", mysqli_error($db));
                exit();
            }

            header("location: publishRefundRequests.php");

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
                    <h4 class="navbar-text">Publisher <?php echo htmlspecialchars($_SESSION['publisher_login_name']); ?></h4>

                </div>
                <a href="publisherWelcome.php">Home</a>
                <a href="publishRequest.php">Publish Requests</a>
                <a href="publishRefundRequests.php">Refund Requests</a>      
                <a href="publishRefundRequestHistory.php">Refund Request History</a>
                <a href ="publishRequestHistory.php">Publish Request History </a>
                <a href="publisherMyGames.php">My Games</a>

                
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
                    $developer_id = $_SESSION['selected_developer_id'];
                    $publisher_id = $_SESSION['publisher_id'];
                    $req_id = $_SESSION['selected_req_id'];
                    $gameName = $_SESSION['selected_ask_game_name'];


                    $query = "SELECT ask_game_desc, ask_game_genre FROM ask WHERE approval = 'Waiting for Approval' and  publisher_id = '$publisher_id' and developer_id = '$developer_id' and ask_game_name = '$gameName'";
                    $result = mysqli_query($db, $query);
                    if(!$result) {
                        printf("Error: Ask table %s\n", mysqli_error($db));
                        exit();
                    }
                    $row = mysqli_fetch_array($result);

                    $game_genre = $row['ask_game_genre'];
                    $game_desc = $row['ask_game_desc'];

                    // get developer name
                    $query = "SELECT developer_name FROM developer WHERE developer_id ='$developer_id'";
                    $result = mysqli_query($db, $query);
                    $row = mysqli_fetch_array($result);
                    $developer_name = $row['developer_name'];

                    // get min system req info
                    $query = "SELECT os, processor, memory, storage FROM systemrequirements WHERE req_id = '$req_id'";
                    $result = mysqli_query($db, $query);
                    $row = mysqli_fetch_array($result);
                    $os = $row['os'];
                    $processor = $row['processor'];
                    $memory = $row['memory'];
                    $storage = $row['storage'];

                    echo "<h2>Publish Game</h2>";

                    echo "<form id=\"publishForm\" action=\"\" method=\"post\">
    
                    <div class=\"form-group\">
                        <label>Game Name</label>
                        <input type=\"text\" name=\"gamename\" class=\"form-control\" id=\"gamename\" value=\"$gameName\" readonly=\"readonly\">
    
                     </div>
    
                        <div class=\"form-group\">
                                <label>Game Genre</label>
                                <input type=\"text\" name=\"gamegenre\" class=\"form-control\" id=\"gamegenre\" value=\"$game_genre\" readonly=\"readonly\">
    
                        </div>

                        <div class=\"form-group\">
                        <label>Developer Name</label>
                        <input type=\"text\" name=\"developername\" class=\"form-control\" id=\"developername\" value=\"$developer_name\" readonly=\"readonly\">

                     </div>

    
                         <div class=\"form-group\">
                         <label>Game Description</label>
                         <textarea class=\"form-control\" name=\"game_desc\" id=\"game_desc\" rows=\"8\" value=\"$game_desc\" readonly=\"readonly\">$game_desc</textarea>
    
                      </div>

                      <h4><b>Minimum System Requirements</b></h4>

                      <div class=\"form-group\">
                      <label>Operating System</label>
                      <textarea class=\"form-control\" rows=\"1\"  readonly=\"readonly\">$os</textarea>
 
                        </div>

                        <div class=\"form-group\">
                        <label>Processor</label>
                        <textarea class=\"form-control\" rows=\"1\"  readonly=\"readonly\">$processor</textarea>
   
                          </div>

                          <div class=\"form-group\">
                          <label>Memory</label>
                          <textarea class=\"form-control\" rows=\"1\"  readonly=\"readonly\">$memory</textarea>
     
                            </div>

                            <div class=\"form-group\">
                            <label>Storage</label>
                            <textarea class=\"form-control\" rows=\"1\"  readonly=\"readonly\">$storage</textarea>
       
                              </div>
                      
                      <div class=\"form-group\">
                      <label>Game Price</label>
                      <input type=\"text\" name=\"gameprice\" class=\"form-control\" id=\"gameprice\">

                   </div>

    
                        <button type=\"submit\" onclick=\"checkEmpty()\" name = \"publish\"class=\"btn btn-success btn-sm\">Publish</button>

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