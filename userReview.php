<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_POST['review_button'])) {

        $_SESSION['game_name'] = $_POST['review_button'];

        echo "<script LANGUAGE='JavaScript'>
                window.location.href = 'userReviewGame.php'; 
                </script>";

    }

    else if(isset($_POST['review_details_button'])) {

        $_SESSION['game_name'] = $_POST['review_details_button'];

        header("location: userReviewGameDetails.php");

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
            <div id="centerwrapper">
            <div id="centerdiv">

            <br><br>
                <h1>List of Games</h1>

                <form id="gameForm" action="" method="post">

                    <?php
                        // Prepare a select statement
                        $personId = $_SESSION['person_id'];
                        $query = "SELECT  game_name, game_id, game_genre, game_desc, publisher_name, developer_name FROM game NATURAL JOIN publishGame NATURAL JOIN publisher NATURAL JOIN updateGame NATURAL JOIN developer NATURAL JOIN has WHERE isInstalled = 1 and person_id = '$personId'";

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
                        </select>
    
                        </div>";

                        echo "<table class=\"table table-lg table-striped\" id=\"myTable\">
                            <tr>
                            <th>Game Name</th>
                            <th>Game Genre</th>
                            <th>Publisher Name</th>
                            <th>Developer Name</th>
                            <th>        </th>
                            </tr>";

                        while($row = mysqli_fetch_array($result)) {

                            $personId = $_SESSION['person_id'];
                            $gameId = $row['game_id'];

                            $is_ever_reviewed = "SELECT game_id FROM game NATURAL JOIN review NATURAL JOIN personreview WHERE person_id = '$personId' and game_id = '$gameId'";
                            $res = mysqli_prepare($db, $is_ever_reviewed);
                            mysqli_stmt_execute($res);
                            mysqli_stmt_store_result($res);
                            $numberOfRows = mysqli_stmt_num_rows($res);

                            $isReviewed = TRUE;

                            if($numberOfRows == 0){
                                $isReviewed = FALSE;
                            }


                            echo "<tr>";
                            echo "<td>" . $row['game_name'] . "</td>";
                            echo "<td>" . $row['game_genre'] . "</td>";
                            echo "<td>" . $row['publisher_name'] . "</td>";
                            echo "<td>" . $row['developer_name'] . "</td>";

                            if($isReviewed){
                                echo "<td>
                                <button onclick=\"cancelled()\" name = \"review_details_button\"class=\"btn btn-primary btn-sm\" value ='".$row['game_name'] ."'>REVIEW DETAILS</button>
                                </td>";
                            }   
                            else{
                                echo "<td>
                                <button onclick=\"cancelled()\" name = \"review_button\"class=\"btn btn-success btn-sm\" value ='".$row['game_name'] ."'>REVIEW</button>
                                </td>";
                            }         
  
                            echo "</tr>";
                        }

                        echo "</table>";
                        ?>
                </form>  
                
            </div>
        </div>
                
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