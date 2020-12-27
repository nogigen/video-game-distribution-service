<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $gameName = $_SESSION['game_name'];
    $personId = $_SESSION['person_id'];

    $reviewText = mysqli_real_escape_string($db, $_POST['reviewcomment']);
    $score = $_POST['rating'];

    if($reviewText == ""){ 

        echo "<script LANGUAGE='JavaScript'>
        window.alert('Please fill the Review Comment');
        window.location.href = 'userReviewGame.php'; 
        </script>";
    }

    else{ //SUCCESS CASE

        //GET GAME ID
        $queryGameId = "SELECT game_id FROM game WHERE game_name = '$gameName'";
        $result = mysqli_query($db, $queryGameId);
        $gameIdRow = mysqli_fetch_array($result);
        $gameId = $gameIdRow['game_id'];

        //INSERT INTO PERSONREVIEW TABLE
        $insert_review = "INSERT INTO personreview(review_text, review_score) VALUES ('$reviewText', '$score')";
        $result = mysqli_query($db,$insert_review);

        //GET REVIEW ID
        $queryReviewId = "SELECT MAX(review_id) as review_id FROM personreview";
        $result = mysqli_query($db, $queryReviewId);
        $reviewIdRow = mysqli_fetch_array($result);
        $reviewId = $reviewIdRow['review_id'];

        //INSERT INTO REVIEW TABLE
        $insert_publish = "INSERT INTO review VALUES ('$reviewId', '$personId', '$gameId')";
        $result = mysqli_query($db,$insert_publish);

        $_SESSION['review_text'] = $reviewText;
        $_SESSION['score'] = $score;
        $_SESSION['the_review_id'] = $reviewId;

        

        echo "<script LANGUAGE='JavaScript'>
        window.alert('Review has ben published');
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
                <h1>Review <?php echo htmlspecialchars($_SESSION['game_name']); ?> </h1>

                <form id="updateForm" action="" method="post">

                    <div class="form-group">
                        <label>Review Comment</label>
                        <textarea class="form-control" name="reviewcomment" id="updatedesc" rows="4"></textarea>

                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Rating</label>
                        <select class="form-control" name="rating" id="exampleFormControlSelect1">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <input type = "submit" onclick="checkEmpty()" class="btn btn-primary" value="Publish Review">
                    </div>
                </form>  
            </div>
        </div>
    </div>


    <script type="text/javascript">
        
    </script>
</body>
</html>