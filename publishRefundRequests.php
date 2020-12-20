<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $publisher_id = $_SESSION['publisher_id'];
    $refund_id = $_POST['refund_request'];

    if(isset($_POST['refund_request'])) {
        $_SESSION['selected_refund_id'] = $refund_id;      
        header("location: publishRefundRequestDecision.php");
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
                    <h4 class="navbar-text">Publisher <?php echo htmlspecialchars($_SESSION['publisher_login_name']); ?></h4>

                </div>
                <a href="publisherWelcome.php">Home</a>
                <a href="publishRequest.php">Publish Requests</a>
                <a href="publishRefundRequests.php">Refund Requests</a>      
                <a href="publishRefundRequestHistory.php">Refund Request History</a>
                <a href ="publishRequestHistory.php">Publish Request History </a>
                <a href="publisherMyGames.php">My Games</a>


                <div class="navbar-right">
                    <a href="logout.php">Log Out</a>
                </div>
    </div>
            </div>
            
        </nav>

        
        <div id="centerwrapper">
            <div id="centerdiv">
                <br><br>
                <h1>Refund Requests</h1>


                    
                    <?php
                        // Prepare a select statement
                        $publisher_id = $_SESSION['publisher_id'];

                        $query = "SELECT refund_id, person_id, game_id FROM request WHERE publisher_id = '$publisher_id'";


                        echo "<p><b>Refund Requests : </b></p>";

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
                            <option value = \"filterUserNickName\">User NickName</option>
                            <option value = \"filterDeveloperName\">Developer Name</option>
                            <option value = \"filterBoughtDate\">Bought Date</option>
                            <option value = \"filterBoughtPrice\">Bought Price</option>
                            <option value = \"filterRefundStatus\">Refund Status</option>
                        </select>

                        </div>";

                        echo "<table class=\"table table-lg table-striped\" id=\"myTable\">
                            <tr>
                            <th>Game Name</th>
                            <th>Game Genre</th>
                            <th>User NickName</th>                            
                            <th>Developer Name</th>
                            <th>Bought Date</th>
                            <th>Bought Price</th>
                            <th>Refund Status</th>
                            </tr>";

                        while($hasRow = mysqli_fetch_array($result)) {
                            $refund_id = $hasRow['refund_id'];
                            $person_id = $hasRow['person_id'];
                            $game_id = $hasRow['game_id'];

                            // get game name and game genre from game id
                            $query = "SELECT game_name, game_genre FROM game WHERE game_id = '$game_id'";
                            $result = mysqli_query($db, $query);
                            if (!$result) {
                                printf("Error: getting game name and game genre %s\n", mysqli_error($db));
                                exit();
                            }
                            $gameRow = mysqli_fetch_array($result);
                            $game_name = $gameRow['game_name'];
                            $game_genre = $gameRow['game_genre'];

                            // get the nickname of the user who made that refund request
                            $query = "SELECT nick_name FROM person WHERE person_id = '$person_id'";
                            $result = mysqli_query($db, $query);
                            if (!$result) {
                                printf("Error: getting person name %s\n", mysqli_error($db));
                                exit();
                                
                            }
                            $personRow = mysqli_fetch_array($result);
                            $user_name = $personRow['nick_name'];

                            // get developer name
                            $queryDeveloperId = "SELECT developer_id FROM updategame WHERE game_id = '$game_id' " ;
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

                            // get shop_id and refund_status from refund_id
                            $query = "SELECT shop_id, refund_approval FROM refundhistory WHERE refund_id = '$refund_id'";
                            $res = mysqli_query($db, $query);
                            
                            if(!$res) {
                                printf("Error5: %s\n", mysqli_error($db));
                                exit();
                            }
                            $refundRow = mysqli_fetch_array($res);
                            $shop_id = $refundRow['shop_id'];
                            $refund_approval = $refundRow['refund_approval'];

                            // get bought date and price from shop_id
                            $query = "SELECT bought_date, bought_price FROM shophistory WHERE shop_id = '$shop_id'";
                            $res = mysqli_query($db, $query);

                            if(!$res) {
                                printf("Error5: %s\n", mysqli_error($db));
                                exit();
                            }
                            $shopRow = mysqli_fetch_array($res);
                            $bought_date = $shopRow['bought_date'];
                            $bought_price = $shopRow['bought_price'];

                            if($refund_approval == 'Waiting for Approval') {
                            
                            echo "<form action=\"\" METHOD=\"POST\">";
                            echo "<tr>";
                            echo "<td>" . $game_name . "</td>";
                            echo "<td>" . $game_genre . "</td>";
                            echo "<td>" . $user_name . "</td>";
                            echo "<td>" . $developer_name . "</td>";
                            echo "<td>" . $bought_date . "</td>";
                            echo "<td>" . $bought_price . "</td>";
                            echo "<td>" . $refund_approval . "</td>";
                           
                            echo "<td> 
                            <button type=\"submit\" onclick=\"checkEmpty()\" name = \"refund_request\"class=\"btn btn-success btn-sm\" value =".$refund_id.">REVIEW</button>
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

            else if(filterTypeVal === "filterUserNickName") {
                index = 2;
            }
            else if(filterTypeVal === "filterDeveloperName") {
                index = 3;
            }
            else if(filterTypeVal === "filterBoughtDate") {
                index = 4;
            }
            else if(filterTypeVal === "filterBoughtPrice") {
                index = 5;
            }
            else if(filterTypeVal === "filterRefundStatus") {
                index = 6;
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