<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $reviewId = $_SESSION['the_review_id'];

    if(isset($_POST['delete_review'])) {

        //DELETE FROM PUBLISH
        $follow_insertion = "DELETE FROM publish WHERE c_review_id = '$reviewId'";
        $result = mysqli_query($db,$follow_insertion);

        //DELETE FROM CURATORREVIEW
        $follow_insertion = "DELETE FROM curatorreview WHERE c_review_id = '$reviewId'";
        $result = mysqli_query($db,$follow_insertion);

        echo "<script LANGUAGE='JavaScript'>
                window.alert('Review is successfully deleted.');
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
                <?php
                $gameName = $_SESSION['game_name'];

                $query = "SELECT c_review_text, c_review_score  FROM curator NATURAL JOIN game NATURAL JOIN publish NATURAL JOIN curatorreview WHERE game_name = '$gameName' AND curator_id = " .$_SESSION['curator_id'];
                $res = mysqli_query($db, $query);
                $row = mysqli_fetch_array($res);
                $review_desc = $row['c_review_text'];
                $rating = $row['c_review_score'];
                
                echo "<h2>Review of $gameName </h2>";

                echo "<form id=\"reviewForm\" action=\"\" method=\"post\">

                <div class=\"form-group\">
                    <label>Review Description</label>
                    <textarea class=\"form-control\" name=\"review_desc\" id=\"review_desc\" rows=\"8\" value=\"$review_desc\" readonly=\"readonly\">$review_desc</textarea>

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