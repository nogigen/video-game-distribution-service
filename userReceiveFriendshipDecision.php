<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $person_id = $_SESSION['person_id'];
    $friendship_id = $_SESSION['selected_friendship_id'];

    if(isset($_POST['accept'])) {

        // update relationship_status
        $query = "UPDATE relationship
                  SET relationship_status = 'Accepted'
                  WHERE friendship_id = '$friendship_id'";

        $result = mysqli_query($db, $query);
        if(!$result) {
            printf("Error: Updating relationship status to accepted. %s\n", mysqli_error($db));
            exit();
        }

        echo "<script LANGUAGE='JavaScript'>
        window.alert('Friendship Request has been approved.');
        window.location.href = 'userReceivedFriendRequests.php'; 
        </script>";


    }
    else if(isset($_POST['decline'])) {

        // update relationship_status
        $query = "UPDATE relationship
        SET relationship_status = 'Declined'
        WHERE friendship_id = '$friendship_id'";

        $result = mysqli_query($db, $query);
        if(!$result) {
            printf("Error: Updating relationship status to declined. %s\n", mysqli_error($db));
            exit();
        }

        echo "<script LANGUAGE='JavaScript'>
        window.alert('Friendship Request has been declined.');
        window.location.href = 'userReceivedFriendRequests.php'; 
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
                <br><br>
                <?php
                    $personId = $_SESSION['person_id'];
                    $friendship_id = $_SESSION['selected_friendship_id'];

                    $query = "SELECT relationship_msg, person_id1 FROM relationship WHERE friendship_id = '$friendship_id'";
                    $result = mysqli_query($db, $query);
                    if(!$result) {
                        printf("Error: select from relationship %s\n", mysqli_error($db));
                        exit();
                    }
                    $relationshipRow = mysqli_fetch_array($result);
                    $relationship_msg = $relationshipRow['relationship_msg'];
                    $person_id_sent = $relationshipRow['person_id1'];

                    // get name of that person
                    $query = "SELECT nick_name, person_name, person_surname FROM person WHERE person_id = '$person_id_sent'";
                    $result = mysqli_query($db, $query);
                    if(!$result) {
                        printf("Error: select from person %s\n", mysqli_error($db));
                        exit();
                    }
                    $personRow = mysqli_fetch_array($result);
                    $nickname = $personRow['nick_name'];
                    $firstName = $personRow['person_name'];
                    $lastName = $personRow['person_surname'];

                    echo "<h2>Friendship Request From User : $nickname </h2>";              
                    
                    echo "<form id=\"gameForm\" action=\"\" method=\"post\">";

                    echo "<div class=\"form-group\">
                            <label>Nickname</label>
                            <input type=\"text\" name=\"nickname\" class=\"form-control\" id=\"nickname\" value=\"$nickname\" readonly=\"readonly\">
                        </div>";

                    echo "<div class=\"form-group\">
                        <label>First Name</label>
                        <input type=\"text\" name=\"firstname\" class=\"form-control\" id=\"firstname\" value=\"$firstName\" readonly=\"readonly\">

                    </div>";

                    echo "<div class=\"form-group\">
                            <label>Last Name</label>
                            <input type=\"text\" name=\"lastname\" class=\"form-control\" id=\"lastname\" value=\"$lastName\" readonly=\"readonly\">

                        </div>";

                    echo "<div class=\"form-group\">
                            <label>Message</label>
                            <textarea class=\"form-control\" name=\"friendshipmsg\" id=\"friendshipmsg\" rows=\"8\" readonly=\"readonly\">$relationship_msg</textarea>

                        </div>";

                    echo "<button type=\"submit\"  name =\"accept\" class=\"btn btn-success\">ACCEPT</button>";
                    echo "<button type=\"submit\"  name =\"decline\" class=\"btn btn-danger\" >DECLINE</button>";
                        
                        
                    echo "</form>";



                  
                        
                    ?>
          
            </div>
        </div>
    </div>


    <script type="text/javascript">

    </script>
</body>
</html>