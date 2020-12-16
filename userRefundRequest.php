<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $gameName = $_SESSION['selected_game_to_refund'];
    $person_id = $_SESSION['person_id'];

    $refund_desc = $_POST['refund_desc'];

    // game id from game name
    $queryGame = "SELECT game_id FROM game WHERE game_name = '$gameName'";
    $res = mysqli_query($db, $queryGame);

    if(!$res) {
        printf("Error: %s\n", mysqli_error($db));
        exit();
    }
    $gameRow = mysqli_fetch_array($res);
    $gameId = $gameRow['game_id'];

    // publisher id from game id
    $query = "SELECT publisher_id FROM publishgame WHERE game_id = '$gameId'";
    $res = mysqli_query($db, $query);

    if(!$res) {
        printf("Error: %s\n", mysqli_error($db));
        exit();
    }
    $publishGameRow = mysqli_fetch_array($res);
    $publisher_id = $publishGameRow['publisher_id'];

    // get shop id
    $query = "SELECT shop_id FROM renew WHERE person_id = '$person_id' and game_id = '$gameId'";
    $res = mysqli_query($db, $query);

    if(!$res) {
        printf("Error: %s\n", mysqli_error($db));
        exit();
    }
    $renewRow = mysqli_fetch_array($res);
    $shopId = $renewRow['shop_id'];

    if(isset($_POST['refund'])) {

        echo("<script>console.log('PHP: " . $gameId . "');</script>");
        echo("<script>console.log('PHP: " . $person_id . "');</script>");

        
        // check to see if there is already a request.
        $query = "SELECT refund_id FROM request WHERE person_id = '$person_id' and game_id = '$gameId'";
        $res = mysqli_prepare($db, $query);
        if(!$res) {
            printf("Error: %s\n", mysqli_error($db));
            exit();
        }
        mysqli_stmt_execute($res);
        mysqli_stmt_store_result($res);
        $numberOfRows = mysqli_stmt_num_rows($res);

        if($numberOfRows == 0) {    
            // create refund history.
            $query = "INSERT INTO refundhistory(shop_id, refund_description, refund_approval) VALUES('$shopId', '$refund_desc', 0)";
            $res = mysqli_query($db, $query);

            if(!$res) {
                printf("Error: Insert into refundhistory %s\n", mysqli_error($db));
                exit();
            }

            // get the latest refund id
            $query = "SELECT MAX(refund_id) as refund_id FROM refundhistory";
            $res = mysqli_query($db, $query);

            if(!$res) {
                printf("Error: %s\n", mysqli_error($db));
                exit();
            }
            $refundRow = mysqli_fetch_array($res);
            $refundId = $refundRow['refund_id'];

            // add it to request.
            $query = "INSERT INTO request VALUES('$refundId', '$person_id', '$gameId', '$publisher_id')";
            $res = mysqli_query($db, $query);

            if(!$res) {
                printf("Error: Insert into request table %s\n", mysqli_error($db));
                exit();
            }

            header("location: userRefund.php");




        }
        else {
            echo "<script LANGUAGE='JavaScript'>
            window.alert('You already have a refund request for this game.');
            window.location.href = 'userRefund.php'; 
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
                $gameName = $_SESSION['selected_game_to_refund'];
                $person_id = $_SESSION['person_id'];
                // get game id from game name
                $queryGame = "SELECT game_id FROM game WHERE game_name = '$gameName'";
                $res = mysqli_query($db, $queryGame);

                if(!$res) {
                    printf("Error: %s\n", mysqli_error($db));
                    exit();
                }
                $gameRow = mysqli_fetch_array($res);
                $gameId = $gameRow['game_id'];

                // get publisher id, tickets will go to that publisher
                $query = "SELECT publisher_id FROM publishgame WHERE game_id = '$gameId'";
                $res = mysqli_query($db, $query);

                if(!$res) {
                    printf("Error: %s\n", mysqli_error($db));
                    exit();
                }
                $publishGameRow = mysqli_fetch_array($res);
                $publisher_id = $publishGameRow['publisher_id'];

                // get publisher name from publisher id
                $query = "SELECT publisher_name FROM publisher WHERE publisher_id = '$publisher_id'";
                $res = mysqli_query($db, $query);

                if(!$res) {
                    printf("Error: %s\n", mysqli_error($db));
                    exit();
                }
                $publisherRow = mysqli_fetch_array($res);
                $publisher_name = $publisherRow['publisher_name'];


                // get shop_id
                $query = "SELECT shop_id FROM renew WHERE person_id = '$person_id' and game_id = '$gameId'";
                $res = mysqli_query($db, $query);

                if(!$res) {
                    printf("Error: %s\n", mysqli_error($db));
                    exit();
                }
                $renewRow = mysqli_fetch_array($res);
                $shopId = $renewRow['shop_id'];

                // get bought price and date from ShopHistory table
                $query = "SELECT bought_date, bought_price FROM shophistory WHERE shop_id = '$shopId'";
                $res = mysqli_query($db, $query);

                if(!$res) {
                    printf("Error: %s\n", mysqli_error($db));
                    exit();
                }
                $shopRow = mysqli_fetch_array($res);
                $bought_date = $shopRow['bought_date'];
                $bought_price = $shopRow['bought_price'];

                // get current price
                $query = "SELECT game_price FROM game WHERE game_id ='$gameId'";
                $res = mysqli_query($db, $query);

                if(!$res) {
                    printf("Error: %s\n", mysqli_error($db));
                    exit();
                }
                $gameRow = mysqli_fetch_array($res);
                $game_price = $gameRow['game_price'];

                echo "<h2>Refund Request for : $gameName</h2>";

                echo "<form id=\"refundForm\" action=\"\" method=\"post\">

                <div class=\"form-group\">
                    <label>Bought Date</label>
                    <input type=\"text\" name=\"boughtdate\" class=\"form-control\" id=\"boughtdate\" value=\"$bought_date\" readonly=\"readonly\">

                 </div>

                    <div class=\"form-group\">
                            <label>Bought Price</label>
                            <input type=\"text\" name=\"boughtprice\" class=\"form-control\" id=\"boughtprice\" value=\"$bought_price\" readonly=\"readonly\">

                    </div>

                    <div class=\"form-group\">
                        <label>Current Price</label>
                        <input type=\"text\" name=\"gameprice\" class=\"form-control\" id=\"gameprice\" value=\"$game_price\" readonly=\"readonly\">

                     </div>

                     <div class=\"form-group\">
                     <label>Publisher Name</label>
                     <textarea class=\"form-control\" name=\"publisherId\" id=\"publisherId\" rows=\"1\" value=\"$publisher_id\" readonly=\"readonly\">$publisher_name</textarea>

                  </div>


                    <div class=\"form-group\">
                        <label>Refund Description</label>
                        <textarea class=\"form-control\" name=\"refund_desc\" id=\"refund_desc\" rows=\"8\"></textarea>

                    </div>

                    <button type=\"submit\" onclick=\"checkEmpty()\" name = \"refund\"class=\"btn btn-success btn-sm\">SEND REFUND REQUEST</button>
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