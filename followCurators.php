<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    
    

    if(isset($_POST['follow'])) {

        $curatorId = $_POST['follow'];
        $personId = $_SESSION['person_id'];

        $queryC = "SELECT no_of_followers FROM curator WHERE curator_id = '$curatorId'";
        $res = mysqli_query($db, $queryC);
        $cRow = mysqli_fetch_array($res);
        $noOfFollowers = $cRow['no_of_followers'];


        //UPDATE FOLLOWER NO
        $update_version_no_query = "UPDATE curator
        SET no_of_followers = $noOfFollowers + 1
        WHERE curator_id = '$curatorId'";
        $result = mysqli_query($db,$update_version_no_query);

        //INSERT TO FOLLOW TABLE
        $follow_insertion = "INSERT INTO follow(person_id, curator_id) VALUES ('$personId', '$curatorId')";
        $result = mysqli_query($db,$follow_insertion);

        echo "<script LANGUAGE='JavaScript'>
                window.location.href = 'followCurators.php'; 
                </script>";

    }

    else if(isset($_POST['unfollow'])) {

        $curatorId = $_POST['unfollow'];
        $personId = $_SESSION['person_id'];


        $queryC = "SELECT no_of_followers FROM curator WHERE curator_id = '$curatorId'";
        $res = mysqli_query($db, $queryC);
        $cRow = mysqli_fetch_array($res);
        $noOfFollowers = $cRow['no_of_followers'];


        //UPDATE FOLLOWER NO
        $update_version_no_query = "UPDATE curator
        SET no_of_followers = $noOfFollowers - 1
        WHERE curator_id = '$curatorId'";
        $result = mysqli_query($db,$update_version_no_query);

        //DELETE FOLLOW TABLE
        $follow_insertion = "DELETE FROM follow WHERE curator_id = '$curatorId' AND person_id = '$personId'";
        $result = mysqli_query($db,$follow_insertion);

        echo "<script LANGUAGE='JavaScript'>
                window.location.href = 'followCurators.php'; 
                </script>";

    }

    else if(isset($_POST['seeprofile'])) {

        $curatorId = $_POST['seeprofile'];

        $query = "SELECT curator_login_name FROM curator WHERE curator_id = '$curatorId'";
        $res = mysqli_query($db, $query);
        $curatorNameRow = mysqli_fetch_array($res);
        $curatorName = $curatorNameRow['curator_login_name'];



        $_SESSION['curator_id'] = $curatorId;
        $_SESSION['curator_name'] = $curatorName;


        header("location: userSeeCuratorProfile.php");

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
                <a href="userRefundHistory.php">Refund History</a>
                <a href="userShopHistory.php">Shop History</a>
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
                <h1>Curators</h1>

                    <?php
                        // Prepare a select statement
                        $query = "SELECT curator_id, curator_first_name, curator_last_name, no_of_followers FROM curator";


                        echo "<p><b>Available Curators: </b></p>";

                        $result = mysqli_query($db, $query);

                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($db));
                            exit();
                        }

                        echo "<table class=\"table table-lg table-striped\">
                            <tr>
                            <th>Curator First Name</th>
                            <th>Curator Last Name</th>
                            <th>No. of Followers</th>
                            </tr>";

                        while($row = mysqli_fetch_array($result)) {

                            $curatorId = $row['curator_id'];
                            $personId = $_SESSION['person_id'];

                            $is_ever_followed = "SELECT person_id FROM follow WHERE curator_id = '$curatorId' and person_id = '$personId'";
                            $res = mysqli_prepare($db, $is_ever_followed);
                            mysqli_stmt_execute($res);
                            mysqli_stmt_store_result($res);
                            $numberOfRows = mysqli_stmt_num_rows($res);

                            $isFollowed = TRUE;

                        
                            if($numberOfRows == 0){
                                $isFollowed = FALSE;
                            }
                    
                            echo "<form action=\"\" METHOD=\"POST\">";                 
                            echo "<tr>";
                            echo "<td>" . $row['curator_first_name'] . "</td>";
                            echo "<td>" . $row['curator_last_name'] . "</td>";
                            echo "<td>" . $row['no_of_followers'] . "</td>";
                            echo "<td>"; 
                            
                            if($isFollowed) {
                                echo "<td> 
                                    <button type=\"submit\" onclick=\"checkEmpty()\" name = \"unfollow\"class=\"btn btn-danger btn-sm\" value =". $row['curator_id'] .">UNFOLLOW</button>
                                </td>";

                                echo "<td> 
                                <button type=\"submit\" onclick=\"checkEmpty()\" name = \"seeprofile\"class=\"btn btn-success btn-sm\" value =". $row['curator_id'] .">SEE PROFILE</button>
                                </td>";

                            }
                            else {
                                echo "<td> 
                                    <button type=\"submit\" onclick=\"checkEmpty()\" name = \"follow\"class=\"btn btn-success btn-sm\" value =". $row['curator_id'] .">FOLLOW</button>
                                </td>";

                                echo "<td> 
                                <button type=\"submit\" onclick=\"checkEmpty()\" name = \"seeprofile\"class=\"btn btn-success btn-sm\" value =". $row['curator_id'] .">SEE PROFILE</button>
                                </td>";
                            }
                            echo "</tr>";
                            echo "</form>";
                        }

                        echo "</table>";
                        
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