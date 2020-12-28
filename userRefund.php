<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $person_id = $_SESSION['person_id'];
    $gameName = $_POST['gamename'];

    if(isset($_POST['refund'])) {
        $_SESSION['selected_game_to_refund'] = $gameName;
        header("location: userRefundRequest.php");
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
                <h1>Refund</h1>


                    
                    <?php
                        $person_id = $_SESSION['person_id'];
                        // Prepare a select statement
                        $query = "SELECT person_id, game_id, isInstalled, personVersion FROM has WHERE person_id = " .$_SESSION['person_id'];


                        echo "<p><b>Your Games : </b></p>";

                        $result = mysqli_query($db, $query);

                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($db));
                            exit();
                        }

                        echo "<div class=\"form-group\">
                        <input type=\"text\" id=\"myInput\" onkeyup=\"myFunction()\" placeholder=\"Search for value & col type..\">
                        <select id = \"filterType\">
                            <option value =\"filterGameName\" selected=\"selected\">Game Name</option>
                            <option value = \"filterGameGenre\">Game Genre</option>
                            <option value = \"filterPublisherName\">Publisher Name</option>
                            <option value = \"filterDeveloperName\">Developer Name</option>
                            <option value = \"filterLatestVersion\">Game's Latest Version</option> 
                            <option value = \"filterUserVersion\">User's Latest Version</option>                            
                        </select>

                        </div>";

                        echo "<table class=\"table table-lg table-striped\" id=\"myTable\">
                            <tr>
                            <th>Game Name</th>
                            <th>Game Genre</th>
                            <th>Publisher Name</th>
                            <th>Developer Name</th>
                            <th>Latest Version</th>
                            <th>User's Version</th>
                            <th></th>
                            </tr>";

                        while($hasRow = mysqli_fetch_array($result)) {
                            $gameId = $hasRow['game_id'];
                            $isInstalled = $hasRow['isInstalled'];
                            $personVersion = $hasRow['personVersion'];

                            $queryGame = "SELECT game_name, game_genre, latest_version_no FROM game WHERE game_id = '$gameId'";
                            $result2 = mysqli_query($db, $queryGame);
                            if(!$result2) {
                                printf("Error1: %s\n", mysqli_error($db));
                                exit();
                            }
                            $gameRow =  mysqli_fetch_array($result2);
                            $game_name = $gameRow['game_name'];
                            $game_genre = $gameRow['game_genre'];
                            $latestVersionNo = $gameRow['latest_version_no'];
                            
                            $queryPublisherId = "SELECT publisher_id FROM publishgame WHERE game_id = '$gameId'";
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


                            // check if there is already a refund request
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
                                             
                                echo "<form action=\"\" METHOD=\"POST\">";
                                echo "<tr>";
                                echo "<td><input type=\"hidden\" name=\"gamename\" value='". $game_name ."'>" . $game_name . "</td>";
                                echo "<td>" . $game_genre . "</td>";
                                echo "<td>" . $publisher_name . "</td>";
                                echo "<td>" . $developer_name . "</td>";
                                echo "<td>" . $latestVersionNo . "</td>";

                                if($isInstalled) {
                                    echo "<td>" . $personVersion . "</td>";
                                }
                                else {
                                    echo "<td>" . "-" . "</td>";
                                }                          

                                echo "<td> 
                                    <button type=\"submit\" onclick=\"checkEmpty()\" name = \"refund\"class=\"btn btn-success btn-sm\">REFUND</button>
                                    </td>";

                                echo "</tr>";
                                echo "</form>";
                            }
                        }

                        echo "</table>";
                        
                        ?>
          
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function myFunction() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue, filterType, filterTypeVal;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            filterType = document.getElementById("filterType");
            filterTypeVal = filterType.value;

            var index = 0;
            if(filterTypeVal === "filterGameName") {
                index = 0;
            }
            else if(filterTypeVal === "filterGameGenre") {
                index = 1;
            }

            else if(filterTypeVal === "filterPublisherName") {
                index = 2;
            }
            else if(filterTypeVal === "filterDeveloperName") {
                index = 3;
            }
            else if(filterTypeVal === "filterLatestVersion") {
                index = 4;
            }
            else if(filterTypeVal === "filterUserVersion") {
                index = 5;
            }
            
            // Loop through all table rows, and hide those who don't match the search query
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[index];
                if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
                }
            }
        }
    </script>
</body>
</html>