<?php

//config inclusion session starts
include("config.php");
session_start();

//defining necessary variables
$gameName = "";
$gameGenre = "";
$gameDesc = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $accepted_query = "UPDATE ask
    SET approval = 'Accepted'
    WHERE developer_id = 1;";

    $declined_query = "UPDATE ask
    SET approval = 'Declined'
    WHERE developer_id = 1;";

    //$result = mysqli_query($db,$accepted_query);
    //$result = mysqli_query($db,$declined);

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
                        $query = "SELECT ask_game_name, ask_game_desc, ask_game_genre FROM ask WHERE publisher_id = 1 and approval = 'Waiting for Approval'";

                        echo "<p><b>Requested Games:</b></p>";

                        $result = mysqli_query($db, $query);

                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($db));
                            exit();
                        }

                        echo "<table class=\"table table-lg table-striped\">
                            <tr>
                            <th>Game Name</th>
                            <th>Game Description</th>
                            <th>Game Genre</th>
                            <th>        </th>
                            <th>        </th>
                            </tr>";
                            
                        while($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['ask_game_name'] . "</td>";
                            echo "<td>" . $row['ask_game_desc'] . "</td>";
                            echo "<td>" . $row['ask_game_genre'] . "</td>";
                            echo "<td> <form action=\"\" METHOD=\"POST\">
                                    <button onclick=\"approved()\" name = \"select_approve\"class=\"btn btn-success btn-sm\"  >APPROVE</button>
                                    </form>
                                </td>";
                            echo "<td> <form action=\"\" METHOD=\"POST\">
                                <button onclick=\"cancelled()\" name = \"selected_cancel\"class=\"btn btn-danger btn-sm\" >CANCEL</button>
                                </form>
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
        function approved() {
            
        }

        function cancelled() {
            
        }
    </script>
</body>
</html>