<?php

$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "dbproject";

$db = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

if (!$db) {
    die("error. " . mysqli_connect_error());
}
echo "Connected successfully.";
?>