<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $gameName = $_SESSION['game_name'];
    $curatorId = $_SESSION['curator_id'];

    $reviewText = mysqli_real_escape_string($db, $_POST['reviewcomment']);
    $score = $_POST['rating'];

    if($reviewText == ""){ 

        echo "<script LANGUAGE='JavaScript'>
        window.alert('Please fill the Review Comment');
        window.location.href = 'curatorReview.php'; 
        </script>";
    }

    else{ //SUCCESS CASE

        //GET GAME ID
        $queryGameId = "SELECT game_id FROM game WHERE game_name = '$gameName'";
        $result = mysqli_query($db, $queryGameId);
        $gameIdRow = mysqli_fetch_array($result);
        $gameId = $gameIdRow['game_id'];

        //INSERT INTO CURATURREVIEW TABLE
        $insert_curator_review = "INSERT INTO curatorreview(c_review_text, c_review_score) VALUES ('$reviewText', '$score')";
        $result = mysqli_query($db,$insert_curator_review);

        //GET CREVIEW ID
        $queryCReviewId = "SELECT MAX(c_review_id) as c_review_id FROM curatorreview";
        $result = mysqli_query($db, $queryCReviewId);
        $CReviewIdRow = mysqli_fetch_array($result);
        $cReviewId = $CReviewIdRow['c_review_id'];


        //INSERT INTO PUBLISH TABLE
        $insert_publish = "INSERT INTO publish VALUES ('$cReviewId', '$curatorId', '$gameId')";
        $result = mysqli_query($db,$insert_publish);

        $_SESSION['review_text'] = $reviewText;
        $_SESSION['score'] = $score;
        $_SESSION['the_review_id'] = $cReviewId;


        echo "<script LANGUAGE='JavaScript'>
        window.alert('Review has ben published');
        window.location.href = 'curatorSuggestGame.php'; 
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
                    <h4 class="navbar-text">Curator <?php echo htmlspecialchars($_SESSION['curator_login_name']); ?></h4>
                </div>
                <a href="curatorWelcome.php">Home</a>
                <?php
                    $query = "SELECT no_of_followers FROM curator WHERE curator_id = " .$_SESSION['curator_id'];
                    $res = mysqli_query($db, $query);
                    $row = mysqli_fetch_array($res);
                    $followers = $row['no_of_followers'];
                    echo "<a href='curatorShowFollowers.php'>Followers : $followers</a>";

                ?>
                <a href="curatorSuggestGame.php">Suggest Game</a>

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