<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $person_id = $_SESSION['person_id'];

        /*
        echo "<script LANGUAGE='JavaScript'>
        window.alert('it should never come here :D.');
        </script>";
        */
    if(isset($_POST['unfriend'])) {
        $friendship_id = $_POST['unfriend'];

        // delete relationship
        
        $query = "DELETE FROM relationship
        WHERE friendship_id = '$friendship_id'";

        $result = mysqli_query($db, $query);
        if(!$result) {
            printf("Error: Deleting relationship. %s\n", mysqli_error($db));
            exit();
        }

        // delete friendship
        $query = "DELETE FROM friendship
                    WHERE friendship_id = '$friendship_id'";


        $result = mysqli_query($db, $query);
        if(!$result) {
            printf("Error: Deleting friendship. %s\n", mysqli_error($db));
            exit();
        }



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
                <h1>My Friends</h1>


                    
                    <?php
                        // Prepare a select statement
                        $person_id = $_SESSION['person_id'];
                        $query = "SELECT person_id1, person_id2, friendship_id FROM relationship WHERE (person_id1 = '$person_id' or person_id2 = '$person_id') and relationship_status = 'Accepted'"; 


                        echo "<p><b>Your Friends : </b></p>";

                        $result = mysqli_query($db, $query);

                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($db));
                            exit();
                        }

                        echo "<div class=\"form-group\">
                        <input type=\"text\" id=\"myInput\" onkeyup=\"myFunction()\" placeholder=\"Search for value & col type..\">
                        <select id = \"filterType\">
                            <option value =\"filterNickname\" selected=\"selected\">Nickname</option>
                            <option value = \"filterFirstName\">First Name</option>
                            <option value = \"filterLastName\">Last Name</option>
                        </select>

                        </div>";


                        echo "<table class=\"table table-lg table-striped\" id=\"myTable\">
                            <tr>
                            <th>Nickname</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th></th>
                            </tr>";

                        while($hasRow = mysqli_fetch_array($result)) {
                            $person_id1 = $hasRow['person_id1'];
                            $person_id2 = $hasRow['person_id2'];
                            $friendship_id = $hasRow['friendship_id'];
                        
                            if($person_id1 == $person_id) {
                                $friends_person_id = $person_id2;
                            }
                            else {
                                $friends_person_id = $person_id1;
                            }

                            // get friends nickname,first name and last name
                            $query = "SELECT nick_name, person_name, person_surname FROM person WHERE person_id = '$friends_person_id'";
                            $res = mysqli_query($db, $query);

                            if (!$res) {
                                printf("Error: %s\n", mysqli_error($db));
                                exit();
                            }
                            $personRow = mysqli_fetch_array($res);
                            $nickname = $personRow['nick_name'];
                            $firstName = $personRow['person_name'];
                            $lastName = $personRow['person_surname'];
    
                                            
                            echo "<form action=\"\" METHOD=\"POST\">";
                            echo "<tr>";
                            echo "<td>" . $nickname . "</td>";
                            echo "<td>" . $firstName . "</td>";
                            echo "<td>" . $lastName . "</td>";
                            
                            echo "<td><button type=\"submit\" onclick=\"checkEmpty()\" name =\"unfriend\"class=\"btn btn-danger btn-sm\" value=\"$friendship_id\">UNFRIEND</button></td>";
                            
                            

                            echo "</tr>";
                            
                            echo "</form>";
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
            if(filterTypeVal === "filterNickname") {
                index = 0;
            }
            else if(filterTypeVal === "filterFirstName") {
                index = 1;
            }

            else if(filterTypeVal === "filterLastName") {
                index = 2;
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