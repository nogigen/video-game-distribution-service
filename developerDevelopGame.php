<?php

//config inclusion session starts
include("config.php");
session_start();

function isEmpty($inputStr) {
    if (isset($inputStr) && (string) $inputStr !== '') {
        return false;
    }
    return true;
}



if($_SERVER["REQUEST_METHOD"] == "POST") {


    $publisherId = $_POST['selected_publisher_id'];

    $developerId = $_SESSION["developer_id"];


    $gameName = $_POST['gamename'];
    $gameGenre = $_POST['gamegenre'];
    $gameDesc = $_POST['gamedesc'];
    $os = $_POST['operatingsystem'];
    $memory = $_POST['memory'];
    $storage = $_POST['memory'];
    $processor = $_POST['processor'];

    $query = "SELECT game_id FROM game WHERE game_name = '$gameName'";
    $res = mysqli_prepare($db, $query);
    mysqli_stmt_execute($res);
    mysqli_stmt_store_result($res);
    $numberOfRows = mysqli_stmt_num_rows($res);

    if($numberOfRows != 0) {
        echo "<script LANGUAGE='JavaScript'>
        window.alert('There is a game with that name in the store. Choose a different game name.');
        window.location.href = 'developerDevelopGame.php'; 
        </script>";
    }

    elseif(isEmpty($gameName) || isEmpty($gameGenre) || isEmpty($gameDesc) || isEmpty($os) || isEmpty($memory) || isEmpty($storage) || isEmpty($processor)) {
        echo "<script LANGUAGE='JavaScript'>
        window.alert('You should fill all the boxes!');
        window.location.href = 'developerDevelopGame.php'; 
        </script>";
    }

    else {
    
        //
        $waitOrAccepted = "SELECT ask_game_name from ask WHERE ask_game_name = '$gameName' and (approval = 'Waiting for Approval' or approval = 'Accepted')";
        $res = mysqli_prepare($db, $waitOrAccepted);
        mysqli_stmt_execute($res);
        mysqli_stmt_store_result($res);
        $numberOfRows = mysqli_stmt_num_rows($res);

        if($numberOfRows == 0){

            $waitOrAccepted = "SELECT ask_game_name from ask WHERE ask_game_name = '$gameName' and approval = 'Declined' and publisher_id = '$publisherId' and developer_id = '$developerId'";
            $res = mysqli_prepare($db, $waitOrAccepted);
            mysqli_stmt_execute($res);
            mysqli_stmt_store_result($res);
            $numberOfRows = mysqli_stmt_num_rows($res);

            if($numberOfRows == 0){

                // create a system requirements if not already exists.
                $query = "SELECT req_id FROM systemrequirements WHERE os = '$os' and memory = '$memory' and processor = '$processor' and storage = '$storage'";
                $res = mysqli_prepare($db, $query);
                mysqli_stmt_execute($res);
                mysqli_stmt_store_result($res);
                $rows = mysqli_stmt_num_rows($res);

                if($rows == 0) {
                    // insert into systemrequirements
                    $query = "INSERT INTO systemrequirements VALUES (DEFAULT, '$os', '$processor', '$memory', '$storage')";
                    $result = mysqli_query($db, $query);
                    if (!$result) {
                        printf("Error: Inserting into systemrequirements table. %s\n", mysqli_error($db));
                        exit();
                    }

                    // get req id
                    $query = "SELECT MAX(req_id) as req_id FROM systemrequirements";
                    $result = mysqli_query($db, $query);
                    if (!$result) {
                        printf("Error: Selecting max req_id from systemrequirements table. %s\n", mysqli_error($db));
                        exit();
                    }
                    $sysReqRow = mysqli_fetch_array($result);
                    $req_id = $sysReqRow['req_id'];


                }
                else {
                    // select that systemrequirements 
                    $query = "SELECT req_id FROM systemrequirements WHERE os = '$os' and memory = '$memory' and processor = '$processor' and storage = '$storage'";
                    $result = mysqli_query($db, $query);
                    if (!$result) {
                        printf("Error: %s\n", mysqli_error($db));
                        exit();
                    }
                    $systemReqRow = mysqli_fetch_array($result);
                    $req_id = $systemReqRow['req_id'];
                }

                //INSERT
                $sql = "INSERT INTO ask(publisher_id, developer_id, req_id, ask_game_name, ask_game_genre, ask_game_desc) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($db, $sql);
                mysqli_stmt_bind_param($stmt, "iiisss", $publisherId, $developerId, $req_id, $gameName, $gameGenre, $gameDesc);
                mysqli_stmt_execute($stmt);
                //header("location: developerWelcome.php");

                echo "<script LANGUAGE='JavaScript'>
                    window.alert('Game is sent to the publisher for approval');
                    window.location.href = 'developerWelcome.php'; 
                    </script>";
            }
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

    <script>
    
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
                if(filterTypeVal === "filterPublisherName") {
                    index = 0;
                }
                else if(filterTypeVal === "filterPublisherID") {
                    index = 1;
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

</head>
<body>
    
    <div class="container">
        
        <nav class="navbar navbar-inverse bg-primary navbar-fixed-top">
        
            <div class="container-fluid">
                <div class="navbar-header">
                    <h4 class="navbar-text">Developer <?php echo htmlspecialchars($_SESSION['developer_login_name']); ?></h4>

                </div>
                <a href="developerWelcome.php">Home</a>
                <a href="developerDevelopGame.php">Develop Game</a>
                <a href="developerPublishedGames.php">Published Games</a>
                <a href="developerCheckApproval.php">Check Approval</a>
                <div class="navbar-right">
                    <a href="logout.php">Log Out</a>
                </div>
    </div>
            </div>
            
        </nav>

        
        <div id="centerwrapper">
            <div id="centerdiv">
                <br><br>
                <h1>Game Specification</h1>

                <form id="gameForm" action="" method="post">

                    <div class="form-group">
                        <label>Game Name</label>
                        <input type="text" name="gamename" class="form-control" id="gamename">

                    </div>
                    <div class="form-group">
                        <label>Game Description</label>
                        <textarea class="form-control" name="gamedesc" id="gamedesc" rows="4"></textarea>

                    </div>
                    <div class="form-group">
                        <label>Game Genre</label>
                        <input type="text" name="gamegenre" class="form-control" id="gamegenre">

                    </div>
                    
                    <br>
                    <h4><b>Minimum System Requirements</b></h4>
                    
                    <div class="form-group">
                        <label>Operating System</label>
                        <input type="text" name="operatingsystem" class="form-control" id="operatingsystem">

                    </div>

                    <div class="form-group">
                        <label>Processor</label>
                        <input type="text" name="processor" class="form-control" id="processor">
                    </div>

                    <div class="form-group">
                        <label>Memory</label>
                        <input type="text" name="memory" class="form-control" id="memory">
                    </div>

                    <div class="form-group">
                        <label>Storage</label>
                        <input type="text" name="storage" class="form-control" id="storage">
                    </div>

                    <?php
                        // Prepare a select statement
                        $query = "SELECT publisher_login_name, publisher_id FROM publisher";

                        echo "<h5><p><b>Available Publishers:</b></p></h5>";

                        $result = mysqli_query($db, $query);

                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($db));
                            exit();
                        }

                        echo "<div class=\"form-group\">
                        <input type=\"text\" id=\"myInput\" onkeyup=\"myFunction()\" placeholder=\"Search for value & col type..\">
                        <select id = \"filterType\">
                            <option value =\"filterPublishername\" selected=\"selected\">Publisher Name</option>
                            <option value = \"filterPublisherID\">Publisher ID</option>
                        </select>

                        </div>";

                        echo "<table class=\"table table-lg table-striped\" id=\"myTable\">
                            <tr>
                            <th>Publisher Name</th>
                            <th>Publisher ID</th>
                            <th>Option</th>
                            </tr>";

                        while($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['publisher_login_name'] . "</td>";
                            echo "<td>" . $row['publisher_id'] . "</td>";
                            echo "<td> 
                                    <button type=\"submit\" onclick=\"checkEmpty()\" name = \"selected_publisher_id\"class=\"btn btn-success btn-sm\"  value =".$row['publisher_id'].">SELECT</button>
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


        function checkEmpty() {
            var gamenameVal = document.getElementById("gamename").value;
            var gamedescVal = document.getElementById("gamedesc").value;
            var gamegenreVal = document.getElementById("gamegenre").value;
            var osVal = document.getElementById("operatingsystem").value;
            var processorVal = document.getElementById("processor").value;
            var memoryVal = document.getElementById("memory").value;
            var storageVal = document.getElementById("storage").value;
            if (gamenameVal.length == 0 || gamedescVal.length = 0 || gamegenreVal.length == 0 || osVal.length == 0 || processorVal.length == 0 || memoryVal.length == 0 || storageVal.length == 0) {
                alert("FILL!");
            }
            else {

                var form = document.getElementById("gameForm").submit();
            }
        }

    </script>
</body>
</html>