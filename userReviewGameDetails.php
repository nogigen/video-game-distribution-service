<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $reviewId = $_SESSION['the_review_id'];

    if(isset($_POST['delete_review'])) {

        //DELETE FROM REVIEW
        $follow_insertion = "DELETE FROM review WHERE review_id = '$reviewId'";
        $result = mysqli_query($db,$follow_insertion);

        //DELETE FROM PERSONREVIEW
        $follow_insertion = "DELETE FROM personreview WHERE review_id = '$reviewId'";
        $result = mysqli_query($db,$follow_insertion);

        echo "<script LANGUAGE='JavaScript'>
                window.alert('Review is successfully deleted.');
                window.location.href = 'userReview.php'; 
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

                <a href="userReview.php">Review Games</a>
                
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
                $gameName = $_SESSION['game_name'];
                $person_id = $_SESSION['person_id'];

                $review_desc = $_SESSION['review_text'];
                $rating = $_SESSION['score'];
                
                echo "<h2>Review of $gameName</h2>";

                echo "<form id=\"reviewForm\" action=\"\" method=\"post\">

                <div class=\"form-group\">
                    <label>Review Description</label>
                    <input type=\"text\" name=\"reviewdesc\" class=\"form-control\" id=\"reviewdesc\" value=\"$review_desc\" readonly=\"readonly\">

                 </div>

                    <div class=\"form-group\">
                            <label>Rating</label>
                            <input type=\"text\" name=\"rating\" class=\"form-control\" id=\"rating\" value=\"$rating\" readonly=\"readonly\">

                    </div>

                    <button type=\"submit\" onclick=\"checkEmpty()\" name = \"delete_review\"class=\"btn btn-danger btn-sm\">DELETE REVIEW</button>
                </form>";
                ?>

                
            </div>
        </div>
    </div>


    <script type="text/javascript">
        
    </script>
</body>
</html>