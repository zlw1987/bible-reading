<?php
$host = 'localhost';
$user = 'zlw1987_svca';
$password = 'svca@3131';
$dbName = 'zlw987_bible_app';

$connection = mysqli_connect($host,$user,$password,$dbName) or die('Connection Fail!'.mysqli_connect_error());
?>