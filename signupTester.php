<?php

//config inclusion session starts
include("config.php");
session_start();

//defining necessary variables
$username = "";
$password = "";
$confirmPassword = "";
$email = "";
$firstName = "";
$lastName = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["nick_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];

    $sql = "INSERT INTO tester(tester_login_name, tester_email, tester_password, tester_first_name, tester_last_name) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $username, $email, $password, $firstName, $lastName);
    mysqli_stmt_execute($stmt);
    header("location: index.php");
    
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
                    <h4 class="navbar-text">Video Game Digital Distribution System</h4>

                </div>
                <a href="index.php">Home</a>
                <a href="signupUser.php">Sign Up</a>
    </div>
            </div>
        </nav>
        <div id="centerwrapper">
            <div id="centerdiv">
                <br><br>
                <h2>Tester Sign Up</h2>
                
                <p>Choose Your Role: 
                <form action="">
                    <input type="radio" name="user_type" value="user" onclick = "document.location.href='signupUser.php'"> User
                    <input type="radio" name="user_type" value="curator" onclick = "document.location.href='signupCurator.php'"> Curator
                    <input type="radio" name="user_type" value="publisher" onclick = "document.location.href='signupPublisher.php'"> Publisher
                    <input type="radio" name="user_type" value="developer"onclick = "document.location.href='signupDeveloper.php'"> Developer
                    <input type="radio" name="user_type" value="tester" checked="checked"> Tester
                </form>
                <form id="signupForm" action="" method="post">
                    <div class="form-group">
                        <label>Tester Login Name</label>
                        <input type="text" name="nick_name" class="form-control" id="nick_name">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control" id="email">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" id="password">
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" id="confirm_password">
                    </div>

                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="firstname" class="form-control" id="firstname">
                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="lastname" class="form-control" id="lastname">
                    </div>

                    <div class="form-group">
                        <input onclick="checkEmptyAndLogin()" class="btn btn-primary" value="Sign Up">
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function checkEmptyAndLogin() {
            var usernameVal = document.getElementById("nick_name").value;
            var passwordVal = document.getElementById("password").value;
            var confirmPasswordVal = document.getElementById("confirm_password").value;
            var emailVal = document.getElementById("email").value;
            var firstnameVal = document.getElementById("firstname").value;
            var lastnameVal = document.getElementById("lastname").value;
            
            if (usernameVal === "" || passwordVal === "" || confirmPasswordVal === "" || emailVal === "" || firstnameVal === "" || lastnameVal === "") {
                alert("Make sure to fill all fields");
            }
            else if (passwordVal != confirmPasswordVal) {
                alert("Passwords are not the same");
            }
            else {
                var form = document.getElementById("signupForm").submit();
            }
        }
    </script>
</body>
</html> 