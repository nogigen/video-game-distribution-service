<?php

//config inclusion session starts
include("config.php");
session_start();

//defining necessary variables
$username = "";
$password = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form
    $username = mysqli_real_escape_string($db,$_POST["username"]);
    $password = mysqli_real_escape_string($db,$_POST['password']);
    $userType = $_POST["user_type"];

    if($userType == "user"){
        $sql = "SELECT nick_name, password FROM person WHERE nick_name = ? and password = ?";
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $entered_username, $entered_password);

            //set parameters
            $entered_username = $username;
            $entered_password = $password;

            //executing the statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $username, $turned_pw);

                    if(mysqli_stmt_fetch($stmt)){
                        if($turned_pw == $password){
                            //inputs are correct session is starting
                            session_start();
                            $_SESSION['nick_name'] = $username;
                            $_SESSION['password'] = $password;
                            header("location: userWelcome.php");
                        }
                    }
                }else{
                    //wrong input
                    echo "<script type='text/javascript'>alert('Invalid Username or Password.');</script>";
                }
            }
        }
    }

    else if($userType == "curator"){
        $sql = "SELECT curator_login_name, curator_password FROM curator WHERE curator_login_name = ? and curator_password = ?";
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $entered_username, $entered_password);

            //set parameters
            $entered_username = $username;
            $entered_password = $password;

            //executing the statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $username, $turned_pw);

                    if(mysqli_stmt_fetch($stmt)){
                        if($turned_pw == $password){
                            //inputs are correct session is starting
                            session_start();
                            $_SESSION['curator_login_name'] = $username;
                            $_SESSION['curator_password'] = $password;
                            header("location: curatorWelcome.php");
                        }
                    }
                }else{
                    //wrong input
                    echo "<script type='text/javascript'>alert('Invalid Username or Password.');</script>";
                }
            }
        }
    }
    else if($userType == "publisher"){
        $sql = "SELECT publisher_login_name, publisher_password FROM publisher WHERE publisher_login_name = ? and publisher_password = ?";
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $entered_username, $entered_password);

            //set parameters
            $entered_username = $username;
            $entered_password = $password;

            //executing the statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $username, $turned_pw);

                    if(mysqli_stmt_fetch($stmt)){
                        if($turned_pw == $password){
                            //inputs are correct session is starting
                            session_start();
                            $_SESSION['publisher_login_name'] = $username;
                            $_SESSION['publisher_password'] = $password;
                            header("location: publisherWelcome.php");
                        }
                    }
                }else{
                    //wrong input
                    echo "<script type='text/javascript'>alert('Invalid Username or Password.');</script>";
                }
            }
        }
    }
    else if($userType == "developer"){
        $sql = "SELECT developer_login_name, developer_password FROM developer WHERE developer_login_name = ? and developer_password = ?";
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $entered_username, $entered_password);

            //set parameters
            $entered_username = $username;
            $entered_password = $password;

            //executing the statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $username, $turned_pw);

                    if(mysqli_stmt_fetch($stmt)){
                        if($turned_pw == $password){
                            //inputs are correct session is starting
                            session_start();
                            $_SESSION['developer_login_name'] = $username;
                            $_SESSION['developer_password'] = $password;
                            header("location: developerWelcome.php");
                        }
                    }
                }else{
                    //wrong input
                    echo "<script type='text/javascript'>alert('Invalid Username or Password.');</script>";
                }
            }
        }
    }
    else if($userType == "tester"){
        $sql = "SELECT tester_login_name, tester_password FROM tester WHERE tester_login_name = ? and tester_password = ?";
        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $entered_username, $entered_password);

            //set parameters
            $entered_username = $username;
            $entered_password = $password;

            //executing the statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $username, $turned_pw);

                    if(mysqli_stmt_fetch($stmt)){
                        if($turned_pw == $password){
                            //inputs are correct session is starting
                            session_start();
                            $_SESSION['tester_login_name'] = $username;
                            $_SESSION['tester_password'] = $password;
                            header("location: testerWelcome.php");
                        }
                    }
                }else{
                    //wrong input
                    echo "<script type='text/javascript'>alert('Invalid Username or Password.');</script>";
                }
            }
        }
    }
    

    // Close statement
    mysqli_stmt_close($stmt);
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
                <h2>Login</h2>
                <p>Choose Your Role: 
                <form id="role" action="" method="post">
                    
                </form>
                <form id="loginForm" action="" method="post">
                <input type="radio" name="user_type" value="user" checked="checked"> User
                    <input type="radio" name="user_type" value="curator"> Curator
                    <input type="radio" name="user_type" value="publisher"> Publisher
                    <input type="radio" name="user_type" value="developer"> Developer
                    <input type="radio" name="user_type" value="tester"> Tester
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" id="username">

                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" id="password">

                    </div>
                    <div class="form-group">
                        <input onclick="checkEmptyAndLogin()" class="btn btn-primary" value="Login">
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        function checkEmptyAndLogin() {
            var usernameVal = document.getElementById("username").value;
            var passwordVal = document.getElementById("password").value;
            if (usernameVal === "" || passwordVal === "") {
                alert("Make sure to fill all fields!");
            }
            else {
                var form = document.getElementById("loginForm").submit();
            }
        }
    </script>
</body>
</html>