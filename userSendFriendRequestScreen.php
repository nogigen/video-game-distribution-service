<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $person_id = $_SESSION['person_id'];
    $other_person_id = $_SESSION['other_person_id'];


    if(isset($_POST['add'])) {
        $message = $_POST['message'];

        // check to see if that user already sent you a request or not.
        $query = "SELECT person_id1 FROM relationship WHERE person_id1 = '$other_person_id' and person_id2 = '$person_id' and relationship_status = 'Waiting for Approval'";
        $res = mysqli_prepare($db, $query);
        if(!$res) {
            printf("Error: %s\n", mysqli_error($db));
            exit();
        }
        mysqli_stmt_execute($res);
        mysqli_stmt_store_result($res);
        $numberOfRows = mysqli_stmt_num_rows($res);

        if($numberOfRows != 0) {
            $query = "UPDATE relationship
                      SET relationship_message = '$message', relationship_status = 'Waiting for Approval'
                      WHERE person_id1 = '$other_person_id' and person_id2 = '$person_id'";

            $result = mysqli_query($db,$query);
            if(!$result) {
                printf("Error: Updating relationship table %s\n", mysqli_error($db));
                exit();
            }
    
        }

        else {
            // check to see if this relationship already exists.
            // if it's change status from declined to waiting for approval.
            $query = "SELECT person_id1 FROM relationship WHERE person_id1 = '$person_id' and person_id2 = '$other_person_id'";
            $res = mysqli_prepare($db, $query);
            if(!$res) {
                printf("Error: %s\n", mysqli_error($db));
                exit();
            }
            mysqli_stmt_execute($res);
            mysqli_stmt_store_result($res);
            $numberOfRows = mysqli_stmt_num_rows($res);

            if($numberOfRows != 0) {
                $query = "UPDATE relationship
                SET relationship_msg = '$message', relationship_status = 'Waiting for Approval'
                WHERE person_id1 = '$person_id' and person_id2 = '$other_person_id'";

                $result = mysqli_query($db,$query);
                if(!$result) {
                    printf("Error: 2- Updating relationship table %s\n", mysqli_error($db));
                    exit();
                }
            }
            else {
                // else
                // insert friendship
                $query = "INSERT INTO friendship VALUES(DEFAULT)";
                $result = mysqli_query($db,$query);
                if(!$result) {
                    printf("Error: Inserting into friendship %s\n", mysqli_error($db));
                    exit();
                }

                // get last friendship instance
                $query = "SELECT MAX(friendship_id) as friendship_id FROM friendship";
                $result = mysqli_query($db,$query);        
                if(!$result) {
                    printf("Error: Getting last row of friendship %s\n", mysqli_error($db));
                    exit();
                }
                $friendshipRow =  mysqli_fetch_array($result);
                $friendship_id = $friendshipRow['friendship_id'];

                // insert into relationship
                $query = "INSERT INTO relationship VALUES ('$friendship_id', '$person_id', '$other_person_id', DEFAULT, '$message')";
                $result = mysqli_query($db,$query);        
                if(!$result) {
                    printf("Error: Inserting into relationship %s\n", mysqli_error($db));
                    exit();
                }
        }

    }

        header("location: userSendFriendRequests.php");


    }
    else if(isset($_POST['cancel'])) {
        header("location: userSendFriendRequests.php");
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
                <h1>Friend Request</h1>

                <?php
                    $person_id = $_SESSION['person_id'];
                    $other_person_id = $_SESSION['other_person_id'];

                    // get the nick name, first name and last name of that user
                    $query = "SELECT nick_name, person_name, person_surname FROM person WHERE person_id = '$other_person_id'";
                    $res = mysqli_query($db, $query);

                    if(!$res) {
                        printf("Error: %s\n", mysqli_error($db));
                        exit();
                    }
                    $personRow = mysqli_fetch_array($res);
                    $nickname = $personRow['nick_name'];
                    $firstName = $personRow['person_name'];
                    $lastName = $personRow['person_surname'];
                    

                    echo "<form id=\"gameForm\" action=\"\" method=\"post\">";

                    echo "<div class=\"form-group\">
                            <label>User Nickname</label>
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
                            <textarea class=\"form-control\" name=\"message\" id=\"message\" rows=\"8\"></textarea>

                        </div>";

                    echo "<button type=\"submit\"  name =\"add\" class=\"btn btn-success\">SEND</button>";
                    echo "<button type=\"submit\"  name =\"cancel\" class=\"btn btn-danger\">CANCEL</button>";
                        
                        
                    echo "</form>";
                ?>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function checkEmpty() {

            }
        }
    </script>
</body>
</html>