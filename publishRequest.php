<?php

//config inclusion session starts
include("config.php");
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $gameName = $_POST['gamename'];
    $gameGenre = $_POST['gamegenre'];
    $developer_id = $_POST['developerid'];
    $publisherId = $_SESSION['publisher_id'];
    

    if( isset($_POST['select_approve']) )
    {
        $gameDesc = $_POST['select_approve'];

        /*
        $accepted_query = "UPDATE ask
        SET approval = 'Accepted'
        WHERE ask_game_name = '$gameName' AND publisher_id = '$publisherId'";
        $result = mysqli_query($db,$accepted_query);

        $publish_game_query = "INSERT INTO game(game_name, game_genre, game_desc) VALUES ('$gameName', '$gameGenre', '$gameDesc')";
        $result = mysqli_query($db,$publish_game_query);

        //GET GAME ID
        $queryGameId = "SELECT game_id FROM game WHERE game_name = '$gameName'";
        $result3 = mysqli_query($db, $queryGameId);
        $gameIdRow = mysqli_fetch_array($result3);
        $gameId = $gameIdRow['game_id'];

        //ADD TO PUBLISH GAME
        $publish_game_query_2 = "INSERT INTO publishgame(publisher_id, game_id, discount) VALUES ('$publisherId', '$gameId', 0)";
        $result = mysqli_query($db,$publish_game_query_2);

        //ADD TO UPDATEGAME INITIAL
        $insert_to_update_query = "INSERT INTO updateGame(game_id, developer_id, update_desc, new_version_no) VALUES ('$gameId','$developerId', '', 1)";
        $result = mysqli_query($db,$insert_to_update_query);
        */
        $_SESSION['selected_developer_id'] = $developer_id;
        $_SESSION['selected_ask_game_name'] = $gameName;

        $query = "SELECT req_id FROM ask WHERE publisher_id = '$publisherId' and developer_id = '$developer_id' and ask_game_name = '$gameName' and ask_game_genre = '$gameGenre' and ask_game_desc = '$gameDesc'";
        $result = mysqli_query($db,$query);

        if (!$result) {
            printf("Error: System Requirement select is failed. %s\n", mysqli_error($db));
            exit();
        }

        $reqRow = mysqli_fetch_array($result);
        $req_id = $reqRow['req_id'];

        $_SESSION['selected_req_id'] = $req_id;



        header("location: publishRequestDecision.php");


    }
    else{
    
        $declined_query = "UPDATE ask
        SET approval = 'Declined'
        WHERE ask_game_name = '$gameName' AND publisher_id = '$publisherId' AND developer_id ='$developer_id'";

        $result = mysqli_query($db,$declined_query);
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
                <h1>Publish Requests</h1>

                <form id="gameForm" action="" method="post">

    
                    
                    <?php
                        // Prepare a select statement
                        $query = "SELECT ask_game_name, ask_game_desc, ask_game_genre, developer_id FROM ask WHERE approval = 'Waiting for Approval' and  publisher_id = " .$_SESSION['publisher_id'];

                        echo "<p><b>Requested Games:</b></p>";

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
                            <option value = \"filterDeveloperName\">Developer Name</option>
                        </select>

                        </div>";

                        echo "<table class=\"table table-lg table-striped\" id=\"myTable\">
                            <tr>
                            <th>Game Name</th>
                            <th>Game Genre</th>
                            <th>Developer Name</th>
                            
                            <th>        </th>
                            <th>        </th>
                            </tr>";
                            
                        while($row = mysqli_fetch_array($result)) {

                            // get developer name from developer id
                            $developer_id = $row['developer_id'];
                            $query = "SELECT developer_name FROM developer WHERE developer_id = '$developer_id'";
                            $result2 = mysqli_query($db, $query);
                            
                            if (!$result) {
                                printf("Error: %s\n", mysqli_error($db));
                                exit();
                            }
                            $developerRow = mysqli_fetch_array($result2);
                            $developer_name = $developerRow['developer_name'];


                            echo "<form action=\"\" METHOD=\"POST\">";
                            echo "<tr>";
                            echo "<td><input type=\"hidden\" name=\"gamename\" value=". $row['ask_game_name'] .">" . $row['ask_game_name'] . "</td>";
                            echo "<td><input type=\"hidden\" name=\"gamegenre\" value=". $row['ask_game_genre'] .">" . $row['ask_game_genre'] . "</td>";
                            echo "<td><input type=\"hidden\" name=\"developerid\" value=". $row['developer_id'] .">" . $developer_name . "</td>";
                                echo "<td> 
                                    <button type = \"submit\" onclick=\"approved()\" name = \"select_approve\"class=\"btn btn-success btn-sm\"  value =".$row['ask_game_desc'] .">APPROVE</button>
                                    
                                </td></form>";
                            
                            echo "<td>
                                <button onclick=\"cancelled()\" name = \"select_cancel\"class=\"btn btn-danger btn-sm\" value =".$row['ask_game_name'] .">CANCEL</button>
                                </td>";
                            echo "</tr>";
                        }

                        echo "</table>";
                        ?>
                </form>  
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

            else if(filterTypeVal === "filterDeveloperName") {
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