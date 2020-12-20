<?php

//config inclusion session starts
include("config.php");
session_start();

/*
if($_SERVER["REQUEST_METHOD"] == "POST") {

}
*/
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
                <h1>Refund History</h1>
                    
                    <?php
                        $person_id = $_SESSION['person_id'];
                        // Prepare a select statement
                        $query = "SELECT refund_id, game_id, publisher_id FROM request WHERE person_id = '$person_id'";


                        echo "<p><b>Bought Games : </b></p>";

                        $result = mysqli_query($db, $query);

                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($db));
                            exit();
                        }

                        echo "<div class=\"form-group\">
                        <input type=\"text\" id=\"myInput\" onkeyup=\"myFunction()\" placeholder=\"Search for value & col type..\">
                        <select id = \"filterType\">
                            <option value =\"filterGameName\" selected=\"selected\">Game Name</option>
                            <option value = \"filterBoughtDate\">Bought Date</option>
                            <option value = \"filterBoughtPrice\">Bought Price</option>
                            <option value = \"filterCurrentPrice\">Current Price</option>
                            <option value = \"filterPublisherName\">Publisher Name</option> 
                            <option value = \"filterRefundStatus\">Refund Status</option>                            
                        </select>

                        </div>";

                        echo "<table class=\"table table-lg table-striped\" id=\"myTable\">
                            <tr>
                            <th>Game Name</th>
                            <th>Bought Date</th>
                            <th>Bought Price</th>
                            <th>Current Price</th>
                            <th>Publisher Name</th>
                            <th>Refund Status</th>

                            </tr>";

                        while($hasRow = mysqli_fetch_array($result)) {
                            $refund_id = $hasRow['refund_id'];
                            $game_id = $hasRow['game_id'];
                            $publisher_id = $hasRow['publisher_id'];

                            // get game name from game id;
                            $queryGame = "SELECT game_name, game_price FROM game WHERE game_id = '$game_id'";
                            $result2 = mysqli_query($db, $queryGame);
                            if(!$result2) {
                                printf("Error1: %s\n", mysqli_error($db));
                                exit();
                            }
                            $gameRow =  mysqli_fetch_array($result2);
                            $game_name = $gameRow['game_name'];
                            $current_price = $gameRow['game_price'];


                            // get publisher name
                            $queryPublisherId = "SELECT publisher_id FROM publishgame WHERE game_id = '$game_id'";
                            $result3 = mysqli_query($db, $queryPublisherId);
                            if(!$result3) {
                                printf("Error2: %s\n", mysqli_error($db));
                                exit();
                            }
                            $publisherIdRow = mysqli_fetch_array($result3);
                            $publisher_id = $publisherIdRow['publisher_id'];

                            $queryPublisherName = "SELECT publisher_name FROM publisher WHERE publisher_id = '$publisher_id'";
                            $result4 = mysqli_query($db, $queryPublisherName);
                            if(!$result4) {
                                printf("Error3: %s\n", mysqli_error($db));
                                exit();
                            }
                            $publisherNameRow = mysqli_fetch_array($result4);
                            $publisher_name = $publisherNameRow['publisher_name'];



                            $query = "SELECT shop_id, refund_approval FROM refundhistory WHERE refund_id = '$refund_id'";
                            $res = mysqli_query($db, $query);
                            if(!$res) {
                                printf("Error1: %s\n", mysqli_error($db));
                                exit();
                            }
                            $refundHistoryRow = mysqli_fetch_array($res);
                            $shop_id = $refundHistoryRow['shop_id'];
                            $refund_approval = $refundHistoryRow['refund_approval'];


                            // get bought date and bought price
                            $query = "SELECT bought_date, bought_price FROM shophistory WHERE shop_id = '$shop_id'";
                            $res = mysqli_query($db, $query);
                            if(!$res) {
                                printf("Error1: %s\n", mysqli_error($db));
                                exit();
                            }

                            $shopRow =  mysqli_fetch_array($res);
                            $bought_price = $shopRow['bought_price'];
                            $bought_date = $shopRow['bought_date'];
                            

                                            
                            echo "<form action=\"\" METHOD=\"POST\">";
                            echo "<tr>";
                            echo "<td>" . $game_name . "</td>";
                            echo "<td>" . $bought_date . "</td>";
                            echo "<td>" . $bought_price . "</td>";
                            echo "<td>" . $current_price . "</td>";
                            echo "<td>" . $publisher_name . "</td>";
                            echo "<td>" . $refund_approval . "</td>";

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
            else if(filterTypeVal === "filterBoughtDate") {
                index = 1;
            }

            else if(filterTypeVal === "filterBoughtPrice") {
                index = 2;
            }
            else if(filterTypeVal === "filterCurrentPrice") {
                index = 3;
            }
            else if(filterTypeVal === "filterPublisherName") {
                index = 4;
            }
            else if(filterTypeVal === "filterRefundStatus") {
                index = 5;
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