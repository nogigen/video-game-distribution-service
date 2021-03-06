<?php

//config inclusion session starts
include("config.php");
session_start();


if($_SERVER["REQUEST_METHOD"] == "POST") {
    
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
                    echo "<a href='showFollowers.php'>Followers : $followers</a>";
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
                <h1>Followers</h1>

                <form id="gameForm" action="" method="post">

                    
                    <?php
                        // Prepare a select statement
                        $query = "SELECT nick_name, person_name, person_surname FROM curator NATURAL JOIN person NATURAL JOIN follow WHERE curator_id =" .$_SESSION['curator_id'];

                        $result = mysqli_query($db, $query);

                        if (!$result) {
                            printf("Error: %s\n", mysqli_error($db));
                            exit();
                        }

                        echo "<div class=\"form-group\">
                        <input type=\"text\" id=\"myInput\" onkeyup=\"myFunction()\" placeholder=\"Search for value & col type..\">
                        <select id = \"filterType\">
                            <option value =\"filterNickName\" selected=\"selected\">Nickname</option>
                            <option value = \"filterFirstName\">First Name</option>
                            <option value = \"filterLastName\">Last Name</option>
                        </select>

                        </div>";

                        echo "<table class=\"table table-lg table-striped\" id=\"myTable\">
                            <tr>
                            <th>Nickname</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            </tr>";

                        while($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['nick_name'] . "</td>";
                            echo "<td>" . $row['person_name'] . "</td>";
                            echo "<td>" . $row['person_surname'] . "</td>";
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
            if(filterTypeVal === "filterNickName") {
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