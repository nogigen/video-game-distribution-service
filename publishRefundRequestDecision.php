<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $publisher_id = $_SESSION['publisher_id'];
    $refund_id = $_SESSION['selected_refund_id'];

    if(isset($_POST['accept'])) {

        // update refund status
        $query = "UPDATE refundhistory SET refund_approval = 'Accepted' WHERE refund_id = '$refund_id'";
        $res = mysqli_query($db, $query);        
        if (!$res) {
            printf("Error: Updating refund history. %s\n", mysqli_error($db));
            exit();
            
        }
        // delete the game from users library
        // first find the person and the game id
        $query = "SELECT person_id, game_id FROM request WHERE refund_id ='$refund_id'";
        $res = mysqli_query($db, $query);        
        if (!$res) {
            printf("Error: Getting person_id %s\n", mysqli_error($db));
            exit();
            
        }
        $requestRow = mysqli_fetch_array($res);
        $person_id = $requestRow['person_id'];
        $game_id = $requestRow['game_id'];

        
        $query = "DELETE FROM has WHERE person_id = '$person_id' and game_id = '$game_id'";
        $res = mysqli_query($db, $query);        
        if (!$res) {
            printf("Error: Deleting from has table. %s\n", mysqli_error($db));
            exit();
            
        }

        // update user's credit. give the money back
        // get shop_id first
        $query = "SELECT shop_id FROM refundhistory WHERE refund_id = '$refund_id'";
        $res = mysqli_query($db, $query);
        
        if(!$res) {
            printf("Error5: %s\n", mysqli_error($db));
            exit();
        }
        $refundRow = mysqli_fetch_array($res);
        $shop_id = $refundRow['shop_id'];

        // get bought price and bought date
        $query = "SELECT bought_price FROM shophistory WHERE shop_id = '$shop_id'";
        $res = mysqli_query($db, $query);

        if(!$res) {
            printf("Error5: %s\n", mysqli_error($db));
            exit();
        }
        $shopRow = mysqli_fetch_array($res);
        $bought_price = (float)$shopRow['bought_price'];

        // users current balance
        $query = "SELECT credits FROM person WHERE person_id = '$person_id'";
        $res = mysqli_query($db, $query);
        if(!$res) {
            printf("Error: Getting . %s\n", mysqli_error($db));
            exit();
        }
        $personRow = mysqli_fetch_array($res);
        $balance = (float)$personRow['credits'];


        $newBalance = $balance + $bought_price;
        $query = "UPDATE person SET credits = $newBalance WHERE person_id = '$person_id'";
        $res = mysqli_query($db, $query);

        if(!$res) {
            printf("Error: Updating user's credit. %s\n", mysqli_error($db));
            exit();
        }

        // delete renew row from renew table
        $query = "DELETE FROM renew
                  WHERE person_id = '$person_id' and game_id = '$game_id' and buy_type = 'buy'";

        $res = mysqli_query($db, $query);

        if(!$res) {
            printf("Error: Deleting from renew table. %s\n", mysqli_error($db));
            exit();
        }

        echo "<script LANGUAGE='JavaScript'>
        window.alert('Refund Request has been approved.');
        window.location.href = 'publishRefundRequests.php'; 
        </script>";

        
    }
    
    elseif(isset($_POST['decline'])) { 
        // update refund status
        $query = "UPDATE refundhistory SET refund_approval = 'Declined' WHERE refund_id = '$refund_id'";
        $res = mysqli_query($db, $query);        
        if (!$res) {
            printf("Error: Updating refund history. %s\n", mysqli_error($db));
            exit();
            
        }
        echo "<script LANGUAGE='JavaScript'>
        window.alert('Refund Request has been declined.');
        window.location.href = 'publishRefundRequests.php'; 
        </script>";

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
                    $refund_id = $_SESSION['selected_refund_id'];

                    // get game id and person id from refund id
                    $query = "SELECT game_id, person_id FROM request WHERE publisher_id = '$publisher_id'";
                    $result = mysqli_query($db, $query);
                    if (!$result) {
                        printf("Error: getting game name and game genre %s\n", mysqli_error($db));
                        exit();
                    }
                    $requestRow = mysqli_fetch_array($result);
                    $game_id = $requestRow['game_id'];
                    $person_id = $requestRow['person_id'];

                    // get game name from game id
                    $query = "SELECT game_name, game_genre, game_price FROM game WHERE game_id = '$game_id'";
                    $result = mysqli_query($db, $query);
                    if (!$result) {
                        printf("Error: getting game name and game genre %s\n", mysqli_error($db));
                        exit();
                    }
                    $gameRow = mysqli_fetch_array($result);
                    $game_name = $gameRow['game_name'];
                    $game_genre = $gameRow['game_genre'];
                    $game_price = $gameRow['game_price'];
                    
                    // get developer name from game id
                    $queryDeveloperId = "SELECT developer_id FROM updategame WHERE game_id = '$game_id' " ;
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

                    // get shop_id and refund_description using refund_id
                    $query = "SELECT shop_id, refund_description FROM refundhistory WHERE refund_id = '$refund_id'";
                    $res = mysqli_query($db, $query);
                    
                    if(!$res) {
                        printf("Error5: %s\n", mysqli_error($db));
                        exit();
                    }
                    $refundRow = mysqli_fetch_array($res);
                    $shop_id = $refundRow['shop_id'];
                    $refund_description = $refundRow['refund_description'];

                    // get bought price and bought date
                    $query = "SELECT bought_date, bought_price FROM shophistory WHERE shop_id = '$shop_id'";
                    $res = mysqli_query($db, $query);

                    if(!$res) {
                        printf("Error5: %s\n", mysqli_error($db));
                        exit();
                    }
                    $shopRow = mysqli_fetch_array($res);
                    $bought_date = $shopRow['bought_date'];
                    $bought_price = $shopRow['bought_price'];

                    // get users nickname from person id
                    $query = "SELECT nick_name FROM person WHERE person_id = '$person_id'";
                    $result = mysqli_query($db, $query);
                    if (!$result) {
                        printf("Error: getting person name %s\n", mysqli_error($db));
                        exit();
                        
                    }
                    $personRow = mysqli_fetch_array($result);
                    $user_name = $personRow['nick_name'];

                    echo "<h2>Reviewing Refund Request</h2>";

                    echo "<form id=\"refundForm\" action=\"\" method=\"post\">
    
                    <div class=\"form-group\">
                        <label>User Nickname</label>
                        <input type=\"text\" name=\"username\" class=\"form-control\" id=\"username\" value=\"$user_name\" readonly=\"readonly\">
    
                     </div>
    
                        <div class=\"form-group\">
                                <label>Game Name</label>
                                <input type=\"text\" name=\"gamename\" class=\"form-control\" id=\"gamename\" value=\"$game_name\" readonly=\"readonly\">
    
                        </div>

                        <div class=\"form-group\">
                        <label>Developer Name</label>
                        <input type=\"text\" name=\"developername\" class=\"form-control\" id=\"developername\" value=\"$developer_name\" readonly=\"readonly\">

                     </div>
    
                        <div class=\"form-group\">
                            <label>Bought Date</label>
                            <input type=\"text\" name=\"boughtdate\" class=\"form-control\" id=\"boughtdate\" value=\"$bought_date\" readonly=\"readonly\">
    
                         </div>

                         <div class=\"form-group\">
                         <label>Bought Price</label>
                         <input type=\"text\" name=\"boughtprice\" class=\"form-control\" id=\"boughtprice\" value=\"$bought_price\" readonly=\"readonly\">
 
                      </div>
    
                         <div class=\"form-group\">
                         <label>Refund Description</label>
                         <textarea class=\"form-control\" name=\"refund_desc\" id=\"refund_desc\" rows=\"8\" readonly=\"readonly\">$refund_description</textarea>
    
                      </div>    
    
                        <button type=\"submit\" onclick=\"checkEmpty()\" name = \"accept\"class=\"btn btn-success btn-sm\">ACCEPT</button>
                        <button type=\"submit\" onclick=\"checkEmpty()\" name = \"decline\"class=\"btn btn-danger btn-sm\">DECLINE</button>

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