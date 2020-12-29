<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $publisherId = $_SESSION['publisher_id'];
    $gameId = $_SESSION['selected_game_id'];

    if(isset($_POST['changeprice'])) {
        $newPrice = (float)$_POST['newgameprice'];

        if($newPric < 0) {
            echo "<script LANGUAGE='JavaScript'>
            window.alert('Game price cannot be negative.');
            window.location.href = 'publisherMyGames.php'; 
            </script>";
        }
        else {
            $query = "UPDATE game SET game_price = $newPrice WHERE game_id = '$gameId'";
            $result = mysqli_query($db, $query);
            if(!$result) {
                printf("Error: Game price couldnt be updated. %s\n", mysqli_error($db));
                exit();

            
            }

            echo "<script LANGUAGE='JavaScript'>
            window.alert('Game price has been updated.');
            window.location.href = 'publisherMyGames.php'; 
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
                    $publisher_id = $_SESSION['publisher_id'];
                    $gameId = $_SESSION['selected_game_id'];

                    $query = "SELECT game_name, game_genre, game_desc, game_price FROM game WHERE game_id = '$gameId'";
                    $result = mysqli_query($db, $query);
                    if(!$result) {
                        printf("Error: Ask table %s\n", mysqli_error($db));
                        exit();
                    }
                    $row = mysqli_fetch_array($result);
                    $game_name = $row['game_name'];
                    $game_genre = $row['game_genre'];
                    $game_desc = $row['game_desc'];
                    $game_price = $row['game_price'];

                    // get developer name
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


                    echo "<h2>Change Price</h2>";

                    echo "<form id=\"priceForm\" action=\"\" method=\"post\">
    
                    <div class=\"form-group\">
                        <label>Game Name</label>
                        <input type=\"text\" name=\"gamename\" class=\"form-control\" id=\"gamename\" value=\"$game_name\" readonly=\"readonly\">
    
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

                      <div class=\"form-group\">
                      <label>Current Game Price</label>
                      <input type=\"text\" name=\"gameprice\" class=\"form-control\" id=\"gameprice\" value=\"$game_price\" readonly=\"readonly\">

                   </div>
                      
                      <div class=\"form-group\">
                      <label>New Game Price</label>
                      <input type=\"text\" name=\"newgameprice\" class=\"form-control\" id=\"newgameprice\">

                   </div>

    
                        <button type=\"submit\" onclick=\"checkEmpty()\" name = \"changeprice\"class=\"btn btn-success btn-sm\">CHANGE PRICE</button>

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