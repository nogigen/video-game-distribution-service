<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $gameId = $_SESSION['selected_game_id'];
    $person_id = $_SESSION['person_id'];
    $latestVersionNo = $_POST['latestversion'];
    $gamePrice = $_POST['gameprice'];


    if(isset($_POST['buy'])) {
                

        // user's current credit
        $query = "SELECT credits FROM person WHERE person_id = " .$_SESSION['person_id'];
        $res = mysqli_query($db, $query);
        $row = mysqli_fetch_array($res);
        $credit = $row['credits'];

        if($credit >= $gamePrice) {
            // update the balance
            $newBalance = $credit - $gamePrice;
            $query = "UPDATE person SET credits = '$newBalance' WHERE person_id = '$person_id'";
            $res = mysqli_query($db, $query);

            if(!$res) {
                printf("Error: Updating balance %s\n", mysqli_error($db));
                exit();
            }
            // add game to user's library
            $query = "INSERT INTO has VALUES ('$person_id', '$gameId', 0, '$latestVersionNo')";
            $res = mysqli_query($db, $query);
            if(!$res) {
                printf("Error: Adding game to user's library. %s\n", mysqli_error($db));
                exit();
            }

            // update shop history
            $query = "INSERT INTO shophistory (bought_date, bought_price) VALUES(CURDATE(), '$gamePrice')";
            $res = mysqli_query($db, $query);
            if(!$res) {
                printf("Error: Updating shop history. %s\n", mysqli_error($db));
                exit();
            }
          

            // get the latest shop_id
            $query = "SELECT MAX(shop_id) as shop_id FROM shophistory";
            $res = mysqli_query($db, $query);
            if(!$res) {
                printf("Error: Updating shop history. %s\n", mysqli_error($db));
                exit();
            }
            $row = mysqli_fetch_array($res);
            $shopId = $row['shop_id'];

             // update renew table
             $query = "INSERT into renew VALUES('$shopId', '$person_id', '$gameId')";
             $res = mysqli_query($db, $query);
             if(!$res) {
                 printf("Error: Inserting to renew table. %s\n", mysqli_error($db));
                 exit();
             }

             echo "<script LANGUAGE='JavaScript'>
             window.alert('You have successfully bought the game.');
             window.location.href = 'userStore.php'; 
             </script>";
            

        }
        else {
            echo "<script LANGUAGE='JavaScript'>
            window.alert('Not enough credit to buy the game.');
            </script>";
        }
        
        


    }
    elseif(isset($_POST['cancel'])) {
        header("location: userStore.php");
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
                <a href="userSendFriendRequests.php">Send Friend Requests</a>
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
                    $game_id = $_SESSION['selected_game_id'];

                    $query = "SELECT game_name, game_price, req_id, game_desc, game_genre, latest_version_no FROM game WHERE game_id = '$game_id'";
                    $result = mysqli_query($db, $query);
                    if(!$result) {
                        printf("Error: Ask table %s\n", mysqli_error($db));
                        exit();
                    }
                    $row = mysqli_fetch_array($result);

                    $game_name = $row['game_name'];
                    $game_genre = $row['game_genre'];
                    $game_price = $row['game_price'];
                    $req_id = $row['req_id'];
                    $game_desc = $row['game_desc'];
                    $latest_version_no = $row['latest_version_no'];

                    // get developer name
                    $queryDeveloperId = "SELECT developer_id FROM updategame WHERE game_id = '$game_id'";
                    $result5 = mysqli_query($db, $queryDeveloperId);

                    if(!$result5) {
                        printf("Error4: %s\n", mysqli_error($db));
                        exit();
                    }
                    $developerIdRow = mysqli_fetch_array($result5);
                    $developer_id = $developerIdRow['developer_id'];

                
                    $queryDeveloperName = "SELECT developer_name FROM developer WHERE developer_id = '$developer_id'";
                    $result6 = mysqli_query($db, $queryDeveloperName);
                    
                    if(!$result6) {
                        printf("Error5: %s\n", mysqli_error($db));
                        exit();
                    }
                    $developerNameRow = mysqli_fetch_array($result6);
                    $developer_name = $developerNameRow['developer_name'];

                    // get publisher name
                    $queryPublisherId = "SELECT publisher_id FROM publishgame WHERE game_id = '$game_id'";
                    $result3 = mysqli_query($db, $queryPublisherId);
                    if(!$result3) {
                        printf("Error2: %s\n", mysqli_error($db));
                        exit();
                    }
                    $publisherIdRow = mysqli_fetch_array($result3);
                    $publisher_id = $publisherIdRow['publisher_id'];

                    $queryPublisherName = "SELECT publisher_name FROM publisher WHERE publisher_id = '$publisher_id'";
                    $result4 = mysqli_query($db, $queryPublisherName);
                    if(!$result4) {
                        printf("Error3: %s\n", mysqli_error($db));
                        exit();
                    }
                    $publisherNameRow = mysqli_fetch_array($result4);
                    $publisher_name = $publisherNameRow['publisher_name'];


                    // get min system req info
                    $query = "SELECT os, processor, memory, storage FROM systemrequirements WHERE req_id = '$req_id'";
                    $result = mysqli_query($db, $query);
                    $row = mysqli_fetch_array($result);
                    $os = $row['os'];
                    $processor = $row['processor'];
                    $memory = $row['memory'];
                    $storage = $row['storage'];

                    echo "<h2>Game Properties</h2>";

                    echo "<form id=\"buyForm\" action=\"\" method=\"post\">
    
                    <div class=\"form-group\">
                        <label>Game Name</label>
                        <input type=\"text\" name=\"gamename\" class=\"form-control\" id=\"gamename\" value=\"$game_name\" readonly=\"readonly\">
    
                     </div>
    
                        <div class=\"form-group\">
                                <label>Game Genre</label>
                                <input type=\"text\" name=\"gamegenre\" class=\"form-control\" id=\"gamegenre\" value=\"$game_genre\" readonly=\"readonly\">
    
                        </div>

                        <div class=\"form-group\">
                        <label>Game Price</label>
                        <input type=\"text\" name=\"gameprice\" class=\"form-control\" id=\"gameprice\" value=\"$game_price\" readonly=\"readonly\">

                </div>

                <div class=\"form-group\">
                <label>Latest Version Of Game</label>
                <input type=\"text\" name=\"latestversion\" class=\"form-control\" id=\"latestversion\" value=\"$latest_version_no\" readonly=\"readonly\">

                </div>

                        <div class=\"form-group\">
                        <label>Developer Name</label>
                        <input type=\"text\" name=\"developername\" class=\"form-control\" id=\"developername\" value=\"$developer_name\" readonly=\"readonly\">

                     </div>

                     <div class=\"form-group\">
                     <label>Publisher Name</label>
                     <input type=\"text\" name=\"publishername\" class=\"form-control\" id=\"publishername\" value=\"$publisher_name\" readonly=\"readonly\">

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
                    

    
                        <button type=\"submit\" onclick=\"checkEmpty()\" name = \"buy\"class=\"btn btn-success btn-sm\">BUY</button>
                        <button type=\"submit\" onclick=\"checkEmpty()\" name = \"cancel\"class=\"btn btn-danger btn-sm\">CANCEL</button>


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