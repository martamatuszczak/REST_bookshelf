<?php
require_once __DIR__ . '/Book.php';


$DBusername = "root";
$DBpassword = "coderslab";
$DBaddress = "localhost";
$DBname = "bookshelf";

$conn = new mysqli($DBaddress, $DBusername, $DBpassword, $DBname);
if($conn->errno) {
    die("Can't connect to database. Error: ". $conn->error);
}

